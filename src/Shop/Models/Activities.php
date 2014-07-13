<?php
namespace Shop\Models;

class Activities extends \Dsc\Activities
{
    public static function track($action, $properties=array())
    {
        $action_properties = $properties + array(
            'app' => 'shop'
        );
        
        return parent::track($action, $action_properties);
    }
}