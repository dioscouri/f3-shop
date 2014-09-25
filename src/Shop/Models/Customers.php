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
            if ($indexed_campaign->__descendants = $indexed_campaign->ancestorsGetDescendants()) 
            {
            	foreach ($indexed_campaign->__descendants as $descendant) 
            	{
            		if (isset($indexed_campaigns[(string)$descendant->id])) 
            		{
            		    $next_match = $descendant;
            		}
            	}
            }
            
            $indexed_campaign->__replace_with = null;
            if ($next_match->id != $indexed_campaign->id) 
            {
                $indexed_campaign->__replace_with = $next_match;
            }
            
            if (!array_key_exists((string) $next_match->id, $matches))
            {
                $matches[(string) $next_match->id] = $next_match;
            }            
            
            //echo $indexed_campaign->title . " (which has " . count($indexed_campaign->__descendants) . " descendants) <br/>";            
        }
        
        // Check all of the customer's current campaigns, 
        // and if they have expired 
        // OR if they are being replaced by a descendant,
        // expire the benefits
        $active_campaign_ids = array();
        if ($active_campaigns = (array) $this->{'shop.active_campaigns'}) 
        {
        	foreach ($active_campaigns as $key=>$active_campaign_cast) 
        	{
        		$active_campaign_id = (string) \Dsc\ArrayHelper::get($active_campaign_cast, 'id');
        		$active_campaign_expires_time = \Dsc\ArrayHelper::get($active_campaign_cast, 'expires.time');
        		$active_campaign = (new \Shop\Models\Campaigns)->setState('filter.id', $active_campaign_id)->getItem();

        		$replacing_with_descendant = false;
        		// Does the campaign have descendants?
        		if ($active_campaign->__descendants = $active_campaign->ancestorsGetDescendants())
        		{
        		    foreach ($active_campaign->__descendants as $descendant)
        		    {
        		        if (isset($matches[(string)$descendant->id]))
        		        {
        		            $replacing_with_descendant = true;
        		        }
        		    }
        		}        		
        		
        		// are we replacing this?  Has it expired?
        		if ($active_campaign_expires_time < time() || $replacing_with_descendant) 
        		{
        		    // echo "Removing customer from: " . $active_campaign->title . "<br/>";
        		    $active_campaign->expireCustomerRewards( $this );
        		    unset($active_campaigns[$key]);
        		} 
        		else 
        		{
        		    // Only track this if it really is active
        		    $active_campaign_ids[] = $active_campaign_id;        			
        		}
        	}
        }
        
        
        //echo "Customer's active campaigns: <br/>";
        //echo \Dsc\Debug::dump($active_campaigns);

        //echo "Customer should only be in these campaigns: <br/>";

        // Now add the customer to any new campaigns they qualify for
        foreach ($matches as $match)
        {
            //echo $match->title . "<br/>";
            
            if (!in_array((string) $match->id, $active_campaign_ids)) 
            {
                $match->rewardCustomer( $this );
                //echo "so Adding customer to: " . $match->title . "<br/>";
                $active_campaigns[] = array(
                	'id' => (string) $match->id,
                    'title' => (string) $match->title,
                    'activated' => \Dsc\Mongo\Metastamp::getDate('now'),
                    'expires' => \Dsc\Mongo\Metastamp::getDate( $match->expires() ),
                );
            }
        }

        // Track current campaigns in the user object, shop.active_campaigns
        $this->{'shop.active_campaigns'} = array_values($active_campaigns);
        
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
    
    /**
     * 
     * @param unknown $id
     * @return \Shop\Models\Customers
     */
    public static function recalculateTotals( $id ) 
    {
        $model = new static;
        $model = $model->setState('filter.id', $id)->getItem();
        if (!empty($model->id)) 
        {
            $model->{'shop.total_spent'} = $model->totalSpent(true);
            $model->{'shop.orders_count'} = $model->ordersCount(true);
            $model->save()->checkCampaigns();
        }
        
        return $model;
    }
    
    /**
     * Determine whether or not the user has purchased a product
     * 
     * @param \Users\Models\Users $user
     * @param \Shop\Models\Products $product
     * @return boolean
     */
    public static function hasUserPurchasedProduct( \Users\Models\Users $user, \Shop\Models\Products $product ) 
    {
        $model = (new \Shop\Models\Orders)
        ->setState('filter.user', $user->id)
        ->setState('filter.product_id', $product->id)
        ->setState('filter.status_excludes', \Shop\Constants\OrderStatus::cancelled)
        ->setState('filter.financial_status', array( \Shop\Constants\OrderFinancialStatus::paid, \Shop\Constants\OrderFinancialStatus::authorized ) );
        
        if ($model->getCount() > 0) 
        {
            return true;
        }
        
        return false;
    }
    
    /**
     * Get a paginated list of the customer's purchased products
     *
     * @param \Users\Models\Users $user
     * @param \Shop\Models\Products $product
     * @return boolean
     */
    public static function purchasedProducts( \Users\Models\Users $user, $options=array() )
    {
        $options = $options + array(
            'limit' => 10,
            'offset' => 0,
            'keyword' => null
        );
        
        $limit = $options['limit'];
        $offset = $options['offset']; 
        
        $model = (new \Shop\Models\Orders)
        ->setState('filter.keyword', $options['keyword'])
        ->setState('filter.user', $user->id)
        ->setState('filter.status_excludes', \Shop\Constants\OrderStatus::cancelled)
        ->setState('filter.financial_status', array( \Shop\Constants\OrderFinancialStatus::paid, \Shop\Constants\OrderFinancialStatus::authorized, \Shop\Constants\OrderFinancialStatus::pending ) )
        ;
        
        $conditions = $model->conditions();
        
        $pipeline = array(
            array(
                '$match' => $conditions
            ),
            array(
                '$sort' => array( 'metadata.created.time' => -1 )
            ),            
            array(
                '$unwind' => '$items'
            ),            
            array(
                '$project' => array(
                    '_id' => 0,
                    'title' => '$items.product.title',
                    'slug' => '$items.product.slug',
                    'product_id' => '$items.product_id',
                    'variant_id' => '$items.variant_id',
                    'price' => '$items.price',
                    'quantity' => '$items.quantity',
                    'attribute_title' => '$items.attribute_title',
                    'sku' => '$items.sku',
                    'model_number' => '$items.model_number',
                    'order_created' => '$metadata.created',
                    'order_id' => '$_id',
                )
            ),
            array(
                '$match' => array(
                    'product_id' => array(
                        '$nin' => array('', null)
                    )
                )
            ),
        );

        if (!empty($options['keyword'])) 
        {
            $key = new \MongoRegex('/'.$options['keyword'].'/i');
            
            $pipeline[] = array(
                '$match' => array(
                    '$or' => array(
                        array( 'title' => $key ),
                        array( 'sku' => $key ),
                        array( 'order_id' => $key ),
                    )
                )
            );
            
        }
        
        if (isset($options['is_reviewed']) && is_bool($options['is_reviewed'])) 
        {
            // get the product_ids that have been reviewed
            $reviewed_product_ids = \Shop\Models\ProductReviews::collection()->distinct('product_id', array(
                'user_id' => $user->id
            )); 
            
            if ($options['is_reviewed']) {
                // Add an $in filter to the pipeline for product_id
                $pipeline[] = array(
                    '$match' => array(
                        'product_id' => array(
                            '$in' => $reviewed_product_ids
                        )
                    )
                );
            }
            else 
            {
                // Add an $nin filter to the pipeline for product_id
                $pipeline[] = array(
                    '$match' => array(
                        'product_id' => array(
                            '$nin' => $reviewed_product_ids
                        )
                    )
                );                
            }
        }
        
        $count_pipeline = $pipeline;
        $count_pipeline[] = array(
            '$group' => array(
                '_id' => null,
                'count' => array(
                    '$sum' => 1
                )
            )
        );
        
        $pipeline[] = array(
            '$skip' => $offset * $limit
        );            
        
        $pipeline[] = array(
            '$limit' => $limit
        );

        $agg = \Shop\Models\Orders::collection()->aggregate($pipeline);
        
        $result = null;
        //\Dsc\System::addMessage(\Dsc\Debug::dump($agg));
        if (!empty($agg['ok']) && !empty($agg['result']))
        {
            $agg_count = \Shop\Models\Orders::collection()->aggregate($count_pipeline);
            $total = isset($agg_count['result'][0]['count']) ? $agg_count['result'][0]['count'] : \Shop\Models\Orders::collection()->count($conditions);
 
            $result = new \Dsc\Pagination( $total, $limit );
            $items = array();
            foreach ($agg['result'] as $doc) 
            {
                if (empty($doc['product_id'])) 
                {
                    continue;
                }
                
                $item = (new \Shop\Models\Products)->setState('filter.id', $doc['product_id'])->getItem();
                if (!empty($item->id)) 
                {
                    $item->order_item = $doc;
                    if (empty($item->order_item['variant_id'])) {
                        $item->order_item['variant_id'] = null;
                    }
                    $item->order_item['image'] = $item->variantImage( $item->order_item['variant_id'] );
                    $items[] = $item;                    
                }
            }
            $result->items = $items;
        }
    
        return $result;
    }
}