<?php
namespace Shop\Models;

class Settings extends \Dsc\Mongo\Collections\Settings
{
    protected $__type = 'shop.settings';
    
    public $home = array(
        'include_categories_widget' => 0 
    );
    
    public $shipping = array(
        'required' => 0 
    );
    
    public $orders = array(
        'printing' => array(
            'header' => null,
            'footer' => null,
        ),
        'email_html' => array(
            'header' => null,
            'footer' => null,
        ),        
        'email_text' => array(
            'header' => null,
            'footer' => null,
        ),        
    );
    
    public $order_confirmation = array(
        'gtm' => array(
    	   'enabled' => 0,
        ),        
    	'tracking_pixels' => array(
    	   'generic' => null
        ),
    );
    
    public $store_address = array(
        'line_1' => null,
        'line_2' => null,
        'city' => null,
        'region' => null,
    	'country' => 'US',
        'postal_code' => null,
        'phone_number' => null,
    );
    
    public $countries_sort = 'name';
    
    
    public function isShippingMethodEnabled($method=null)
    {
    	$result = false;
    	switch ($method)
    	{
    		case 'ups':
    			$result = $this->{'shipping.ups.enabled'} && $this->{'shipping.ups.key'} && $this->{'shipping.ups.password'};
    			break;
    		case 'usps':
    			$result = $this->{'shipping.usps.enabled'} && $this->{'shipping.usps.key'};
    			break;
    		case 'fedex':
    			$result = $this->{'shipping.fedex.enabled'};
    			break;
    		case 'stamps':
    			$result = $this->{'shipping.stamps.enabled'};
    			break;
    		case 'dhl':
    			$result = $this->{'shipping.dhl.enabled'};
    			break;
    		case null:
    			// are ANY of the social providers enabled?
    			$enabled = $this->enabledShippingMethods();
    			if (!empty($enabled)) {
    				$result = true;
    			}
    			break;
    		default:
    			$event = \Dsc\System::instance()->trigger('onShippingMethodEnabled', array('method' => $provider, 'result'=>null ));
    			$result = $event->getArgument('result');
    			break;
    	}
    
    	return $result;
    }
    
    
    /*
     * returns settings arrays for all enabled Methods
     * */
    public function enabledShippingMethods() {
    	
    	$enabledMethods = array();
    	foreach ((array) $this->{'shippingmethods'} as $methods => $method)
    	{
    		if ($this->isShippingMethodEnabled(strtolower($method['name'])))
    		{
    			$enabledMethods[] = $this->convertShippingMethod($method['name']);
    		}
    	}
    	return $enabledMethods;
    }
    
    protected function convertShippingMethod($name) {
    	
    	switch(strtolower($name)) {
    		case "ups":
    			return new \Shop\Shipping\Ups;
    		break;
    		case "usps":
    			return new \Shop\Shipping\Usps;
    		break;
    		case "fedex":
    			return new \Shop\Shipping\Fedex;
    		break;
    		case "stamps":
    			return new \Shop\Shipping\Fedex;
    		break;
    		case "dhl":
    				return new \Shop\Shipping\Dhl;
    		break;
    	}
    }
    
    /*
     * returns settings arrays for all enabled Methods
    * */
    public function enabledPaymentMethods() {
    	 
    	$enabledMethods = array();
    	foreach ((array) $this->{'paymentmethods'} as $methods => $method)
    	{
    		if ($this->isShippingMethodEnabled(strtolower($method['name'])))
    		{
    			$enabledMethods[] = $this->convertPaymentMethod($method['name']);
    		}
    	}
    	return $enabledMethods;
    }
    
    public function isPaymentMethodEnabled($method=null)
    {
    	$result = false;
    	switch ($method)
    	{
    		case "stripe":
    			break;
    		case "paypal":
    			break;
    		case "authorizenet":
    			break;
    		case "2checkout":
    			break;
    		case "coinbase":
    			break;
    	}
    
    	return $result;
    }
    
    protected function convertPaymentMethod($name) {
    	 
    	switch(strtolower($name)) {
    		case "stripe":
    			break;
    		case "paypal":
    			break;
    		case "authorizenet":
    			break;
    		case "2checkout":
    			break;
    		case "coinbase":
    			break;
    	}
    }
    
   
}