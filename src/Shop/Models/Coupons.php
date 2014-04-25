<?php 
namespace Shop\Models;

class Coupons extends \Dsc\Mongo\Collections\Describable 
{
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
}