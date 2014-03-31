<?php
namespace Shop\Models;

class Orders extends \Dsc\Mongo\Collections\Nodes
{
    public $order_number = null;
    public $status = null;
    public $status_history = array();
    
    public $grand_total = null;
    public $sub_total = null;
    public $tax_total = null;
    public $shipping_total = null;
    public $shipping_tax = null;
    public $discount_total = null;
    public $credit_total = null;

    public $user_id = null;
    public $user_email = null;
    public $is_guest = null;
    public $ip_address = null;
    public $order_comments = null;
    public $currency = null;
    
    public $requires_shipping = null;
    public $shipping_status = null;
    public $shipping_method = null;
    public $shipping_address = array();               
    public $tracking_numbers = array();
    
    public $payment_method = null;
    public $billing_address = array();
    public $payments = array();
    
    public $items = array();            
    public $taxes = array();        
    public $coupons = array();      
    public $discounts = array();    
    
    public $recurring = array(
        // enabled => null,         // boolean, is there a trial period or no?                    
    	// amount => null,          // the amount of the recurring charge
    	// payment_count => null,   // the number of recurring payments that should be made
        // period_unit => null,         // D, W, M, Y
        // period_interval => null,     // how many period_units between payments? 
        // trial => array(
                // enabled => null,     // boolean, is there a trial period or no?
                // price => null,                               
                // period_unit => null,                        
                // period_interval => null,
        // )
    );
        
    protected $__collection_name = 'shop.orders';
    protected $__type = 'shop.orders';
    protected $__config = array(
        'default_sort' => array(
            'metadata.created.time' => -1 
        ) 
    );

    protected function fetchConditions()
    {
        parent::fetchConditions();
        
        $this->setCondition( 'type', $this->__type );
        
        $filter_user = $this->getState('filter.user');
        if (strlen($filter_user))
        {
            $this->setCondition('user_id', new \MongoId((string) $filter_user));
        }
        
        return $this;
    }

    /**
     * 
     */
    protected function beforeValidate()
    {
        if (! $this->get( 'metadata.creator' ))
        {
            $identity = \Dsc\System::instance()->get( 'auth' )->getIdentity();
            if (! empty( $identity->id ))
            {
                $this->set( 'metadata.creator', array(
                    'id' => $identity->id,
                    'name' => $identity->getName() 
                ) );
            }
            else
            {
                $this->set( 'metadata.creator', array(
                    'id' => \Dsc\System::instance()->get( 'session' )->id(),
                    'name' => 'session' 
                ) );
            }
        }
        
        if (!empty($this->items)) 
        {
            $this->items = array_values($this->items);
        }
        
        return parent::beforeValidate();
    }
}