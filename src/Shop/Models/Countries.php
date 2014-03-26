<?php
namespace Shop\Models;

class Countries extends \Dsc\Mongo\Collection
{
    public $name = null;
    public $isocode_2 = null;
    public $isocode_3 = null;
    public $enabled = null;
    public $ordering = null;
    
    protected $__collection_name = 'shop.countries';
    protected $__config = array(
        'default_sort' => array(
            'name' => 1 
        ) 
    );
}