<?php
namespace Shop\Models;

class Carts extends \Dsc\Mongo\Collections\Nodes
{
    public $user_id = null;
    public $user_email = null;      // could be a useful way of pre-creating carts for users.  alternatively, put this in the $this->checkout array
    public $session_id = null;
    public $items = array();        // array of \Shop\Models\Prefabs\CartItem objects
    
    public $taxes = array();        // array of \Shop\Models\Prefabs\TaxItems objects
    public $coupons = array();      // array of \Shop\Models\Prefabs\Coupon objects
    public $auto_coupons = array(); // array of \Shop\Models\Prefabs\Coupon objects
    public $discounts = array();    // array of \Shop\Models\Prefabs\Discount objects -- currently unused
    public $giftcards = array();    // array of \Shop\Models\OrderedGiftCards cast as arrays
    
    public $name = null;            // user-defined name for cart
    
    public $checkout = array(       // array of values used during checkout
        'order_comments' => null,
        'shipping_address' => array(),
        'billing_address' => array(),
        'shipping_method' => null,
    );
    
    public $shipping_methods = array();    // array of \Shop\Models\ShippingMethod objects, each with a rate
    
    protected $__collection_name = 'shop.carts';
    protected $__type = 'shop.carts';
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
     * Get the current user's cart, either based on session_id (visitor) or user_id (logged-in)
     * 
     * @return \Shop\Models\Carts
     */
    public static function fetch()
    {
        $identity = \Dsc\System::instance()->get('auth')->getIdentity();
        if (empty($identity->id))
        {
            $cart = static::fetchForSession();
        }
        else
        {
            $cart = static::fetchForUser();
        }
        
        return $cart;
    }
    
    /**
     * Get the current session's cart
     *
     * @return \Shop\Models\Carts
     */
    public static function fetchForSession()
    {
        $cart = new static;

        $session_id = \Dsc\System::instance()->get('session')->id();
    
        $cart->load(array('session_id' => $session_id));
        $cart->session_id = $session_id;
    
        return $cart;
    }
    
    /**
     * Get the current user's cart
     *
     * @return \Shop\Models\Carts
     */
    public static function fetchForUser()
    {
        $cart = new static;
    
        $identity = \Dsc\System::instance()->get('auth')->getIdentity();
        $session_id = \Dsc\System::instance()->get('session')->id();
        
        if (!empty($identity->id))
        {
            $cart->load(array('user_id' => new \MongoId( (string) $identity->id ) ));
            $cart->user_id = $identity->id;
            
            $session_cart = static::fetchForSession();
            
            // if there was no user cart but there IS a session cart, just add the user_id to the session cart and save it
            if (empty($cart->id) && !empty($session_cart->id))
            {
                $cart = $session_cart;
                $cart->user_id = $identity->id;
                $cart->save();
            }
            
            // if there was a user cart and there is a session cart, merge them and delete the session cart
            // if we already did the merge, skip this
            $session_cart_merged = \Dsc\System::instance()->get('session')->get('shop.session_cart_merged');
            if (!empty($session_cart->id) && $session_cart->id != $cart->id && empty($session_cart_merged))
            {
                $cart->session_id = $session_id;
                $cart->merge( $session_cart->cast() );
                $session_cart->remove();
                \Dsc\System::instance()->get('session')->set('shop.session_cart_merged', true);
            }
        }
    
        return $cart;
    }
    
    public static function createItem( $variant_id, \Shop\Models\Products $product, array $post=array() ) 
    {
        $options = !empty($post['options']) ? $post['options'] : array();
        $quantity = (!empty($post['quantity']) && $post['quantity'] > 0) ? (int) $post['quantity'] : 1;
        $price = $product->price( $variant_id );
        if (!$variant = $product->variant($variant_id)) {
            $variant = array();
        }
        
        $attribute_title = \Dsc\ArrayHelper::get( $variant, 'attribute_title' );
        $attribute_titles = \Dsc\ArrayHelper::get( $variant, 'attribute_titles' );
        $attributes = \Dsc\ArrayHelper::get( $variant, 'attributes' );
        $sku = \Dsc\ArrayHelper::get( $variant, 'sku' );
        $model_number = \Dsc\ArrayHelper::get( $variant, 'model_number' );
        $upc = \Dsc\ArrayHelper::get( $variant, 'upc' );
        $weight = \Dsc\ArrayHelper::get( $variant, 'weight' );
        $image = \Dsc\ArrayHelper::get( $variant, 'image' );
        
        $cartitem = new \Shop\Models\Prefabs\CartItem(array(
            'variant_id' => (string) $variant_id,
            'options' => (array) $options,
            'product' => $product->cast(),
            'product_id' => $product->id,
            'quantity' => $quantity,
            'price' => $price,
            // Set these based on the variant_id and the product
            'attribute_title' => !empty($attribute_title) ? $attribute_title : null,
            'attribute_titles' => !empty($attribute_titles) ? $attribute_titles : array(),
            'attributes' => !empty($attributes) ? $attributes : array(),
            'sku' => !empty($sku) ? $sku : $product->{'tracking.sku'},
            'model_number' => !empty($model_number) ? $model_number : $product->{'tracking.model_number'},
            'upc' => !empty($upc) ? $upc : $product->{'tracking.upc'},
            'weight' => !empty($weight) ? $weight : $product->{'shipping.weight'},
            'image' => !empty($image) ? $image : $product->{'featured_image.slug'}
        ));

        return $cartitem;
    }
    
    /**
     * Validates that the cartitem is configured correctly for adding to cart,
     * including quantity availability checks, quantity increment checks, etc
     * 
     * Throws an exception on fail, otherwise returns $this
     * 
     * @param \Shop\Models\Prefabs\CartItem $cartitem
     */
    public function validateAdd( \Shop\Models\Prefabs\CartItem $cartitem )
    {
        // does the product track inventory?
        if (\Dsc\ArrayHelper::get($cartitem->product, 'policies.track_inventory')) 
        {
            // is the quantity available?
            $quantity = \Shop\Models\Variants::quantity( $cartitem->variant_id );
            if ($cartitem->quantity > $quantity)
            {
                throw new \Exception( 'Quantity selected is unavailable' );
            }
        }
        
        // Fire an event so that any Listeners that want to stop validation
        // can throw an exception
        $event = \Dsc\System::instance()->trigger( 'onShopValidateAddToCart', array(
            'cart' => $this,
            'cartitem' => $cartitem
        ) );
        
        return $this;
    }

    /**
     * Adds an item to the cart
     * 
     * @param string $variant_id
     * @param \Shop\Models\Products $product
     * @param array $post
     */
    public function addItem( $variant_id, \Shop\Models\Products $product, array $post=array() )
    {
        $cartitem = static::createItem( $variant_id, $product, $post );

        $this->validateAdd( $cartitem );
        
        // Is the item already in the cart?
            // if so, inc quantity
            // otherwise add the cartitem
        $exists = false;
        foreach ($this->items as $key=>$item)
        {
            if ($item['hash'] == $cartitem->hash)
            {
                $exists = true;
                $cartitem->id = $item['id'];
                $cartitem->quantity = $cartitem->quantity + $item['quantity']; 
                $this->items[$key] = $cartitem->cast();
                
                break;
            }
        }
        
        if (!$exists) {
            $this->items[] = $cartitem->cast();
        }        

        $this->save();

        // Fire an event so that any Listeners that want to stop validation
        // can throw an exception
        $event = \Dsc\System::instance()->trigger( 'afterShopAddToCart', array(
            'cart' => $this,
            'cartitem' => $cartitem
        ) );
        
        return $this;
    }

    /**
     * 
     * @param unknown $cartitem_hash
     * @param unknown $new_quantity
     * @return \Shop\Models\Carts
     */
    public function updateItemQuantity( $cartitem_hash, $new_quantity )
    {
        $exists = false;
        foreach ($this->items as $key=>$item)
        {
            if ($item['hash'] == $cartitem_hash)
            {
                $exists = true;
                $this->items[$key]['quantity'] = $new_quantity;
                
                break;
            }
        }
        
        if ($exists) {
            $this->save();
        }        
        
        return $this;
    }

    /**
     * Removes an item from the cart
     * 
     * @param unknown $cartitem_hash
     * @return \Shop\Models\Carts
     */
    public function removeItem( $cartitem_hash )
    {
        $exists = false;
        foreach ($this->items as $key=>$item)
        {
            if ($item['hash'] == $cartitem_hash)
            {
                $exists = true;
                unset($this->items[$key]);
                
                break;
            }
        }
        
        if ($exists) {
            $this->save();
        }        
        
        return $this;
    }

    /**
     * Merges the data array into $this
     * giving quantities in the data array priority over quantities in $this
     *
     * @param unknown $data            
     */
    public function merge( $data )
    {
        $save = false;
        
        if (!empty($data['items'])) 
        {
            $save = true;
            
            foreach ($data['items'] as $data_key=>$data_item)
            {
            	// does it exist in $this?  if so, merge
                $exists = false;
                foreach ($this->items as $key=>$item)
                {
                    if ($item['hash'] == $data_item['hash'])
                    {
                        $exists = true;
                        $data_item['id'] = $item['id'];
                        $this->items[$key] = $data_item;
                        break;
                    }
                }
                
                // otherwise add it
                if (!$exists) {
                    $this->items[] = $data_item;
                }
            }
        }
        
        if (!empty($data['coupons'])) 
        {
            $save = true;
            
        	$this->coupons = $data['coupons'];
        }
        
        if (!empty($data['giftcards']))
        {
            $save = true;
        
            $this->giftcards = $data['giftcards'];
        }
        
        if (!empty($data['discounts']))
        {
            $save = true;
        
            $this->discounts = $data['discounts'];
        }
        
        if ($save) {
            $this->save();
        }
        
        return $this;
    }
    
    /**
     * A diagnostic method. Updates/Removes products from stored carts.
     * Should be run each time a cart is viewed or when a checkout begins.
     * Checks that products are still available,
     * that the product definition in the cart is up to date,
     * etc. 
     * 
     * @return Array of messages from actions taken
     */
    public function validateProducts()
    {
        $return = array();
        
        if (empty($this->items)) {
            return $return;
        }
        
        $change = false;
        foreach ($this->items as $key=>$cartitem)
        {
            $variant_id = \Dsc\ArrayHelper::get($cartitem, 'variant_id');
            
            try {
                $product = (new \Shop\Models\Variants)->getById($variant_id);
            } catch (\Exception $e) {
                // remove item from cart and add message to $return
                $title = \Dsc\ArrayHelper::get($cartitem, 'product.title');
                $return[] = 'The item "'. $title .'" is invalid and has been removed from your cart.';
                unset($this->items[$key]);
                $change = true;
                continue;
            }

            // TODO If the product is not available, remove item from cart and add message to $return
            
            // update the cart's stored product definition
            $cast = $product->cast();
            if ($this->items[$key]['product'] != $cast) 
            {
                $change = true;
                $this->items[$key]['product'] = $cast;
            }
            
            // TODO Has the price changed?  If so, update the cart and add message to $return
            
        }
        
        if ($change) {
        	$this->save();
        }        
        
        return $return;
    }
    
    /**
     * Get a cart item using its hash
     *
     * @param unknown $id
     */
    public function fetchItemByHash($hash)
    {
        if (empty($this->items)) {
            return false;
        }
    
        foreach ($this->items as $item)
        {
            if ($item['hash'] == $hash) {
                return $item;
            }
        }
    
        return false;
    }
    
    /**
     * Given a cartitem, calculates the subtotal
     * 
     * @param unknown $item
     */
    public static function calcItemSubtotal( $data )
    {
        $subtotal = $data['quantity'] * $data['price'];
        return $subtotal;
    }
    
    /**
     * Does this cart require payment?
     *
     * @return boolean
     */
    public function paymentRequired()
    {
        $payment_required = true;
        
        if (empty($this->total())) 
        {
            $payment_required = false;
        }
        
        return $payment_required;
    }
    
    /**
     * Does this cart require shipping?
     * 
     * 0    = return if shipping is not required for this cart and global setting = no
     * 1    = return if global setting = yes but the cart itself does not require shipping
     * true = return if cart itself requires shipping
     *
     * @return boolean
     */
    public function shippingRequired()
    {
        $shipping_required = (int) \Shop\Models\Settings::fetch()->{'shipping.required'};
    
        if (empty($this->items)) {
            return $shipping_required;
        }
    
        foreach ($this->items as $item)
        {
            if (\Dsc\ArrayHelper::get($item, 'product.shipping.enabled')) 
            {
                $shipping_required = true;
            }
        }
    
        return $shipping_required;
    }
    
    /**
     * Gets the total weight of all items in the cart
     * 
     */
    public function weight()
    {
        $weight = 0;
        
        if (empty($this->items)) {
            return $weight;
        }
        
        foreach ($this->items as $item)
        {
            $weight += $item['weight'];
        }
        
        return $weight;
    }
    
    /**
     * Gets the total number of items in the cart
     */
    public function quantity()
    {
        $quantity = 0;
        
        if (empty($this->items)) {
            return $quantity;
        }
        
        foreach ($this->items as $item)
        {
            $quantity += (int) $item['quantity'];
        }
        
        return $quantity;
    }
    
    /**
     * Gets the subtotal
     * 
     * @return float
     */
    public function subtotal()
    {
        $subtotal = 0;
        
        if (empty($this->items)) {
            return $subtotal;
        }
        
        foreach ($this->items as $item)
        {
            $subtotal += static::calcItemSubtotal( $item );
        }
        
        return (float) $subtotal;
    }
    
    /**
     * Gets the total shipping surcharge to be applied to each shipping method.
     * Shipping surcharges can be either global (set in the Shop config)
     * or per item (set in each Product) 
     */
    public function shippingSurchargeTotal()
    {
        // TODO fire a plugin event here, sending $this as an argument
        
        $total = 0;
        
        if (empty($this->items)) {
            return $total;
        }
        
        // Get the surcharge amount for each product
        foreach ($this->items as $item)
        {
            if ($product_surchage = \Dsc\ArrayHelper::get($item, 'product.shipping.surcharge')) {
                $total += $product_surchage;
            }
        }
        
        return (float) $total;        
    }
    
    /**
     * 
     * @return number
     */
    public function shippingEstimate()
    {
        $estimate = 0;
        return (float) $estimate;
    }
    
    /**
     * 
     * @return number
     */
    public function taxEstimate()
    {
        $estimate = 0;
        return (float) $estimate;
    }
    
    /**
     * Gets the total,
     * incl. subtotal, discounts/coupons, gift certificates, 
     * shipping estimate (if possible) and tax (if possible).
     * 
     * @return number
     */
    public function total()
    {
        $total = $this->subtotal();
        
        if ($shippingMethod = $this->shippingMethod()) 
        {
            $total = $total + $shippingMethod->total();
            $total = $total + $this->taxTotal();
        } 
        else 
        {
            $total = $total + $this->shippingEstimate();
            $total = $total + $this->taxEstimate();
        }
        
        $total = $total - $this->discountTotal() - $this->giftCardTotal();
    
        return (float) $total;
    }
    
    /**
     *
     * @return number
     */
    public function shippingTotal()
    {
        $total = 0;
        
        if ($shippingMethod = $this->shippingMethod()) 
        {
        	$total = $shippingMethod->total();
        }
        
        return (float) $total;
    }
    
    /**
     *
     * @return number
     */
    public function taxTotal()
    {
        $total = 0;
        
        // loop through the $this->getTaxes line items and sum the totals
        foreach ($this->taxItems() as $taxItem) 
        {
        	if (!empty($taxItem['total'])) 
        	{
        		$total = $total + $taxItem['total'];
        	}
        }
        
        return (float) $total;
    }
    
    /**
     * Gets the total of all the applied discounts
     *
     * @return number
     */
    public function discountTotal()
    {
        $discount = 0;
        
        $discount = $this->userDiscountTotal();
        
        $discount = $discount + $this->autoDiscountTotal();
        
        return (float) $discount;
    }
    
    /**
     * Gets the total of all automatically-applied discounts,
     * optionally excluding the discounts that apply to shipping 
     * 
     * @return number
     */
    public function autoDiscountTotal($exclude_shipping=false)
    {
        $discount = 0;
        
        foreach ($this->auto_coupons as $coupon)
        {
            if ($exclude_shipping && \Shop\Models\Coupons::givesShippingDiscount( $coupon ))
            {
                continue;
            }            
            $discount = $discount + $coupon['amount'];
        }
        
        return (float) $discount;
    }
    
    /**
     * Returns the total of all the automatically-applied shipping discounts
     * 
     * @return number
     */
    public function autoShippingDiscountTotal()
    {
        $discount = 0;

        foreach ($this->auto_coupons as $coupon)
        {
            if (\Shop\Models\Coupons::givesShippingDiscount( $coupon ))
            {
                $discount = $discount + $coupon['amount'];
            }
        }
            
        return (float) $discount;
    }
    
    /**
     * Gets the total of all user-applied discounts,
     * optionally excluding the discounts that apply to shipping
     * 
     * @return number
     */
    public function userDiscountTotal($exclude_shipping=false)
    {
        $discount = 0;
    
        foreach ($this->coupons as $coupon)
        {
            if ($exclude_shipping && \Shop\Models\Coupons::givesShippingDiscount( $coupon )) 
            {
                continue;            	
            }
            $discount = $discount + $coupon['amount'];
        }
    
        return (float) $discount;
    }
    
    /**
     * Gets the total of all user-applied shipping discounts
     * 
     * @return number
     */
    public function userShippingDiscountTotal()
    {
        $discount = 0;
    
        foreach ($this->coupons as $coupon)
        {
            if (\Shop\Models\Coupons::givesShippingDiscount( $coupon )) 
            {
                $discount = $discount + $coupon['amount'];
            }            
        }
    
        return (float) $discount;
    }
    
    /**
     * Gets the total of all the applied gift cards
     *
     * @return number
     */
    public function giftCardTotal()
    {
        $total = 0;
    
        foreach ($this->giftcards as $item)
        {
            $total = $total + $item['amount'];
        }
    
        return (float) $total;
    }
    
    /**
     * Gets the total of all the applied credits
     *
     * @return number
     */
    public function creditTotal()
    {
        $credit = 0;
        return (float) $credit;
    }

    /**
     * Gets the total amount that tax can be applied to
     * 
     * @return number
     */
    public function taxableTotal()
    {
        $total = 0;
        
        /*
        foreach ($this->items as $item)
        {
            // is the item taxable?
            if ($taxable = \Dsc\ArrayHelper::get( $item, 'product.taxes.enabled' ))
            {
                $total = $total + \Dsc\ArrayHelper::get( $item, 'price' );
            }
        }        
        */
        
        $total = $total + $this->subtotal();
        
        if ($shippingMethod = $this->shippingMethod())
        {
            $total = $total + $shippingMethod->total();
        }
        
        $total = $total - $this->discountTotal();
        
        return (float) $total;
    }
    
    /**
     * Gets valid shipping methods for this cart,
     * fetching them from Listeners if requested or necessary
     * 
     * @return array
     */
    public function shippingMethods( $refresh=false )
    {
        if (empty($this->{'checkout.shipping_address.country'}) || empty($this->{'checkout.shipping_address.region'}) || empty($this->{'checkout.shipping_address.postal_code'}) )
        {
            $this->{'checkout.shipping_method'} = null;
            $this->shipping_methods = array();
            $this->save();
        }
        
        elseif (empty($this->shipping_methods) || $refresh) 
        {
            $this->shipping_methods = $this->fetchShippingMethods();
            $this->save();
        }
        
        return $this->shipping_methods;
    }
    
    /**
     * Return false if not set in checkout.shipping_method
     * Return null if set but not found in array of valid shipping methods for this cart
     * Return \Shop\Mopdels\ShippingMethods object if found
     *  
     */
    public function shippingMethod()
    {
        // is it not set in checkout?
        if (!$this->{'checkout.shipping_method'}) 
        {
        	return false;
        }
        
        // otherwise get its full object from the array of methods
        foreach ($this->shippingMethods() as $method_array) 
        {
            if ($this->{'checkout.shipping_method'} == \Dsc\ArrayHelper::get( $method_array, 'id' )) 
            {
                $method = new \Shop\Models\ShippingMethods( $method_array );
            	return $method;
            }
        }
        
        return null;
    }
    
    /**
     * Fetches valid shipping methods for this cart
     * 
     */
    protected function fetchShippingMethods()
    {
        $methods = array(); // TODO Set this to an array of the enabled core shipping methods 

        $event = \Dsc\System::instance()->trigger( 'onFetchShippingMethodsForCart', array(
            'cart' => $this,
            'methods' => $methods
        ) );
        
        return $event->getArgument('methods');
    }
    
    /**
     * Gets valid payment methods for this cart,
     * fetching them from Listeners if requested or necessary
     *
     * @return array
     */
    public function paymentMethods( $refresh=false )
    {
        if (empty($this->payment_methods) || $refresh)
        {
            $this->payment_methods = $this->fetchPaymentMethods();
            $this->save();
        }
    
        return $this->payment_methods;
    }
    
    /**
     * Return false if not set in checkout.payment_method
     * Return null if set but not found in array of valid payment methods for this cart
     * Return \Shop\Mopdels\Prefabs\PaymentMethods object if found
     *
     */
    public function paymentMethod()
    {
        // is it not set in checkout?
        if (!$this->{'checkout.payment_method'})
        {
            return false;
        }
    
        // otherwise get its full object from the array of methods
        foreach ($this->paymentMethods() as $method_array)
        {
            if ($this->{'checkout.payment_method'} == \Dsc\ArrayHelper::get( $method_array, 'id' ))
            {
                $method = new \Shop\Models\Prefabs\PaymentMethods( $method_array );
                return $method;
            }
        }
    
        return null;
    }
    
    /**
     * Fetches valid payment methods for this cart
     *
     */
    protected function fetchPaymentMethods()
    {
        $methods = array(); // TODO Set this to an array of the enabled core payment methods 
    
        $event = \Dsc\System::instance()->trigger( 'onFetchPaymentMethodsForCart', array(
            'cart' => $this,
            'methods' => $methods
        ) );
    
        return $event->getArgument('methods');
    }
    
    /**
     * Gets tax line items for this cart,
     * fetching them from Listeners if requested or necessary
     *
     * @return array
     */
    public function taxItems( $refresh=false )
    {
        if (empty($this->taxes) || $refresh)
        {
            $this->taxes = $this->fetchTaxItems();
            $this->save();
        }
    
        return $this->taxes;
    }
    
    /**
     * Fetches tax line items for this cart
     *
     */
    protected function fetchTaxItems()
    {
        $taxes = array(); // TODO Set this to an array of tax items decided upon by the core?
        
        $event = \Dsc\System::instance()->trigger( 'onFetchTaxItemsForCart', array(
            'cart' => $this,
            'taxes' => $taxes
        ) );
    
        return $event->getArgument('taxes');
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
        
        return parent::beforeValidate();
    }
    
    /**
     * Compare the items and shipping from the previously-saved cart.  
     * If they've changed, clear the tax calculations. 
     */
    protected function beforeSave()
    {
        if (!empty($this->id)) 
        {
            // If a cart is updated, recalculate coupon values and tax value
            $cart = (new static)->load(array('_id' => new \MongoId( (string) $this->id ) ));
            
            // Compare items, coupons, shipping address, and shipping method.
            // If changed, empty the taxes
            // and update coupon & giftcard values
            if ($cart->items != $this->items
                || $cart->coupons != $this->coupons
                || $cart->auto_coupons != $this->auto_coupons
                || $cart->giftcards != $this->giftcards
                || $cart->shippingMethod() != $this->shippingMethod()
                || $cart->{'checkout.shipping_address'} != $this->{'checkout.shipping_address'}
                || $cart->{'checkout.billing_address'} != $this->{'checkout.billing_address'}
            )
            {
                $this->taxes = array();
                $this->ensureAutoCoupons();
                foreach ((array) $this->coupons as $key=>$item)
                {
                    if (!empty($item['usage_automatic'])) {
                        unset($this->coupons[$key]);
                    }
                }
                $this->coupons = array_values(array_filter($this->coupons));
                foreach ((array) $this->coupons as $key=>$item)
                {
                    $this->{'coupons.' . $key . '.amount'} = $this->calcCouponValue( $item );
                }
            }
        }
        
        // if there is a user_id, delete the session_id
        if (!empty($this->user_id)) 
        {
        	$this->session_id = null;
        }
        
        return parent::beforeSave();
    }
    
    /**
     * Converts a cart to an Order
     * 
     */
    public function convertToOrder()
    {
        return \Shop\Models\Orders::fromCart( $this );
    }
    
    /**
     * Validates that the provided shipping address can be used for determining a shipping method
     * 
     * @return boolean
     */
    public function validShippingAddress()
    {
        if (!$this->{'checkout.shipping_address.country'}
            || !$this->{'checkout.shipping_address.region'}
            || !$this->{'checkout.shipping_address.postal_code'}
        ) {
            return false;
        }
        
        return true;
    }
    
    /**
     *
     */
    public function shippingAddress()
    {
        return $this->{'checkout.shipping_address'};
    }
    
    /**
     * Gets the shipping region
     */
    public function shippingRegion( $default=null )
    {
        if ($this->{'checkout.shipping_address.region'}) {
            return $this->{'checkout.shipping_address.region'};
        }
    
        return $default;
    }
    
    /**
     * Gets the shipping country, default one if not set
     */
    public function shippingCountry()
    {
        if ($this->{'checkout.shipping_address.country'}) {
            return $this->{'checkout.shipping_address.country'};
        }
    
        return \Shop\Models\Settings::fetch()->{'store_address.country'};
    }
    
    /**
     *
     */
    public function billingSameAsShipping()
    {
        if (!isset($this->checkout['billing_address']['same_as_shipping'])
        || !empty($this->{'checkout.billing_address.same_as_shipping'})
        ) {
            return true;
        }
    
        return false;
    }
    
    /**
     * 
     */
    public function billingAddress()
    {
        return $this->{'checkout.billing_address'};
    }
        
    /**
     * 
     * @param string $default
     * @return string
     */
    public function billingName( $default=null ) 
    {
        if ($this->{'checkout.billing_address.name'}) {
            return $this->{'checkout.billing_address.name'};
        }
        
        return $default;
    }
    
    /**
     *
     * @param string $default
     * @return string
     */
    public function billingLine1( $default=null )
    {
        if ($this->{'checkout.billing_address.line_1'}) {
            return $this->{'checkout.billing_address.line_1'};
        }
    
        return $default;
    }
    
    /**
     *
     * @param string $default
     * @return string
     */
    public function billingLine2( $default=null )
    {
        if ($this->{'checkout.billing_address.line_2'}) {
            return $this->{'checkout.billing_address.line_2'};
        }
    
        return $default;
    }
    
    /**
     *
     * @param string $default
     * @return string
     */
    public function billingCity( $default=null )
    {
        if ($this->{'checkout.billing_address.city'}) {
            return $this->{'checkout.billing_address.city'};
        }
    
        return $default;
    }
    
    /**
     *
     * @param string $default
     * @return string
     */
    public function billingRegion( $default=null )
    {
        if ($this->{'checkout.billing_address.region'}) {
            return $this->{'checkout.billing_address.region'};
        }
    
        return $default;
    }
    
    /**
     *
     * @param string $default
     * @return string
     */
    public function billingPostalCode( $default=null )
    {
        if ($this->{'checkout.billing_address.postal_code'}) {
            return $this->{'checkout.billing_address.postal_code'};
        }
    
        return $default;
    }
    
    /**
     *
     * @param string $default
     * @return string
     */
    public function billingPhone( $default=null )
    {
        if ($this->{'checkout.billing_address.phone_number'}) {
            return $this->{'checkout.billing_address.phone_number'};
        }
    
        return $default;
    }
    
    /**
     * Gets the billing country, default one if not set
     *
     * @param string $default
     * @return string
     */
    public function billingCountry( $default=null )
    {
        if ($this->{'checkout.billing_address.country'}) {
            return $this->{'checkout.billing_address.country'};
        }
    
        return $default ? $default : \Shop\Models\Settings::fetch()->{'country'};
    }
    

    /**
     * Adds a coupon code to the cart,
     * performing validations first
     *
     * @param \Shop\Models\Coupons $coupon
     * @return \Shop\Models\Carts
     */
    public function addCoupon( \Shop\Models\Coupons $coupon )
    {
        // validate that the coupon can be added to this cart
        $coupon->cartValid( $this );
        
        // TODO push it into the coupons stack.  automatic vs user coupons can be parsed on the usage_automatic field
        $exists = false;
        foreach ((array) $this->coupons as $key=>$item)
        {
            if ($item['code'] == $coupon->code)
            {
                $exists = true;
                break;
            }
        }
    
        if (!$exists) 
        {
            $cast = $coupon->cast();
            $cast['amount'] = $coupon->cartValue( $this );
            $this->coupons[] = $cast;
        }

        return $this->save();
    }
    
    /**
     * Removes a coupon from the cart
     *
     * @param string $code
     * @return \Shop\Models\Carts
     */
    public function removeCoupon( $code )
    {
        $exists = false;
        foreach ($this->coupons as $key=>$item)
        {
            if ($item['code'] == $code)
            {
                $exists = true;
                unset($this->coupons[$key]);
    
                break;
            }
        }
    
        if ($exists) {
            $this->save();
        }
    
        return $this;
    }
    
    /**
     * Calculates the value of a coupon against the data in this cart.
     * Is a wrapper for the same method in Models\Coupons to ease getting the value from
     * a stored cart 
     * 
     * @return number
     */
    public function calcCouponValue( $coupon )
    {
        $value = 0;
        
        if (is_object($coupon) && is_a($coupon, '\Shop\Models\Coupons') ) 
        {
        	// do nothing
        }
        elseif (is_array($coupon) || is_object($coupon)) 
        {
        	$coupon = new \Shop\Models\Coupons( $coupon );
        } 
        else 
        {
        	throw new \Exception('Cannot calculate value of invalid coupon');
        }
        
        // IMPORTANT: we only want the value -- we don't want to revalidate
        $coupon->__is_validated = true;        
        $value = $coupon->cartValue( $this );
        
        return $value;
    }
    
    /**
     * Gets the user-submitted coupons from the cart
     * 
     * @return multitype:
     */
    public function userCoupons($shipping=null)
    {
        if (empty($this->coupons)) 
        {
        	return array();
        }

        $coupons = array();
        foreach ($this->coupons as $coupon)
        {
            $gives_shipping_discount = \Shop\Models\Coupons::givesShippingDiscount( $coupon );
            if ($shipping === false)
            {
                // only the non-shipping coupons
                if ($gives_shipping_discount) {
                    continue;
                } else {
                    $coupons[] = $coupon;
                }                
            }
            
            elseif ($shipping === true)
            {
                // only the shipping ones
                if ($gives_shipping_discount) {
                    $coupons[] = $coupon;
                }                
            }
            
            else 
            {
                // $shipping = null, so give me all of the user coupons 
                $coupons[] = $coupon;
            }
        }
        
        return $coupons;
    }
    
    /**
     * Gets the automatic coupons from the cart
     *
     * @return multitype:
     */
    public function autoCoupons($shipping=null)
    {
        if (empty($this->auto_coupons))
        {
            return array();
        }
        
        $coupons = array();
        foreach ($this->auto_coupons as $coupon)
        {
            $gives_shipping_discount = \Shop\Models\Coupons::givesShippingDiscount( $coupon );
            if ($shipping === false)
            {
                // only the non-shipping coupons
                if ($gives_shipping_discount) {
                    continue;
                } else {
                    $coupons[] = $coupon;
                }
            }
        
            elseif ($shipping === true)
            {
                // only the shipping ones
                if ($gives_shipping_discount) {
                    $coupons[] = $coupon;
                }
            }
        
            else
            {
                // $shipping = null, so give me all of the user coupons
                $coupons[] = $coupon;
            }
        }
        
        return $coupons;
    }
    
    /**
     * Determines which auto coupons should be part of this cart
     * then ensures that those are the only auto coupons currently in the cart
     * 
     * @return \Shop\Models\Carts
     */
    public function ensureAutoCoupons()
    {
        // get all potential auto-coupons (published auto coupons)
        // check each one against $this cart for validity
        
        $valid_auto_coupons = array();
        
        $coupons = (new \Shop\Models\Coupons)
            ->setState('filter.publication_status', 'published')
            ->setState('filter.published_today', true)
            ->setState('filter.automatic', '1')
            ->getItems();
        
        foreach ($coupons as $coupon) 
        {
            // if the coupon is valid for this cart, ensure it is added
            // otherwise, ensure it is removed
            try {
            	$coupon->cartValid( $this );
            	$valid_auto_coupons[$coupon->code] = $coupon;
            }
            catch (\Exception $e) {
            	// REMOVE IT
            }
        }

        $this->auto_coupons = array();        
        foreach ($valid_auto_coupons as $valid_auto_coupon) 
        {
            $cast = $valid_auto_coupon->cast();
            $cast['amount'] = $this->calcCouponValue( $valid_auto_coupon );
            $this->auto_coupons[] = $cast;        	
        }
        
        $this->auto_coupons = array_values($this->auto_coupons);
        
        return $this;
    }
    
    /**
     * Adds a gift card to the cart,
     * performing validations first
     *
     * @param \Shop\Models\OrderedGiftCards $giftcard
     * @return \Shop\Models\Carts
     */
    public function addGiftCard( \Shop\Models\OrderedGiftCards $giftcard )
    {
        $giftcard->cartValid( $this );
    
        $exists = false;
        foreach ((array) $this->giftcards as $key=>$item)
        {
            if ($item['code'] == $giftcard->code)
            {
                $exists = true;
                break;
            }
        }
    
        if (!$exists)
        {
            $cast = $giftcard->cast();
            $cast['amount'] = $giftcard->cartValue( $this );
            $this->giftcards[] = $cast;
        }
    
        return $this->save();
    }
    

    /**
     * Removes a gift card from the cart
     *
     * @param string $code
     * @return \Shop\Models\Carts
     */
    public function removeGiftCard( $code )
    {
        $exists = false;
        foreach ($this->giftcards as $key=>$item)
        {
            if ($item['_id'] == $code)
            {
                $exists = true;
                unset($this->giftcards[$key]);
    
                break;
            }
        }
    
        if ($exists) {
            $this->save();
        }
    
        return $this;
    }
}