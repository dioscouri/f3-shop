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
    
    public $country = 'US';
}