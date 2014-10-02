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
        $settings = \Shop\Models\Settings::fetch();
        
        // TODO Support custom formatting
        switch ($settings->{'currency.default'}) 
        {
            case "INR":
                $formatted = 'Rs.' . number_format( (float) $number, 0, ".", "," );
                break;
            case "USD":
            default:
                $formatted = '$' . number_format( (float) $number, 2, ".", "," );
                break;
        }        
        
        return $formatted;
    }
}