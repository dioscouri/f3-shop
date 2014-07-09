<?php
namespace Shop\Models;

class Activities extends \Dsc\Activities
{
    public static function track($action, $properties=array())
    {
        if (class_exists('\Activity\Models\Actions'))
        {
            $action_properties = $properties + array(
                'app' => 'shop'
            );
            \Activity\Models\Actions::track($action, $action_properties);
        }
        
        return null;
    }
}