<?php
namespace Shop\Models;

class GoogleMerchantTaxonomy extends \Dsc\Models
{
    /**
     * Helper method for creating select list options
     *
     * @param array $query
     * @return multitype:multitype:string NULL
     */
    public static function forSelection($term)
    {
        $path_base = __DIR__;
        
        $data = file($path_base . '/Data/GoogleMerchant/taxonomy.en-US.txt');
        
        $items = array();
        foreach ($data as $value)
        {
            if (stripos($value, $term) !== false)
            {
                $items[] = $value;
            }
        }
        
        $result = array();
        foreach ($items as $doc) 
        {
            $array = array(
                'id' => $doc,
                'text' => $doc,
            );
            $result[] = $array;
        }
    
        return $result;
    }
}