<?php
namespace Shop\Models\Prefabs;

class PaymentMethods extends \Dsc\Prefabs
{
    protected $document = array(
        'id' => null,       // (string) unique identifier
        'name' => null,     // (string) human-readable name for display to customer
        'price' => null,    // $$ base price of shipping method
        'tax' => null,      // $$ tax applied to this method
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
            + $this->tax
            + $this->extra; 
    }
}