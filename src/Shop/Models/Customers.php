<?php
namespace Shop\Models;

class Customers extends \Users\Models\Users
{
    /**
     * Gets the customer's highest-ordered Users\Group
     * to be used for determining pricing and primary group status
     * 
     * @return \Users\Models\Groups
     */
    public static function primaryGroup( \Users\Models\Users $user=null )
    {
        $group = new \Users\Models\Groups;
        
        if (!empty($user)) 
        {
            if ($groups = $user->groups()) 
            {
                $group = $groups[0];
            }
        }
        
        if (empty($group->id)) 
        {
            // Set this to be a default group, as configured in the Shop config
            $group_id = \Shop\Models\Settings::fetch()->{'users.default_group'};
            if (!empty($group_id)) {
                $group = $group->setState('filter.id', (string) $group_id)->getItem();
            }        	
        }
        
        return $group;
    }
    
    /**
     * Helper method for creating select list options
     *
     * @param array $query
     * @return multitype:multitype:string NULL
     */
    public static function forSelection(array $query=array(), $id_field='_id' )
    {
        $model = new static;
        \FB::log($query);    
        $cursor = $model->collection()->find($query, array("last_name"=>1, "first_name"=>1, "email"=>1) );
        $cursor->sort(array(
            "last_name"=>1, "first_name"=>1
        ));
    
        $result = array();
        foreach ($cursor as $doc) {
            $array = array(
                'id' => (string) $doc[$id_field],
                'text' => htmlspecialchars( trim( $doc['first_name'] . ' ' . $doc['last_name'] . ': ' . $doc['email'] ), ENT_QUOTES ),
            );
            $result[] = $array;
        }
    \FB::log($result);
        return $result;
    }
    
    /**
     * Gets the total amount a customer has spent
     * 
     * @param string $refresh
     * @return number
     */
    public function totalSpent($refresh=false)
    {
        if (empty($refresh))
        {
            return (float) $this->{'shop.total_spent'};
        }
                
        $this->{'shop.total_spent'} = 0;
        
        $conditions = (new \Shop\Models\Orders)->setState('filter.user', $this->id)->setState('filter.financial_status', \Shop\Constants\OrderFinancialStatus::paid)->conditions();
        
        $agg = \Shop\Models\Orders::collection()->aggregate(array(
        	array( 
        	    '$match' => $conditions 
            ),
            array( 
                '$group' => array(
            	   '_id' => '$user_id',
                   'total' => array( '$sum' => '$grand_total' )
                ) 
            )
        ));
        
        if (!empty($agg['ok']) && !empty($agg['result'])) 
        {
            $this->{'shop.total_spent'} = (float) $agg['result'][0]['total'];
        }
        
        return (float) $this->{'shop.total_spent'};
    }
    
    /**
     * Calculates the total amount a customer has spent
     * during the specified time period
     *
     * @param string $refresh
     * @return number
     */
    public function fetchTotalSpent($start=null, $end=null)
    {
        $model = (new \Shop\Models\Orders)
            ->setState('filter.user', $this->id)
            ->setState('filter.financial_status', \Shop\Constants\OrderFinancialStatus::paid);
        
        if (!empty($start)) {
        	$model->setState('filter.created_after', $start);
        }
        
        if (!empty($end)) {
            $model->setState('filter.created_before', $end);
        }
        
        $conditions = $model->conditions();
    
        $agg = \Shop\Models\Orders::collection()->aggregate(array(
            array(
                '$match' => $conditions
            ),
            array(
                '$group' => array(
                	'_id' => '$user_id',
                    'total' => array( '$sum' => '$grand_total' )
                )
            )
        ));
    
        $total = 0;
        if (!empty($agg['ok']) && !empty($agg['result']))
        {
            $total = (float) $agg['result'][0]['total'];
        }
    
        return (float) $total;
    }    
    
    /**
     * Gets the number of orders the customer has made
     * 
     * @param string $refresh
     */
    public function ordersCount($refresh=false)
    {
        if (empty($refresh)) 
        {
        	return (int) $this->{'shop.orders_count'};
        }
        
        $this->{'shop.orders_count'} = (int) (new \Shop\Models\Orders)->setState('filter.user', $this->id)->setState('filter.financial_status', \Shop\Constants\OrderFinancialStatus::paid)->getCount();
        
        return $this->{'shop.orders_count'};
    }
    
    /**
     * 
     * @return Ambigous <NULL, unknown>
     */
    public function primaryAddress()
    {
        $item = null;
        
        if ($items = (new \Shop\Models\CustomerAddresses)->setState('filter.user', (string) $this->id )->getItems()) 
        {
        	$item = $items[0];
        }
        
        return $item;
    }
    
    /**
     * Check that customer has been rewarded all the appropriate campaigns 
     */
    public function checkCampaigns()
    {
        // get all the published campaigns and see if the customer satisfies any of them.
        $campaigns = (new \Shop\Models\Campaigns)->setState('filter.published_today', true)->setState('filter.publication_status', 'published')->getList();
        //echo "Published campaigns: " . count($campaigns) . "<br/>";
        
        $indexed_campaigns = array();
        foreach ($campaigns as $campaign) 
        {
            try {
                $campaign->__user_qualifies = $campaign->customerQualifies( $this );
                $indexed_campaigns[(string) $campaign->id] = $campaign;
            } catch (\Exception $e) {
                continue;
            }
        }
        
        $matches = array();
        
        // if so, grant the customer the benefits, but only if the customer doesn't satisfy the rules of any descendants
        
        //echo "Customer qualifies for this many campaigns: " . count($indexed_campaigns) . "<br/>";
        //echo "and they are: <br/>";        
        foreach ($indexed_campaigns as $key=>$indexed_campaign) 
        {
            $next_match = $indexed_campaign;
            
        	// Does the campaign have descendants?
            if ($descendants = $indexed_campaign->ancestorsGetDescendants()) 
            {
            	foreach ($descendants as $descendant) 
            	{
            		if (isset($indexed_campaigns[(string)$descendant->id])) 
            		{
            		    $next_match = $descendant;
            		}
            	}
            }
            
            if (!array_key_exists((string) $next_match->id, $matches))
            {
                $matches[(string) $next_match->id] = $next_match;
            }            
            
            //echo $indexed_campaign->title . " (which has " . count($descendants) . " descendants) <br/>";            
        }
        
        // Check all of the customer's current campaigns, and if they no longer match them, expire the benefits
        $active_campaigns = (array) $this->{'shop.active_campaigns'};
        //echo "Customer's active campaigns: <br/>";
        //echo \Dsc\Debug::dump($active_campaigns);
        
        foreach ($active_campaigns as $active_campaign_id) 
        {
            $active_campaign = (new \Shop\Models\Campaigns)->setState('filter.id', $active_campaign_id)->getItem();
            if (!empty($active_campaign->id) && !array_key_exists((string) $active_campaign->id, $matches))
            {
                // echo "Removing customer from: " . $active_campaign->title . "<br/>";
                $active_campaign->expireCustomerRewards( $this );
            }
        }

        //echo "Customer should only be in these campaigns: <br/>";        
        // Track current campaigns in the user object, shop.active_campaigns
        $match_ids = array();
        foreach ($matches as $match)
        {
            //echo $match->title . "<br/>";
            
            if (!in_array((string) $match->id, $active_campaigns)) 
            {
                $match->rewardCustomer( $this );
                //echo "so Adding customer to: " . $match->title . "<br/>";            	
            }
            
            $match_ids[] = (string) $match->id;
        }

        //return $this;
        $this->{'shop.active_campaigns'} = $match_ids;
        return $this->save();
    }
    
    /**
     * Adds an array of campaign ids to the user object
     * indicating that the user is actively part of a marketing campaign
     * 
     * @param array $ids
     * @return \Shop\Models\Customers
     */
    public function activateCampaigns(array $ids, $save=true)
    {
        $active_campaigns = (array) $this->{'shop.active_campaigns'};
        
        $new = false;
        foreach ($ids as $id)
        {
            if (!in_array((string) $id, $active_campaigns)) 
            {
                $active_campaigns[] = $id;
                $new = true;
            }            
        }
        
        $this->{'shop.active_campaigns'} = $active_campaigns;
        
        if ($new && $save) {
            return $this->save();        
        }
        
        return $this;
    }
    
    /**
     * Adds an array of campaign ids to the user object
     * indicating that the user is actively part of a marketing campaign
     *
     * @param array $ids
     * @return \Shop\Models\Customers
     */
    public function deactivateCampaigns(array $ids, $save=true)
    {
        $active_campaigns = (array) $this->{'shop.active_campaigns'};
    
        $this->{'shop.active_campaigns'} = array_diff($active_campaigns, $ids);
    
        if ($save) {
            return $this->save();        
        }
        
        return $this;
    }
}