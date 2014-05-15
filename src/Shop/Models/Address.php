<?php
namespace Shop\Models;

class Address extends \Dsc\Mongo\Collections\Nodes
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
    
    /**
     * 
     */
    protected function beforeSave()
    {
        if (!is_a($this, '\Shop\Models\CustomerAddresses')) 
        {
            $this->setError('Addresses can only be saved as CustomerAddresses');
        }
        
        if (empty($this->user_id))
        {
            $this->setError('Addresses must have an associated customer');
        }
    
        return parent::beforeSave();
    }
    
    public function validate()
    {
        if (empty($this->name))
        {
            $this->setError('Addresses must have an addressee');
        }
    
        if (empty($this->line_1))
        {
            $this->setError('Addresses must have the first street line');
        }
    
        if (empty($this->city))
        {
            $this->setError('Addresses must have a city');
        }
    
        if (empty($this->region))
        {
            $this->setError('Addresses must have a region/state');
        }
    
        if (empty($this->country))
        {
            $this->setError('Addresses must have a country');
        }
    
        if (empty($this->postal_code))
        {
            $this->setError('Addresses must have a postal code');
        }
    
        return parent::validate();
    }
    
    public function asString($glue='<br/>')
    {
        $strings = array();
    
        if (!empty($this->name)) {
            $strings[] = $this->name;
        }
    
        if (!empty($this->line_1)) {
            $strings[] = $this->line_1;
        }
    
        if (!empty($this->line_2)) {
            $strings[] = $this->line_2;
        }
    
        $strings[] = trim($this->city . ' ' . $this->region . ' ' . $this->postal_code);
    
        if (!empty($this->country)) {
            $strings[] = $this->country;
        }
    
        return implode($glue, $strings);
    }
    
    public function __toString()
    {
        return $this->asString();
    }
}