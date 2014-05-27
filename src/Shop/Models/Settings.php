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
    
    public $integration = array(
    		'kissmetrics' => array(
    				'enabled' => 0,
    				'key' => '',
    		),
    );
    
    public function enabledIntegration( $name ){
    	$result = false;
    
    	switch( $name ){
    		case 'kissmetrics' :
    			$result = $this->{'integration.kissmetrics.enabled'} && strlen( $this->{'integration.kissmetrics.key'} );
    			break;
    	}
    
    	return $result;
    }
}