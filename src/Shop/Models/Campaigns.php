<?php
namespace Shop\Models;

class Campaigns extends \Dsc\Mongo\Collections\Describable
{
    use\Dsc\Traits\Models\Publishable;
    use\Dsc\Traits\Models\Ancestors;
    use\Dsc\Traits\Models\ForSelection;
    
    public $campaign_type = 'lto';
    public $period_type = null;
    public $variable_period_days = null;
    public $fixed_period_start = null;
    public $fixed_period_end = null;
    public $duration_period_variable = null;
    public $duration_period_type = 'days';
    
    public $rule_min_spent = null;
    public $reward_groups = array();
    public $expire_groups = array();

    public $groups = array();
    public $groups_method = 'one';
    
    protected $__collection_name = 'shop.campaigns';
    protected $__type = 'campaigns';    
    protected $__config = array(
        'default_sort' => array(
            'path' => 1
        ),
    );

    protected function fetchConditions()
    {
        parent::fetchConditions();
        
        $this->ancestorsFetchConditions();
        $this->publishableFetchConditions();
    }

    protected function beforeValidate()
    {
        $this->ancestorsBeforeValidate();
        $this->forSelectionBeforeValidate('reward_groups');
        $this->forSelectionBeforeValidate('expire_groups');
        
        return parent::beforeValidate();
    }

    protected function beforeUpdate()
    {
        $this->ancestorsBeforeUpdate();
        
        return parent::beforeUpdate();
    }

    protected function beforeSave()
    {
        $this->publishableBeforeSave();
        $this->ancestorsBeforeSave();
        
        $this->rule_min_spent = (float) $this->rule_min_spent;
        
        return parent::beforeSave();
    }

    protected function afterUpdate()
    {
        $this->ancestorsAfterUpdate();
        
        return parent::afterUpdate();
    }
    
    /**
     * if started today, when would this campaign's benefits expire?
     * 
     * @return unknown
     */
    public function expires() 
    {
        switch($this->duration_period_type) 
        {
        	case "forever":
        	    $expires = null;
        	    break;
    	    case "days":
	        case "weeks":
            case "months":
            case "years":
                $expires = date('Y-m-d', strtotime( 'today +' . (int) $this->duration_period_variable . ' ' . $this->duration_period_type ) );
    	        break;        	    
        }
        
        return $expires;
    }
    
    /**
     * Determines whether or not a user qualifies for this campaign
     * 
     * @param \Users\Models\Users $user
     * @throws \Exception
     * @return \Shop\Models\Campaigns
     */
    public function customerQualifies( \Shop\Models\Customers $customer )
    {
        // Set $this->__is_validated = true if YES, user qualifies for this campaign.
        // throw an Exception if NO, user does not qualify.

        /**
         * is the campaign published?
         */
        if (!$this->published()) 
        {
            throw new \Exception('This campaign is not valid for today');
        }
        
        $period_start = null;
        $period_end = null;
        switch ($this->period_type) 
        {
        	case "variable":
        	    $period_start = date('Y-m-d', strtotime( 'today -' . (int) $this->variable_period_days . ' days'));
        	    $period_end = date('Y-m-d', strtotime('tomorrow'));        	    
        	    break;
    	    case "fixed":
    	        $period_start = $this->fixed_period_start;
    	        $period_end = $this->fixed_period_end;    	        
    	        break;
    	    default:
    	        throw new \Exception('Invalid period type');
    	        break;
        }
        
        // has the minimum spend amount for the qualification period been met?
        if (!empty($this->rule_min_spent)) 
        {
        	// Get the total amount spent by the customer during the qualification period
            $total = $customer->fetchTotalSpent($period_start, $period_end);
            if ($total < $this->rule_min_spent) 
            {
                throw new \Exception('Customer has not spent enough during the qualification period');
            }
        }
        
        /**
         * evaluate shopper groups against $this->groups
         */
        if (!empty($this->groups))
        {
            $groups = array();

            if (empty($customer->id))
            {
                // Get the default group
                $group_id = \Shop\Models\Settings::fetch()->{'users.default_group'};
                if (!empty($group_id)) {
                    $groups[] = (new \Users\Models\Groups)->setState('filter.id', (string) $group_id)->getItem();
                }
            }
            elseif (!empty($customer->id))
            {
                $groups = $customer->groups();
            }
             
            $group_ids = array();
            foreach ($groups as $group)
            {
                $group_ids[] = (string) $group->id;
            }
             
            switch ($this->groups_method)
            {
                case "none":
                    $intersection = array_intersect($this->groups, $group_ids);
                    if (!empty($intersection))
                    {
                        // TODO Chagne the error messages!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
                        throw new \Exception('You do not qualify for this campaign.');
                    }
        
                    break;
                case "all":
                    // $missing_groups == the ones from $this->groups that are NOT in $group_ids
                    $missing_groups = array_diff($this->groups, $group_ids);
                    if (!empty($missing_groups))
                    {
                        throw new \Exception('You do not qualify for this campaign.');
                    }
                    	
                    break;
                case "one":
                default:
                    $intersection = array_intersect($this->groups, $group_ids);
                    if (empty($intersection))
                    {
                        throw new \Exception('You do not qualify for this campaign.');
                    }
        
                    break;
            }
        }        

        /**
         * if we made it this far, the user qualifies
         */        
        $this->__is_validated = true;
        
        return $this;
    }
    
    /**
     * 
     * @param \Shop\Models\Customers $customer
     * @return \Shop\Models\Campaigns
     */
    public function rewardCustomer( \Shop\Models\Customers &$customer )
    {
        /**
         * Add customer to reward_groups
         */
        if (!empty($this->reward_groups)) 
        {
        	$customer = $customer->addToGroups($this->reward_groups);
        }
        
        return $this;
    }
    
    /**
     * 
     * @param \Shop\Models\Customers $customer
     * @return \Shop\Models\Campaigns
     */
    public function expireCustomerRewards( \Shop\Models\Customers &$customer )
    {
        /**
         * Remove customer from expire_groups
         */
        if (!empty($this->expire_groups))
        {            
            $customer = $customer->removeFromGroups($this->expire_groups);           
        }
                
        return $this;
    }
}