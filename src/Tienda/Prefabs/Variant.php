<?php 
namespace Tienda\Prefabs;

class Variant extends \Dsc\Prefabs
{
    /**
     * Default document structure
     * @var array
     */
    protected $document = array(
        'title'=>null,          // e.g. Alternative Title for the product when this variant has been selected 
        'attributes'=>array(),  // array of \Tienda\Prefabs\Attribute ids, sorted alphanumerically
        'quantity'=>null,
        'prices'=>array(),      // an array of \Tienda\Prefab\Price objects that are FINAL price overrides for this variant.  these are given priority over attribute price_changes.
        'model_number'=>null,
        'sku'=>null,
        'weight'=>null,
        'images'=>array(
            'primary'=>null,
            'gallery'=>array()
        )                
    );
}