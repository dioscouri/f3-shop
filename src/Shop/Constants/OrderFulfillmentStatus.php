<?php
namespace Shop\Constants;

class OrderFulfillmentStatus 
{
    const unfulfilled = "unfulfilled";
    const partial = "partial";
    const fulfilled = "fulfilled";
    
    public static function fetch()
    {
        $refl = new \ReflectionClass( get_called_class() );
        return (array) $refl->getConstants();
    }
}