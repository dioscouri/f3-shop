<?php
namespace Shop\Models;

class Currency extends \Dsc\Singleton
{
    /**
     * Simply formats a number according to the currency's rules
     * 
     * @param unknown $number
     * @param string $currency_code
     * @param unknown $options
     */
    public static function format( $number, $currency_code='USD', $options=array() )
    {
        // TODO make this support more than just USD
        $formatted = '$' . number_format( (float) $number, 2, ".", "," );
        
        return $formatted;
    }
}