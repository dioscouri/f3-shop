<?php
namespace Shop\Models;

class Campaigns extends \Dsc\Mongo\Collections\Describable
{
    use\Dsc\Traits\Models\Publishable;
    use\Dsc\Traits\Models\Ancestors;
    use\Dsc\Traits\Models\ForSelection;
    
    public $campaign_type = 'lto';
    
    public $rule_min_spent = null;
    public $reward_groups = array();
    public $expire_groups = array();

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
        
        // has the minimum spend amount for the publication period been met?
        if (!empty($this->rule_min_spent)) 
        {
        	// Get the total amount spent by the customer during the publication period
            $total = $customer->fetchTotalSpent($this->{'publication.start.local'}, $this->{'publication.end.local'});            
            if ($total < $this->rule_min_spent) 
            {
                throw new \Exception('Customer has not spent enough during the publication period');
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