<?php 
namespace Shop\Admin\Models;

class Group extends \Users\Models\Groups
{

    public function __construct($config=array())
    {
        parent::__construct($config);
    } 

    protected function fetchFilters()
    {
        $this->filters = parent::fetchFilters();
    	
		$new_filters = array();
           
        return array_merge($this->filters. $new_filters);
    }
	
    
    public function prefab( $source=array(), $options=array() ) 
    {
        $prefab = new \Shop\Models\Prefabs\UserGroupDetail($source, $options);
        
        return $prefab;
    }
}