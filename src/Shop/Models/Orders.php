<?php
namespace Shop\Models;

class Orders extends \Dsc\Mongo\Collections\Nodes
{
    public $number = null;
    public $status = null;                  // null / incomplete / pending (payment) / ready (for shipment/processing -- payment received or authorized) / hold / shipped / cancelled
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
    public $comments = null;
    public $currency = null;
    
    // Shipping Fields
    public $shipping_required = null;
    public $shipping_status = null;         // null / processing / shipped
    public $shipping_method = null;
    public $shipping_address = array();               
    public $tracking_numbers = array();
    
    // Payment Fields
    public $payment_method = null;
    public $billing_address = array();
    public $payments = array();
    
    // Lists
    public $items = array();            
    public $taxes = array();        
    public $coupons = array();      
    public $discounts = array();    
    public $credits = array();
    
    // TODO Add support for recurring charges products
    //public $recurring = array(
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
    //);
        
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
    
    /**
     * Creates an order from a cart object
     * 
     * @param \Shop\Models\Carts $cart
     * 
     * @return \Shop\Models\Orders
     */
    public static function fromCart( \Shop\Models\Carts $cart )
    {
        $order = new static;
        
        $order->grand_total = $cart->total();
        $order->sub_total = $cart->subtotal();
        $order->tax_total = $cart->taxTotal();
        $order->shipping_total = $cart->shippingTotal();
        $order->discount_total = $cart->discountTotal();
        $order->credit_total = $cart->creditTotal();
        
        $order->user_id = $cart->user_id;
        $order->user_email = $cart->user_email;
        // $order->is_guest = $cart->isGuest(); ? or is that from the checkout object?
        // $order->ip_address = $cart->ipAddress(); ? or is that from the checkout object?
        $order->comments = $cart->{'checkout.order_comments'};
        // $order->currency = $cart->currency; // TODO support multiple currencies
        
        // Items
        $order->items = $cart->items;
        
        // Shipping fields
        $order->shipping_required = $cart->shippingRequired();
        $order->shipping_status = 'processing';
        if ($shipping_method = $cart->shippingMethod()) 
        {
            $order->shipping_method = $shipping_method->cast();
        }        
        $order->shipping_address = $cart->{'checkout.shipping_address'}; 
        
        // TODO Payment/Billing fields
        $order->billing_address = $cart->{'checkout.billing_address'};
        
        // TODO Taxes
        // TODO Coupons
        // TODO Discounts
        // TODO Credits
        
        return $order;
    }
    
    /**
     * Add payment data to the model
     *
     * @param array $data
     * @return \Shop\Models\Orders
     */
    public function addPayment(array $data)
    {
        $this->payments[] = $data;
         
        return $this;
    }
}