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
    
    public $store_address = array(
        'line_1' => null,
        'line_2' => null,
        'city' => null,
        'region' => null,
    	'country' => 'US',
        'postal_code' => null,
        'phone_number' => null,
    );
}