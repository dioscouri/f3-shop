<?php
namespace Shop\Models;

class Regions extends \Dsc\Mongo\Collection
{
    public $name = null;
    public $code = null;
    public $country_isocode_2 = null;
    
    protected $__collection_name = 'shop.regions';
    protected $__config = array(
        'default_sort' => array(
            'name' => 1 
        ) 
    );
    
    public static function byCountry( $country_isocode_2 )
    {
        return \Shop\Models\Regions::find(array(
        	'country_isocode_2' => $country_isocode_2
        )); 
    } 
}