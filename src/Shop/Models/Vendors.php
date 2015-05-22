<?php 
namespace Shop\Models;

class Vendors extends \Dsc\Mongo\Collection
{	
	
    protected $__collection_name = 'shop.vendors';
    protected $__type = 'shop.vendors';
    protected $__config = array(
        'default_sort' => array(
            'title' => 1
        ),
    );
    
    
    protected function fetchConditions() {
    	
    	parent::fetchConditions();
    }
    
    protected function beforeValidate()
    {
    	parent::beforeValidate();
    }
    
    protected function beforeUpdate()
    {
        
        return parent::beforeUpdate();
    }
    
    protected function afterUpdate()
    {
        
    }
}