<?php
namespace Shop\Models\Prefabs;

class TaxItems extends \Dsc\Prefabs
{
    protected $document = array(
        'id' => null,       // (string) unique identifier
        'name' => null,     // (string) human-readable name for display to customer
        'total' => null,    // $$ total of this line item
        'rate' => null,     // %% (if applicable) Percentage used to determine total tax amount for this line item  
        'type' => null,     // product / shipping
        'code' => null
    );

    protected $default_options = array(
        'append' => true
    );
    
    public function total()
    {
        return $this->total; 
    }
    
    public function rate()
    {
        return $this->rate;
    }
}