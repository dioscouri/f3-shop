<?php
namespace Shop\Constants;

class OrderStatus 
{
    const open = "open";
    const closed = "closed";
    const cancelled = "cancelled";
    
    public static function fetch() 
    {
        $refl = new \ReflectionClass( get_called_class() );
        return (array) $refl->getConstants();    	
    }
}