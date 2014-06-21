<?php
namespace Shop\Models\Settings;

class ShippingMethods extends \Dsc\Mongo\Collections\Settings
{
    protected $__type = 'shop.settings.shippingmethods';
    
    public $ups = array();
    public $fedex = array();
    public $usps = array();
}