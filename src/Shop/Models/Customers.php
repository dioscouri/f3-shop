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
        
        $this->{'shop.orders_count'} = (new \Shop\Models\Orders)->setState('filter.user', $this->id)->setState('filter.financial_status', \Shop\Constants\OrderFinancialStatus::paid)->getCount();
        
        return (int) $this->{'shop.orders_count'};
    }
}