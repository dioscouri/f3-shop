<?php
namespace Shop\Models;

class Orders extends \Dsc\Mongo\Collections\Taggable
{
    public $number = null;
    public $status = \Shop\Constants\OrderStatus::open;
    public $status_history = array();
    
    public $grand_total = 0;
    public $sub_total = 0;
    public $tax_total = 0;
    public $shipping_total = 0;
    public $shipping_tax = 0;
    public $discount_total = 0;
    public $credit_total = 0;
    public $giftcard_total = 0;

    public $customer = array();     // Users\Models\Users cast as an array
    public $customer_name = null;
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
    public $payment_required = true;
    public $payment_method = null;          // a PaymentMethod model cast as an array
    public $billing_address = array();
    public $payments = array();             // an array of Payment models cast as arrays
    
    // Lists
    public $items = array();            
    public $taxes = array();        
    public $coupons = array();
    public $auto_coupons = array();
    public $discounts = array();    
    public $credits = array();
    
    // internal log of changes to the order
    public $history = array();
    
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
        
        $filter_keyword = $this->getState('filter.keyword');
        if ($filter_keyword&&is_string($filter_keyword))
        {
            $key = new \MongoRegex('/'.$filter_keyword.'/i');
        
            $where = array();
        
            $regex = '/^[0-9a-z]{24}$/';
            if (preg_match($regex, (string) $filter_keyword))
            {
                $where[] = array(
                    '_id' => new \MongoId((string) $filter_keyword)
                );
            }
            $where[] = array(
                'customer_name' => $key
            );
            $where[] = array(
                'user_email' => $key
            );
            $where[] = array(
                'items.product.title' => $key
            );
            $where[] = array(
                'items.sku' => $key
            );
        
            $this->setCondition('$or', $where);
        }        
        
        $filter_user = $this->getState('filter.user');
        if (strlen($filter_user))
        {
            $this->setCondition('user_id', new \MongoId((string) $filter_user));
        }
        
        $filter_status = $this->getState('filter.status');
        if (strlen($filter_status))
        {
            $this->setCondition('status', $filter_status);
        }
        
        $filter_fulfillment_status = $this->getState('filter.fulfillment_status');
        if (strlen($filter_fulfillment_status))
        {
            $this->setCondition('fulfillment_status', $filter_fulfillment_status);
        }
        
        $filter_financial_status = $this->getState('filter.financial_status');
        if (strlen($filter_financial_status))
        {
            $this->setCondition('financial_status', $filter_financial_status);
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
    
    protected function beforeSave()
    {
        // if all the items are fulfilled, the order is fulfilled
        if ($this->status == \Shop\Constants\OrderStatus::open
            && $this->fulfillment_status != \Shop\Constants\OrderFulfillmentStatus::fulfilled
        ) 
        {
            $all_fulfilled = true;
            foreach ($this->items as $item)
            {
                if (\Dsc\ArrayHelper::get( $item, 'fulfillment_status' ) != \Shop\Constants\OrderFulfillmentStatus::fulfilled) 
                {
                    $all_fulfilled = false;
                }
            }        	
        }
        
        return parent::beforeSave();
    }
    
    /**
     * Gets the associated user object
     * 
     * @return unknown
     */
    public function user()
    {
        $user = (new \Users\Models\Users)->load(array('_id'=>$this->user_id));
    
        return $user;
    }
    
    /**
     * Gets the associated customer object
     *
     * @return unknown
     */
    public function customer()
    {
        $user = (new \Shop\Models\Customers)->load(array('_id'=>$this->user_id));
    
        return $user;
    }
    
    /**
     * Gets a customer's full name,
     * defaulting to email
     * 
     * @return unknown
     */
    public function customerName()
    {
        $name = $this->customer_name;
        if (empty($name)) {
            $user = (new \Users\Models\Users)->load(array('_id'=>$this->user_id));
            $name = $user->fullName();
        }
        
        if (empty($name)) {
            $name = $this->user_email;
        }
        
        return $name;
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
        $cart_array = $cart->cast();
        unset($cart_array['_id']);
        unset($cart_array['type']);
        unset($cart_array['metadata']);
        unset($cart_array['name']);
        
        $order = new static($cart_array);
        
        $order->number = $order->createNumber();
        
        $order->grand_total = $cart->total();
        $order->sub_total = $cart->subtotal();
        $order->tax_total = $cart->taxTotal();
        $order->shipping_total = $cart->shippingTotal();
        $order->discount_total = $cart->discountTotal();
        $order->credit_total = $cart->creditTotal();
        $order->giftcard_total = $cart->giftCardTotal();

        $user = (new \Users\Models\Users)->load(array('_id'=>$order->user_id));
        $order->customer = $user->cast();
        $order->customer_name = $user->fullName();
        
        if (empty($order->user_email)) {
        	if (!empty($user->email)) {
        		$order->user_email = $user->email;
        	}
        }
        
        // $order->is_guest = $cart->isGuest(); ? or is that from the checkout object?
        // $order->ip_address = $cart->ipAddress(); ? or is that from the checkout object?
        $order->comments = $cart->{'checkout.order_comments'};
        
        // Shipping fields
        $order->shipping_required = $cart->shippingRequired();
        if ($shipping_method = $cart->shippingMethod()) 
        {
            $order->shipping_method = $shipping_method->cast();
        }        
        $order->shipping_address = $cart->{'checkout.shipping_address'}; 
        
        // TODO Payment/Billing fields
        $order->billing_address = $cart->{'checkout.billing_address'};
        $order->payment_required = $cart->paymentRequired();
        
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
     * Deduct giftcard.amount from any giftcards
     * Deduct credit.total from customer credit balance
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
        // #. Update quantities
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
        
        // #. Add an email to the Mailer
        if ($this->user_email) {
            $this->sendEmailNewOrder();
        }
        
        // #. Increase total spent and orders count
        $this->updateCustomerTotals();
        
        // TODO #. Increase hit counts on coupons used in order, https://github.com/dioscouri/f3-shop/issues/90
        
        // #. Decrease value of any used gift certificates
        $this->redeemGiftCards();

        // #. Add a negative credit record for historical purposes
        $this->deductCredit();
        
        // trigger event
        $this->__accept_event = \Dsc\System::instance()->trigger( 'onShopAcceptOrder', array(
        	'order' => $this
        ) );
        
        return $this;
    }
    
    /**
     * Redeems any gift cards used in this order
     * 
     * @return \Shop\Models\Orders
     */
    public function redeemGiftCards()
    {
        if (empty($this->giftcards)) 
        {
        	return $this;
        }
        
        foreach ($this->giftcards as $giftcard_array)
        {
            if (!empty($giftcard_array['amount']))
            {
                try {
                    $giftcard = (new \Shop\Models\OrderedGiftCards)->load(array('_id'=>new \MongoId( (string) $giftcard_array['_id'])))->redeemForOrder( $giftcard_array['amount'], $this );
                } 
                catch(\Exception $e) {
                	$this->log( 'Failed to redeem gift card #' . (string) $giftcard_array['code'] . ' for order #' . $this->id . '.  Message: ' . $e->getMessage(), 'ERROR', 'ShopModelsOrders::redeemGiftCard'  );
                }                
            }
        }    
        
        return $this;
    }
    
    /**
     * Updates the customer summary totals
     * including: total spent, total number of orders, etc
     *
     * @return \Shop\Models\Orders
     */
    public function updateCustomerTotals()
    {
        $customer = $this->customer();
        if (!empty($customer->id)) 
        {
            $customer->totalSpent(true);
            $customer->ordersCount(true);
            $customer->save();
        }
        
        return $this;
    }
    
    /**
     * If this order has a credit_total, deduct it from the customer's shop.credits.balance
     * by immediately issuing a negative credit
     * 
     * @return \Shop\Models\Orders
     */
    public function deductCredit()
    {
        if ($this->credit_total) {
            $credit = (new \Shop\Models\Credits)->bind(array(
            	'user_id' => $this->user_id,
                'amount' => (float) (0-$this->credit_total),
                'order_id' => $this->id
            ));
            $credit->__issue_to_user = true;
            $credit->save();
        }
        return $this;
    }
    
    /**
     * If this order has a credit_total and then gets cancelled, 
     * refund it to the customer's shop.credits.balance
     * by immediately issuing a positive credit
     *
     * @return \Shop\Models\Orders
     */
    public function refundCredit()
    {
        if ($this->credit_total) {
            $credit = (new \Shop\Models\Credits)->bind(array(
                'user_id' => $this->user_id,
                'amount' => (float) $this->credit_total,
                'order_id' => $this->id
            ));
            $credit->__issue_to_user = true;
            $credit->save();
        }
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
        // send gift certificate emails [optional]
        $this->fulfillGiftCards();

        $this->history[] = array(
            'created' => \Dsc\Mongo\Metastamp::getDate('now'),
            'verb' => 'fulfilled'
        );
        $this->history[] = array(
            'created' => \Dsc\Mongo\Metastamp::getDate('now'),
            'verb' => 'closed'
        );
        
        // Close the order and mark it as fulfilled
        $this->fulfillment_status = \Shop\Constants\OrderFulfillmentStatus::fulfilled;
        $this->status = \Shop\Constants\OrderStatus::closed;
        $this->save();
                
        // TODO send shipment notification emails [optional]
        
        // trigger the onShopFulfillOrder event
        $this->__fulfill_event = \Dsc\System::instance()->trigger( 'onShopFulfillOrder', array(
            'order' => $this
        ) );
        
        return $this;
    }
    
    /**
     *
     * @return \Shop\Models\Orders
     */
    public function open()
    {
        $this->history[] = array(
            'created' => \Dsc\Mongo\Metastamp::getDate('now'),
            'verb' => 'open'
        );
                
        $this->status = \Shop\Constants\OrderStatus::open;
        $this->save();
    
        // TODO send status update notification emails [optional]
    
        return $this;
    }
    
    /**
     * 
     * @return \Shop\Models\Orders
     */
    public function close()
    {
        $this->history[] = array(
            'created' => \Dsc\Mongo\Metastamp::getDate('now'),
            'verb' => 'closed'
        );
                
        $this->status = \Shop\Constants\OrderStatus::closed;
        $this->save();
    
        // TODO send status update notification emails [optional]
    
        return $this;
    }
    
    /**
     *
     * @return \Shop\Models\Orders
     */
    public function cancel()
    {
        $this->history[] = array(
            'created' => \Dsc\Mongo\Metastamp::getDate('now'),
            'verb' => 'cancelled'
        );
         
        // TODO Track this in f3-activity
        
        $this->status = \Shop\Constants\OrderStatus::cancelled;
        $this->save();
    
        // TODO send status update notification emails [optional]
        
        $this->refundCredit();
        
        $this->__cancel_event = \Dsc\System::instance()->trigger( 'onShopCancelOrder', array(
            'order' => $this
        ) );
    
        return $this;
    }
    
    /**
     * Did the customer purchase any gift cards in this order?  
     * if so, create a Models\OrderedGiftCard document and send the customer email an email for each one
     * but only if the gift card hasn't been fulfilled already
     *  
     * @return \Shop\Models\Orders
     */
    public function fulfillGiftCards()
    {
        $giftcards_fulfilled = array();
        
        foreach ($this->items as $key=>$item)
        {
            $product_type = \Dsc\ArrayHelper::get( $item, 'product.product_type' );
            switch ($product_type) 
            {
            	case "giftcard":
            	case "giftcards":
            	case "\\Shop\\Models\\GiftCards":
            	    if (\Dsc\ArrayHelper::get( $item, 'fulfillment_status' ) != \Shop\Constants\OrderFulfillmentStatus::fulfilled) 
            	    {
            	    	// Fulfill it and mark the order item as fulfilled
            	    	$orderedGiftCard = new \Shop\Models\OrderedGiftCards;
            	    	$orderedGiftCard->initial_value = \Dsc\ArrayHelper::get( $item, 'price' );
            	    	$orderedGiftCard->order_item = $item;
            	    	$orderedGiftCard->__email_recipient = $this->user_email;
            	    	if ($orderedGiftCard->save()) 
            	    	{
            	    	    $giftcards_fulfilled[] = $orderedGiftCard;
            	    	    $this->items[$key]['fulfillment_giftcard'] = $orderedGiftCard->id; 
            	    	    $this->items[$key]['fulfillment_status'] = \Shop\Constants\OrderFulfillmentStatus::fulfilled;
            	    	}
            	    }            	        
            	    break;
            	default:
            	    break;
            }
        }
        
        // if anything changed, save the order and note the changes
        if (!empty($giftcards_fulfilled)) 
        {
            $this->fulfillment_status = \Shop\Constants\OrderFulfillmentStatus::partial;
            
            $string = null;
            foreach ($giftcards_fulfilled as $giftcard_fulfilled) {
            	$string .= $giftcard_fulfilled->id . ', ';
            }
            
            // log this in the order's internal history
            $this->history[] = array(
                'created' => \Dsc\Mongo\Metastamp::getDate('now'),
                'verb' => 'fulfilled_giftcards',
                'object' => $string,
            );
             
            // TODO Track this in f3-activity
            
        	return $this->save();
        }
        
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