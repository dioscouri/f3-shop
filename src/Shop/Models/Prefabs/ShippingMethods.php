<?php
namespace Shop\Models\Prefabs;

class ShippingMethods extends \Dsc\Prefabs
{
    protected $document = array(
        'id' => null,       // (string) unique identifier
        'name' => null,     // (string) human-readable name for display to customer
        'price' => null,    // $$ base price of shipping method
        'extra' => null,    // $$ any handling/surcharge/extra fees
        'type' => null,
        'code' => null
    );

    protected $default_options = array(
        'append' => true
    );
    
    public function total()
    {
        return $this->price 
            + $this->extra; 
    }
}