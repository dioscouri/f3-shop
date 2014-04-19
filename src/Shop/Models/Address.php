<?php
namespace Shop\Models;

class Address extends \Dsc\Models
{
    public $name = null;     // full name of the customer
    public $line_1 = null;    // Line 1
    public $line_2 = null;    // Line 2
    public $line_3 = null;
    public $city = null;
    public $region = null;      // code
    public $country = null;      // iso2 code
    public $postal_code = null;
    public $phone_number = null;
    
    /**
     * 
     * @return unknown
     */
    public function country()
    {
        $model = (new \Shop\Models\Countries)->load(array('isocode_2' => $this->country));
        
        if (empty($model->id)) 
        {
        	throw new \Exception( 'Invalid Country Code');
        }
        
        return $model;
    }
    
    /**
     *
     * @return unknown
     */
    public function region()
    {
        $model = (new \Shop\Models\Regions)->load(array('country_isocode_2'=>$this->country()->isocode_2, 'code'=>$this->region));
    
        if (empty($model->id))
        {
            throw new \Exception( 'Invalid Region Code');
        }
    
        return $model;
    }
}