<?php 
namespace Shop\Site;

class Factory extends \Prefab 
{
    /**
     * Returns the currency selected for the session.
     * Default is whatever is set in the Shop config
     *  
     * @return string
     */
    public static function currency()
    {
        $default = 'USD'; // TODO Get this from the Shop config

        $currency = $default;
        
        // TODO has the user changed the selected currency?  if so, change $currency to what the user has selected and validate it
        
        // TODO Return a standardized currency object that has a __toString() method that defaults to it's name (eg. USD)
        
        return $currency;
    }
}