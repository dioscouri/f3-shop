<?php 
namespace Tienda\Prefabs;

class Attribute extends \Dsc\Prefabs
{
    /**
     * Default document structure
     * @var array
     */
    protected $document = array(
        'id'=>null,          // unique id for this attribute, distinguishing it from all other attributes
        'title'=>null,          // e.g. "Sleeve Length" 
        'ordering'=>0,          // allows you to put "Sleeve Length" after "Color" on the product detail page
        'options'=>array(
            'title'=>null,                  // e.g. "Short", "Long", "Three-Quarter"
            'price_change'=>null,           // inc, dec, new, null == this option will either increase, decrease, overwrite, or have no effect on the price
            'price_change_amount'=>null,    // the absolute value of the change, e.g. 1.99, even if it is a negative price_change
            'weight_change'=>null,          // same as price_change
            'weight_change_amount'=>null,   // abs value of the weight change
            'order'=>0
        )
    );
}