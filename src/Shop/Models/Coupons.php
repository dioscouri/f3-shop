<?php 
namespace Shop\Models;

class Coupons extends \Dsc\Mongo\Collections\Describable 
{
    use \Dsc\Traits\Models\Publishable;
	use \Dsc\Traits\Models\ForSelection;
        
    public $code = null;
    public $discount_value = null; 
    public $discount_type = null;
    public $discount_currency = null;
    public $discount_applied = null;
    public $discount_target_products = array();
    public $discount_target_shipping_methods = array();
    public $discount_target_collections = array();
    public $usage_max = null;
    public $usage_max_per_customer = null;
    public $usage_with_others = null;
    public $usage_automatic = null;
    public $max_value = null;
    public $max_value_currency = null;
    public $required_products = array();
    public $required_collections = array();
    public $required_coupons = array();
    public $min_subtotal_amount = null;
    public $min_subtotal_amount_currency = null;
    public $min_order_amount = null;
    public $min_order_amount_currency = null;
    public $geo_address_type = null;
    public $geo_countries = array();
    public $geo_regions = array();
    public $groups = array();
    public $groups_method = 'one';
    public $excluded_products = array();
    public $excluded_collections = array();
    public $generated_code = null;
    // counters
    public $total_sales = null;
        
    public $__is_validated = null;    
    protected $__collection_name = 'shop.coupons';
    protected $__type = 'shop.coupons';
    protected $__chars = array('c','n','u','m','r','s','e','w','h','b','k','f','z','v','x','p','j','t','q','y','g','a','d');
    
    protected function fetchConditions()
    {
        parent::fetchConditions();
        
        $this->publishableFetchConditions();
        
        $filter_keyword = $this->getState('filter.keyword');
        if ($filter_keyword && is_string($filter_keyword))
        {
            $key =  new \MongoRegex('/'. $filter_keyword .'/i');
        
            $where = array();
            $where[] = array('title'=>$key);
            $where[] = array('slug'=>$key);
            $where[] = array('description'=>$key);
            $where[] = array('code'=>$key);
            
            $this->setCondition('$or', $where);
        }
        
        
        $filter_code = $this->getState('filter.code');
        if (strlen($filter_code))
        {
            //$key = new \MongoRegex('/'.$filter_code.'/i');
            $key = strtolower( $filter_code );
            // add $and conditions to the query stack
            if (!$and = $this->getCondition('$and'))
            {
                $and = array();
            }
            
            $and[] = array(
                '$or' => array(
                    array(
                        'code' => $key
                    ),
                    array(
                        'codes.list.code' => $key
                    )
                )
            );
            
            $this->setCondition('$and', $and);
        }

        $filter_automatic = $this->getState('filter.automatic');
        if (strlen($filter_automatic))
        {
            $this->setCondition('usage_automatic', $filter_automatic);
        }
    }
    
    public function validate() 
    {
        // the lower-case version of the code must be unique
        if (empty($this->code)) 
        {
        	$this->setError('A code is required');
        }
        elseif (!empty($this->code) && $existing = $this->codeExists( $this->code )) 
        {
            if ((empty($this->id) || $this->id != $existing->id))
            {
                $this->setError('This code already exists');
            }
        }
        
    	return parent::validate();
    }
    
    public function codeExists( $code )
    {
        $code = strtolower($code);
        
        $clone = (new static)->load(array('code'=>$code));
    
        if (!empty($clone->id)) {
            return $clone;
        }
    
        return false;
    }
    
    protected function beforeSave()
    {
        // convert the code to lowercase
        $this->code = strtolower( $this->code );
        $this->discount_value = (float) $this->discount_value;
        
        if (!empty($this->discount_target_products) && !is_array($this->discount_target_products))
        {
            $this->discount_target_products = trim($this->discount_target_products);
            if (!empty($this->discount_target_products)) {
                $this->discount_target_products = \Base::instance()->split( (string) $this->discount_target_products );
            }
        }
        elseif(empty($this->discount_target_products) && !is_array($this->discount_target_products))
        {
            $this->discount_target_products = array();
        }
        
        if (!empty($this->discount_target_shipping_methods) && !is_array($this->discount_target_shipping_methods))
        {
            $this->discount_target_shipping_methods = trim($this->discount_target_shipping_methods);
            if (!empty($this->discount_target_shipping_methods)) {
                $this->discount_target_shipping_methods = \Base::instance()->split( (string) $this->discount_target_shipping_methods );
            }
        }
        elseif(empty($this->discount_target_shipping_methods) && !is_array($this->discount_target_shipping_methods))
        {
            $this->discount_target_shipping_methods = array();
        }
        
        if (!empty($this->required_products) && !is_array($this->required_products))
        {
            $this->required_products = trim($this->required_products);
            if (!empty($this->required_products)) {
                $this->required_products = \Base::instance()->split( (string) $this->required_products );
            }
        }
        elseif(empty($this->required_products) && !is_array($this->required_products))
        {
            $this->required_products = array();
        }
        
        if (!empty($this->required_coupons) && !is_array($this->required_coupons))
        {
            $this->required_coupons = trim($this->required_coupons);
            if (!empty($this->required_coupons)) {
                $this->required_coupons = \Base::instance()->split( (string) $this->required_coupons );
            }
        }
        elseif(empty($this->required_coupons) && !is_array($this->required_coupons))
        {
            $this->required_coupons = array();
        }
        
        if (!empty($this->geo_countries) && !is_array($this->geo_countries))
        {
            $this->geo_countries = trim($this->geo_countries);
            if (!empty($this->geo_countries)) {
                $this->geo_countries = \Base::instance()->split( (string) $this->geo_countries );
            }
        }
        elseif(empty($this->geo_countries) && !is_array($this->geo_countries))
        {
            $this->geo_countries = array();
        }
        
        if (!empty($this->geo_regions) && !is_array($this->geo_regions))
        {
            $this->geo_regions = trim($this->geo_regions);
            if (!empty($this->geo_regions)) {
                $this->geo_regions = explode(",", (string) $this->geo_regions );
            }
        }
        elseif(empty($this->geo_regions) && !is_array($this->geo_regions))
        {
            $this->geo_regions = array();
        }
        
        if (!empty($this->groups) && is_array($this->groups)) {
            $groups = array();
            $this->groups = array_values($this->groups);
            foreach ($this->groups as $group) 
            {
            	if (!empty($group)) 
            	{
            		$groups[] = $group;
            	}
            }
            $this->set('groups', $groups);
        }
        
        $this->publishableBeforeSave();
        $this->forSelectionBeforeValidate('required_collections');
        
        $this->forSelectionBeforeValidate('excluded_products');
        $this->forSelectionBeforeValidate('excluded_collections');
        
        $this->forSelectionBeforeValidate('discount_target_collections');
        
        return parent::beforeSave();
    }

    /**
     * Determines if this coupon is valid for a cart
     * 
     * @param \Shop\Models\Carts $cart
     * @throws \Exception
     */
    public function cartValid( \Shop\Models\Carts $cart )
    {
    	// Set $this->__is_validated = true if YES, cart can use this coupon
        // throw an Exception if NO, cart cannot use this coupon

        /**
         * is the coupon published?
         */
        if (!$this->published()) 
        {
            throw new \Exception('This coupon is expired.');
        }

        /**
         * Only 1 user-submitted coupon per cart,
         * and if the auto-coupon is exclusive, it can't be added with others
         */
        // If this is a user-submitted coupon && there are other user-submitted coupons in the cart, fail
        if (
            empty($this->usage_automatic) 
            && $cart->userCoupons()
            && $cart->userCoupons()[0]['code'] != $this->code // AND the userCoupon is different from this coupon
            ) 
        {
            throw new \Exception('Only one coupon allowed per cart');
        }
        
        // if this is an automatic coupon && usage_with_others == 0 && there are other automatic coupons in the cart
        if ($this->usage_automatic && empty($this->usage_with_others) && $cart->autoCoupons())
        {
            throw new \Exception('This coupon cannot be combined with others');
        }
        
        // TODO take min_subtotal_amount_currency into account once we have currencies sorted
        if (!empty($this->min_subtotal_amount) && $cart->subtotal() < $this->min_subtotal_amount)
        {
            throw new \Exception('Cart has not met the minimum required subtotal');
        }
        
        // TODO take min_order_amount_currency into account once we have currencies sorted
        $total = $cart->subtotal() - $cart->giftCardTotal() - $cart->discountTotal() - $cart->creditTotal();
        // Add back the value of this coupon in case it is already applied
        foreach ($cart->allCoupons() as $coupon) 
        {
        	if ((string) $coupon['_id'] == (string) $this->id) 
        	{
        	    $total = $total + $coupon['amount'];
        		break;
        	}
        }
        if (!empty($this->min_order_amount) && $total < $this->min_order_amount) 
        {
            throw new \Exception('Cart has not met the minimum required amount');
        }
        
        /**
         * check that at least one of the $this->required_products is in the cart
         */
        if (!empty($this->required_products)) 
        {
            // get the IDs of all products in this cart
            $product_ids = array();
            foreach ($cart->items as $cartitem) 
            {
                $product_ids[] = (string) \Dsc\ArrayHelper::get($cartitem, 'product_id'); 
            }
            
            $intersection = array_intersect($this->required_products, $product_ids);
            if (empty($intersection)) 
            {
                throw new \Exception('Coupon does not apply to any products in your cart.');
            }
        }
        
        /**
         * check that at least one of the $this->required_coupons is in the cart
         */
        if (!empty($this->required_coupons))
        {
            // get the IDs of all coupons in this cart
            $coupon_ids = array();
            foreach ($cart->userCoupons() as $coupon)
            {
                $coupon_ids[] = (string) $coupon['_id'];
            }
            foreach ($cart->autoCoupons() as $coupon)
            {
                $coupon_ids[] = (string) $coupon['_id'];
            }            
        
            $intersection = array_intersect($this->required_coupons, $coupon_ids);
            if (empty($intersection))
            {
                throw new \Exception('Cart does not have any of the required coupons');
            }
        }
        
        /**
         * check that at least one of the products from $this->required_collections is in the cart
         */
        if (!empty($this->required_collections))
        {
        	// get the IDs of all products in this cart
        	$product_ids = array();
        	foreach ($cart->items as $cartitem)
        	{
        		$product_ids[] = (string) \Dsc\ArrayHelper::get($cartitem, 'product_id');
        	}
        	
        	$found = false;
        	foreach( $this->required_collections as $collection_id ) 
        	{
        	    $collection_product_ids = \Shop\Models\Collections::productIds( $collection_id );
        	    $intersection = array_intersect($collection_product_ids, $product_ids);
        	    if (!empty($intersection))
        	    {
        	        $found = true;
        	        break; // if its found, break the foreach loop
        	    }
        	}
        	
        	if (!$found)
        	{
        		throw new \Exception('Coupon does not apply to any products in your cart.');
        	}
        }
        
        /**
         * evaluate shopper groups against $this->groups
         */
        if (!empty($this->groups)) 
        {
            $groups = array();
            $user = (new \Users\Models\Users)->setState('filter.id', $cart->user_id)->getItem();
            if (empty($cart->user_id) || empty($user->id)) 
            {
            	// Get the default group
                $group_id = \Shop\Models\Settings::fetch()->{'users.default_group'};
                if (!empty($group_id)) {
                    $groups[] = (new \Users\Models\Groups)->setState('filter.id', (string) $group_id)->getItem();
                }
            }                
        	elseif (!empty($user->id)) 
        	{
        	    $groups = $user->groups();
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
        	            throw new \Exception('Your order does not qualify for this discount.');
        	        }
        	                	        
        	        break;
        	    case "all":
        	        // $missing_groups == the ones from $this->groups that are NOT in $group_ids
        	        $missing_groups = array_diff($this->groups, $group_ids);
        	        if (empty($intersection))
        	        {
        	            throw new \Exception('Your order does not qualify for this discount.');
        	        }
        	        
        	        break;
        		case "one":
        		default:
        		    $intersection = array_intersect($this->groups, $group_ids);
        		    if (empty($intersection))
        		    {
        		        throw new \Exception('Your order does not qualify for this discount.');
        		    }
        		    
        		    break;
        	}        	
        }
        
        /**
         * using geo_address_type (shipping/billing) from the cart, check that it is in geo_countries | geo_regions (if either is set)
         */
        if (!empty($this->geo_countries) || !empty($this->geo_regions)) 
        {
        	// ok, so which of the addresses should we evaluate?
        	switch($this->geo_address_type) 
        	{
        		case "billing":
        		    $region = $cart->billingRegion();
        		    $country = $cart->billingCountry();
        		    break;
    		    case "shipping":
    		    default:
        		    $region = $cart->shippingRegion();
        		    $country = $cart->shippingCountry();
    		        break;
        	}
        	
        	if ((is_null($region) && !empty($this->geo_regions))
        	    ||
        	    (is_null($country) && !empty($this->geo_countries)))
        	{
        		throw new \Exception('Customer cannot use this coupon until we know your address');
        	}
        	
        	if (!empty($this->geo_countries))
        	{
        	    // eval the country
        	    if (!in_array($country, $this->geo_countries)) 
        	    {
        	        throw new \Exception('Shipping address is invalid');
        	    }
        	}
        	
        	if (!empty($this->geo_regions))
        	{
        	    // eval the region
        	    if (!in_array($region, $this->geo_regions))
        	    {
        	        throw new \Exception('Shipping address is invalid');
        	    }
        	}
        }
        
        /**
         * Check the usage of the coupon
         */
        if (strlen($this->usage_max)) {
        // usage_max = number of times TOTAL that the coupon may be used
            // count the orders with coupon.code 
            $total_count = (new \Shop\Models\Orders)->collection()->count(array(
            	'coupons.code' => $this->code
            ));
            
            if ((int) $this->usage_max <= (int) $total_count) {
                throw new \Exception('Coupon cannot be used any more');
            }
        }

        if (strlen($this->usage_max_per_customer)) {
        // usage_max_per_customer = number of times this customer may use this coupon
            // count the orders with coupon.code for user.id
            $user_count = (new \Shop\Models\Orders)->collection()->count(array(
                'coupons.code' => $this->code,
                'user_id' => $cart->user_id
            ));
            
            if ((int) $this->usage_max_per_customer <= (int) $user_count) {
                throw new \Exception('You cannot use this coupon any more');
            }
        }
        
        /**
         * Check, if this isn't generated code
         */
        if ( !empty($this->generated_code) )
        {
            $key = new \MongoRegex('/'.$this->generated_code.'/i');
            
        	$result = \Shop\Models\Coupons::collection()->aggregate(
        			array( '$match' => array( '_id' => new \MongoId( (string) $this->id )) ),
        			array( '$unwind' => '$codes.list' ),
        			array( '$match' => array( "codes.list.code" => $key ) ),
        			array( '$group' => array( '_id' => '$title', 'used_code' => array( '$sum' => '$codes.list.used' ) ) ) );
        	
        	if( count( $result['result'] ) ){
        		if( $result['result'][0]['used_code'] ){
        			throw new \Exception('You cannot use this coupon any more');
        		}
        	} else {
        		throw new \Exception('Coupon "'.$this->generated_code.'" is no longer available.');
        	}
        }
        
        /**
         * if we made it this far, the cart is valid for this coupon
         */
        $this->__is_validated = true;
        
        return $this;
    }
    
    /**
     * Calculates the value of this coupon against the data in a cart
     * 
     * @param \Shop\Models\Carts $cart
     * @return number
     */
    public function cartValue( \Shop\Models\Carts $cart ) 
    {
    	$value = 0;
    	
    	// check if the coupon has been validated against this cart
    	if ($this->__is_validated === null) 
    	{
    	    // throws an exception
    		$this->cartValid( $cart );
    	}
    	
    	// depending on where this coupon's discount is applied, get its corresponding value
    	switch ($this->discount_applied) 
    	{
    		case "order_subtotal":
    		    if ($this->discount_type == 'flat-rate') 
    		    {
    		        // TODO Take the discount_currency into account
    		    	$value = $this->discount_value;
    		    } 
    		    elseif ($this->discount_type == 'percentage') 
    		    {
    		        $value = ($this->discount_value/100) * $cart->subtotal();
    		    }
    		    
    		    // coupon value cannot be greater than order value
    		    if ($value > $cart->subtotal())
    		    {
    		        $value = $cart->subtotal();
    		    }
    		        		    
    		    break;
		    case "order_shipping":
		        
		        $cart_valid = true;
		        
		        // Is target_shipping method selected?
		        if (!empty($this->discount_target_shipping_methods))
		        {
		            $cart_valid = false;
		            if ($shipping_method = $cart->shippingMethod())
		            {
                        if (in_array($shipping_method->id, $this->discount_target_shipping_methods)) 
                        {
                            $cart_valid = true;
                        }
		            }
		        }
		        
		        if ($cart_valid) 
		        {
		            if ($this->discount_type == 'flat-rate')
		            {
		                // TODO Take the discount_currency into account
		                $value = $this->discount_value;
		            }
		            elseif ($this->discount_type == 'percentage')
		            {
		                $value = ($this->discount_value/100) * $cart->shippingTotal();
		            }
		            
		            // coupon value cannot be greater than order shipping cost
		            $current_shipping_discount_total = $cart->shippingDiscountTotal();
		            if ($value > ($cart->shippingTotal() - $current_shipping_discount_total))
		            {
		                $value = ($cart->shippingTotal() - $current_shipping_discount_total);
		            }
		        }
		        
		        break;
	        case "product_subtotal":

	            // if discount_target_products is empty, then for each product in the cart, apply the discount
	            if (empty($this->discount_target_products)) 
	            {
	                $excluded_products = $this->excludedProducts();
	                
	            	foreach ($cart->items as $cartitem) 
	            	{
	            	    if (!in_array((string) $cartitem['product_id'], $excluded_products)) 
	            	    {
	            	        if ($this->discount_type == 'flat-rate')
	            	        {
	            	            // TODO Take the discount_currency into account
	            	            $value += $this->discount_value;
	            	        }
	            	        elseif ($this->discount_type == 'percentage')
	            	        {
	            	            $value += ($this->discount_value/100) * $cart->calcItemSubtotal( $cartitem );
	            	        }
	            	    }	            	    
	            	}
	            }
	            // else, apply it only to the products in discount_target_products
	            else 
	            {
	                $excluded_products = $this->excludedProducts();
	                
	                foreach ($cart->items as $cartitem)
	                {
	                    // is this product a discount_target_product?
	                    if (in_array((string) $cartitem['product_id'], $this->discount_target_products )) 
	                    {
	                        if (!in_array((string) $cartitem['product_id'], $excluded_products))
	                        {
	                            if ($this->discount_type == 'flat-rate')
	                            {
	                                // TODO Take the discount_currency into account
	                                $value += $this->discount_value;
	                            }
	                            elseif ($this->discount_type == 'percentage')
	                            {
	                                $value += ($this->discount_value/100) * $cart->calcItemSubtotal( $cartitem );
	                            }
	                        }
	                    }
	                }
	            } 

	            // coupon value cannot be greater than order value
	            if ($value > $cart->subtotal())
	            {
	                $value = $cart->subtotal();
	            }
	            
	            break;
            case "product_shipping":

                $excluded_products = $this->excludedProducts();
                $excluded_products = array_merge( $excluded_products, $this->discount_target_products );
                
                // if discount_target_products is empty, then this is just the same thing as an order shipping discount
                if (empty($excluded_products))
                {
                    if ($this->discount_type == 'flat-rate')
                    {
                        // TODO Take the discount_currency into account
                        $value = $this->discount_value;
                    }
                    elseif ($this->discount_type == 'percentage')
                    {
                        $value = ($this->discount_value/100) * $cart->shippingTotal();
                    }
                }
                // else, apply it only to the products in discount_target_products
                else
                {
                    foreach ($cart->items as $cartitem)
                    {
                        // is this product a discount_target_product?
                        if (in_array((string) $cartitem['product_id'], $this->discount_target_products ))
                        {
                            if (!in_array((string) $cartitem['product_id'], $excluded_products))
                            {
                                if ($this->discount_type == 'flat-rate')
                                {
                                    // TODO Take the discount_currency into account
                                    $value += $this->discount_value;
                                }
                                elseif ($this->discount_type == 'percentage')
                                {
                                    // trigger an event with these arguments: (cartitem, selected shipping method, and cart)
                                    // and let the Listeners determine the shipping cost for that product
                                    $event = \Dsc\System::instance()->trigger( 'onFetchShippingRateForProduct', array(
                                        'cart' => $cart,
                                        'cartitem' => $cartitem,
                                        'method' => $cart->shippingMethod(),
                                        'rate' => 0
                                    ) );
                                    $rate = $event->getArgument('rate');
                                
                                    // since this is a percentage-based coupon, calculate its value based on the rate
                                    $value = ($this->discount_value/100) * $rate;
                                }
                            }                            
                        }
                    }
                }                
                
                // coupon value cannot be greater than order shipping cost
                $current_shipping_discount_total = $cart->shippingDiscountTotal();
                if ($value > ($cart->shippingTotal() - $current_shipping_discount_total))
                {
                    $value = ($cart->shippingTotal() - $current_shipping_discount_total);
                }
                break;
                
            case "product_price_override":
                
                // if discount_target_products and discount_target_collections is empty, 
                // then for each product in the cart, figure out how much the discount is.
                // the discount is the difference between the product's price and the coupon's discount_value
                if (empty($this->discount_target_products) && empty($this->discount_target_collections)) 
                {
                    $excluded_products = $this->excludedProducts();
                    foreach ($cart->items as $cartitem)
                    {
                        if (!in_array((string) $cartitem['product_id'], $excluded_products))
                        {
                            foreach ($cart->items as $cartitem)
                            {
                                $value += (($cartitem['price'] - $this->discount_value) * $cartitem['quantity']);
                            }
                        }
                    }
                }
                
                // otherwise, get the array of target_product_ids.  loop thru each product and if it is in the array,
                // figure out how much the discount is.
                // the discount is the difference between the product's price and the coupon's discount_value
                else 
                {
                    $discount_target_products = (array) $this->discount_target_products;
                    foreach( (array) $this->discount_target_collections as $collection_id )
                    {
                        $collection_product_ids = \Shop\Models\Collections::productIds( $collection_id );
                        $discount_target_products = array_merge($discount_target_products, $collection_product_ids);
                    }
                    $discount_target_products = array_unique( $discount_target_products );
                    $excluded_products = $this->excludedProducts();
                    
                    foreach ($cart->items as $cartitem)
                    {
                        if (in_array((string) $cartitem['product_id'], $discount_target_products ))
                        {
                            if (!in_array((string) $cartitem['product_id'], $excluded_products))
                            {
                                $value += (($cartitem['price'] - $this->discount_value) * $cartitem['quantity']);                            
                            }
                        }
                    }
                }
                
                break;
    	}
    	
    	if (strlen($this->max_value) && $value > $this->max_value) 
    	{
    	    $value = $this->max_value;
    	}
    	
    	return $value;
    }
    
    /**
     * Does this coupon give a shipping discount?
     * 
     * @return boolean
     */
    public function shipping()
    {
        return static::givesShippingDiscount( $this->cast() ); 
    }
    
    /**
     * Does the provided coupon data array give a shipping discount
     * 
     * @return boolean
     */
    public static function givesShippingDiscount( array $coupon )
    {
        $result = false;
        
        switch($coupon['discount_applied']) 
        {
            case "product_shipping":
            case "order_shipping":
                $result = true;
                break;
        }
        
        return $result;
    }
    
    /**
     * Helper method for creating select list options
     *
     * @param array $query
     * @return multitype:multitype:string NULL
     */
    public static function forSelection(array $query=array())
    {
        if (empty($this)) {
            $model = new static();
        } else {
            $model = clone $this;
        }
    
        $cursor = $model->collection()->find($query, array("title"=>1) );
        $cursor->sort(array(
            'title' => 1
        ));
    
        $result = array();
        foreach ($cursor as $doc) {
            $array = array(
                'id' => (string) $doc['_id'],
                'text' => htmlspecialchars( $doc['title'], ENT_QUOTES ),
            );
            $result[] = $array;
        }
    
        return $result;
    }

    /**
     * 
     * @return unknown
     */
    public function excludedProducts()
    {
        $excluded_products = (array) $this->excluded_products;
        foreach( $this->excluded_collections as $collection_id )
        {
            $collection_product_ids = \Shop\Models\Collections::productIds( $collection_id );
            $excluded_products = array_merge($excluded_products, $collection_product_ids);
        }
        $excluded_products = array_unique( $excluded_products );
        
        return $excluded_products;
    }
    
    public function countUsedCodes()
    {
		$result = \Shop\Models\Coupons::collection()->aggregate(
						array( '$match' => array( '_id' => new \MongoId( (string) $this->id )) ),
						array( '$unwind' => '$codes.list' ),
    					array( '$group' => array( '_id' => '$title', 'used_codes' => array( '$sum' => '$codes.list.used' ) ) ) );

		if( count( $result['result'] ) ){
			return $result['result'][0]['used_codes'];
		} else {
			return 0;
		}
    }
    
    public function generateCodes($prefix, $len, $num) 
    {
    	$prefix = strtolower($prefix);

    	$num_chars = count( $this->__chars );
    	$possible_codes = pow($num_chars, $len);
    	if( $possible_codes < $num ){
    		throw new \Exception("With length of ".$len.' you can generate only '.$possible_codes.'.' );
    	}
    	
    	$codes = array_values( (array)$this->{'codes.list'} );
    	
    	for( $i = 0; $i < $num; $i++ ){
    		$suffix = '';
			$notUnique = true;
    		while( $notUnique ){
    			for( $j = 0; $j < $len; $j++ ){
    				$suffix .= $this->__chars[rand( 0, $num_chars-1)];
    			}
    			
    			$all_codes = \Joomla\Utilities\ArrayHelper::getColumn( $codes, 'code' );
    			$notUnique = in_array( $prefix.$suffix, $all_codes );
    		}
    		
    		$codes []= array( 'code' => $prefix.$suffix, 'used' => 0 );
    	}
    	$this->{'codes.list'} = $codes;
    	$this->{'codes.prefix'} = $prefix;
    	$this->{'codes.length'} = $len;
    	$this->save();
    }
    
    /**
     * Gets the total sales using this coupon code
     *
     * @param string $refresh
     * @return number
     */
    public function totalSales($refresh=false)
    {
        if (empty($refresh))
        {
            return (float) $this->total_sales;
        }
    
        $this->total_sales = 0;
    
        $conditions = (new \Shop\Models\Orders)->setState('filter.coupon_id', $this->id)->setState('filter.financial_status', \Shop\Constants\OrderFinancialStatus::paid)->conditions();
    
        $agg = \Shop\Models\Orders::collection()->aggregate(array(
            array(
                '$match' => $conditions
            ),
            array(
                '$group' => array(
                	'_id' => '$coupons._id',
                    'total' => array( '$sum' => '$grand_total' )
                )
            )
        ));
    
        if (!empty($agg['ok']) && !empty($agg['result']))
        {
            $this->total_sales = (float) $agg['result'][0]['total'];
        }
    
        return (float) $this->total_sales;
    }
    
    /**
     * Calculates the total sales involving this coupon
     * during the specified time period
     *
     * @param string $refresh
     * @return number
     */
    public function fetchTotalSales($start=null, $end=null)
    {
        $model = (new \Shop\Models\Orders)
        ->setState('filter.coupon_id', $this->id)
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
                	'_id' => '$coupons._id',
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
     * Gets the count of sales using this coupon code
     *
     * @param string $refresh
     * @return number
     */
    public function countSales($refresh=false)
    {
        if (empty($refresh))
        {
            return (float) $this->count_sales;
        }
    
        $this->count_sales = 0;
    
        $conditions = (new \Shop\Models\Orders)->setState('filter.coupon_id', $this->id)->setState('filter.financial_status', \Shop\Constants\OrderFinancialStatus::paid)->conditions();
    
        $this->count_sales = \Shop\Models\Orders::collection()->count( $conditions );
        
        $this->save();
    
        return (float) $this->count_sales;
    }
    
    /**
     * Calculates the total sales involving this coupon
     * during the specified time period
     *
     * @param string $refresh
     * @return number
     */
    public function fetchCountSales($start=null, $end=null)
    {
        $model = (new \Shop\Models\Orders)
        ->setState('filter.coupon_id', $this->id)
        ->setState('filter.financial_status', \Shop\Constants\OrderFinancialStatus::paid);
    
        if (!empty($start)) {
            $model->setState('filter.created_after', $start);
        }
    
        if (!empty($end)) {
            $model->setState('filter.created_before', $end);
        }
    
        $conditions = $model->conditions();
    
        $total = \Shop\Models\Orders::collection()->count( $conditions );
    
        return (float) $total;
    }    
}