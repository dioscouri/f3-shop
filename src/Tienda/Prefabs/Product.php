<?php 
namespace Tienda\Prefabs;

class Product extends \Dsc\Prefabs
{
    /**
     * Default document structure
     * @var array
     */
    protected $document = array(
        'sku'=>null, 
        'type'=>null, 
        'title'=>null, 
        'description'=>null, 
        'shipping'=>array(),
        'pricing'=>array(),
        'details'=>array()
    );
    
    protected $default_options = array(
        'append' => true
    );
}