<?php
namespace Shop\Models;

class Orders extends \Dsc\Mongo\Collections\Taggable
{
    public $number = null;
    public $status = \Shop\Constants\OrderStatus::open;
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
    public $fulfillment_status = \Shop\Constants\OrderFulfillmentStatus::unfulfilled;
    public $shipping_required = null;
    public $shipping_method = null;
    public $shipping_address = array();               
    public $shipments = array();            // an array of Shipment objects, each with a tracking number
    public $tracking_numbers = array();     // TODO Remove this and make it a field within each shipments object
    
    // Payment Fields
    public $financial_status = \Shop\Constants\OrderFinancialStatus::pending;
    public $payment_method = null;          // a PaymentMethod model cast as an array
    public $billing_address = array();
    public $payments = array();             // an array of Payment models cast as arrays
    
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
                    'name' => $identity->fullName() 
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
        
        if (empty($this->number)) 
        {
            $this->number = $this->createNumber();
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
        
        $order->number = $order->createNumber();
        
        $order->grand_total = $cart->total();
        $order->sub_total = $cart->subtotal();
        $order->tax_total = $cart->taxTotal();
        $order->shipping_total = $cart->shippingTotal();
        $order->discount_total = $cart->discountTotal();
        $order->credit_total = $cart->creditTotal();
        
        $order->user_id = $cart->user_id;
        $order->user_email = $cart->user_email;
        if (empty($order->user_email)) {
        	$user = (new \Users\Models\Users)->load(array('_id'=>$order->user_id));
        	if (!empty($user->email)) {
        		$order->user_email = $user->email;
        	}
        }
        
        // $order->is_guest = $cart->isGuest(); ? or is that from the checkout object?
        // $order->ip_address = $cart->ipAddress(); ? or is that from the checkout object?
        $order->comments = $cart->{'checkout.order_comments'};
        // $order->currency = $cart->currency; // TODO support multiple currencies
        
        // Items
        $order->items = $cart->items;
        
        // Shipping fields
        $order->shipping_required = $cart->shippingRequired();
        if ($shipping_method = $cart->shippingMethod()) 
        {
            $order->shipping_method = $shipping_method->cast();
        }        
        $order->shipping_address = $cart->{'checkout.shipping_address'}; 
        
        // TODO Payment/Billing fields
        $order->billing_address = $cart->{'checkout.billing_address'};
        
        // TODO Taxes
        
        // Coupons
        $order->coupons = $cart->{'coupons'};
        
        // Discounts
        $order->discount_total = $cart->discountTotal();
        
        // TODO Credits
        
        return $order;
    }
    
    /**
     * Creates a human-readable version of the order id (it's MongoID)
     * @return unknown
     */
    public function createNumber()
    {
        if (empty($this->id)) 
        {
        	$this->id = new \MongoId;
        }
        
        $id = (string) $this->id;
        
        $number = strtolower( substr( $id, 0, 4 ) . '-' . substr( $id, 4, 4 ) . '-' . substr( $id, 8, 4 ) . '-' . substr( $id, 12, 4 ) . '-' . substr( $id, 16, 4 ) . '-' . substr( $id, 20, 4 ) );
        
        return $number;
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
    
    /**
     * Return \Shop\Mopdels\ShippingMethods object if found
     *
     */
    public function shippingMethod()
    {
        if (!$this->{'shipping_method'})
        {
            return false;
        }
    
        $method = new \Shop\Models\ShippingMethods( $this->{'shipping_method'} );
        
        return $method;
    }
    
    /**
     * Return \Shop\Mopdels\PaymentMethods object if found
     *
     */
    public function paymentMethod()
    {
        if (!$this->{'payment_method'})
        {
            return false;
        }
    
        $method = new \Shop\Models\PaymentMethods( $this->{'payment_method'} );
    
        return $method;
    }
    
    /**
     * 
     * @return \Shop\Models\Address
     */
    public function shippingAddress()
    {
        if (empty($this->{'shipping_address'})) 
        {
        	return null;
        }
        
        $model = new \Shop\Models\Address( $this->{'shipping_address'} );
        
        return $model;
    }
    
    /**
     * 
     * @return \Shop\Models\Address
     */
    public function billingAddress()
    {
        if (empty($this->{'billing_address'})) {
            return null;
        }
        
        $model = new \Shop\Models\Address( $this->{'billing_address'} );
    
        return $model;
    }
    
    /**
     * Completes an order.
     * 
     * Trigger this on newly-made orders to perform tasks such as:
     * Sending an email to the customer
     * Updating available product quantities.
     * 
     * This does NOT do the following:
	 * Enabling file downloads
	 * Enabling subscriptions
	 * as those would be triggered upon order fulfillment == $this->fulfill()
	 * 
	 * Trigger a Listener event to notify observers
     */
    public function accept()
    {
        // 1. Update quantities
        foreach ($this->items as $item) 
        {
            $found = false;

        	$product = (new \Shop\Models\Products)->setState('filter.id', $item['product_id'])->getItem();
        	if (!empty($product->id) && (string) $product->id == (string) $item['product_id']) 
        	{
        		foreach ($product->variants as $variant) 
        		{
        			if ((string) $variant['id'] == (string) $item['variant_id']) 
        			{
        			    $found = true;
        				$variant['quantity'] = $variant['quantity'] - 1;
        				break; 
        			}
        		}
        		
        		if ($found) {
            		$product->save();
        		}
        	} 
        	else 
        	{
        		$this->setError('Could not update variant quantities -- Invalid Product ID');
        	}
        }
        
        // 2. Add an email to the Mailer
        // TODO confirm that user_email is set
        if ($this->user_email) {
            $this->sendEmailNewOrder();
        }
        
        // TODO 3. Increase hit counts on coupons used in order

        // trigger event
        $this->__complete_event = \Dsc\System::instance()->trigger( 'onShopCompleteOrder', array(
        	'order' => $this
        ) );
        
        return $this;
    }
    
    /**
     * Fulfilling an order is the act of delivering the product to the customer
     * and marking the order as closed.
     * Even digital products (subscriptions, downloads, gift cards) are delivered upon fulfillment
     *    
     * @return \Shop\Models\Orders
     */
    public function fulfill()
    {
        // TODO send shipment notification emails
        // TODO send gift certificate emails
        // TODO trigger the onShopFulfillOrder event
        // TODO Close the order and mark it as fulfilled
        
        return $this;
    }
    
    /**
     * Send out new order emails
     * 
     * @param array $recipients
     */
    public function sendEmailNewOrder( array $recipients=array() )
    {
        \Base::instance()->set('order', $this);
        \Base::instance()->set('settings', \Shop\Models\Settings::fetch());
        
        $html = \Dsc\System::instance()->get( 'theme' )->renderView( 'Shop/Views::emails_html/new_order.php' );
        $text = \Dsc\System::instance()->get( 'theme' )->renderView( 'Shop/Views::emails_text/new_order.php' );
        
        $order_number = $this->number;
        $subject = 'Order Confirmation #' . $order_number;

        $this->__sendEmailNewOrder = \Dsc\System::instance()->get('mailer')->send($this->user_email, $subject, array($html, $text) );
        
        return $this;
    }
    
    /**
     * Send out status update emails 
     *  
     * @param array $recipients
     */
    public function sendEmailStatusUpdate( array $recipients=array() )
    {
        
        return $this;
    }
}