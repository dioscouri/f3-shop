<?php 
namespace Shop\Models;

class Coupons extends \Dsc\Mongo\Collections\Describable 
{
    protected $__is_validated = null;
    
    protected $__collection_name = 'shop.coupons';
    protected $__type = 'shop.coupons';
    
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
        // sets __is_validated to a boolean, so use === in comparing later
        //$this->__is_validated = true; // YES, cart can use this coupon
        //$this->__is_validated = false; // NO, cart cannot use this coupon

        // Do the actual validation!
        // lets do the simplest ones first.  save some processing power if they make it invalid
        
        // TODO is the coupon published?
        
        // TODO take min_order_amount_currency into account once we have currencies sorted
        if ($this->min_order_amount !== null && $cart->subtotal() < $this->min_order_amount) 
        {
            throw new \Exception('Cart has not met the minimum required amount');
        }
        
        // check that at least one of the $this->required_products is in the cart
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
                throw new \Exception('Cart does not have any of the required products');
            }
        }
         
        // evaluate shopper groups against $this->groups
        if (!empty($this->groups)) 
        {
            $user = (new \Users\Models\Users)->setState('filter.id', $cart->user_id)->getItem();
            if (empty($cart->user_id) || empty($user->id)) {
            	// TODO Get the default group
                $groups = array();
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
        	
        	$intersection = array_intersect($this->groups, $group_ids);
        	if (empty($intersection))
        	{
        	    throw new \Exception('Customer is not in one of the required user groups');
        	}
        }
        
        // TODO using geo_address_type (shipping/billing) from the cart, check that it is in geo_countries | geo_regions (if either is set)
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
        
        // TODO Check the usage of the coupon
        // usage_max = number of times TOTAL that the coupon may be used
        // usage_max_per_customer = number of times this customer may use this coupon
        // if usage_with_others == 1 && there are other coupons in the cart, fail
         
        
        // if we made it this far, the cart is valid for this coupon
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
    	
    	// TODO depending on where this coupon's discount is applied, get its corresponding value
    	switch ($this->discount_applied) 
    	{
    		case "order_subtotal":
    		    break;
		    case "order_shipping":
		        break;
	        case "product_subtotal":
	            break;
            case "product_shipping":
                break;
    	}
    	
    	// TODO remove this testing value
    	$value = 6.54;
    	
    	// if $value > $this->max_value, $value = $this->max_value
    	
    	return $value;
    }
}