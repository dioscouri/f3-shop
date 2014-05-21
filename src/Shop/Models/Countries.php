<?php
namespace Shop\Models;

class Countries extends \Dsc\Mongo\Collection
{
	use \Dsc\Traits\Models\OrderableCollection;
	
    public $name = null;
    public $isocode_2 = null;
    public $isocode_3 = null;
    public $enabled = null;
    
    protected $__collection_name = 'shop.countries';
    protected $__config = array(
        'default_sort' => array(
        	'ordering' => 1,
            'name' => 1,
        ) 
    );
    
    protected function fetchConditions()
    {
        parent::fetchConditions();
    
        $filter_keyword = $this->getState('filter.keyword');
        if ($filter_keyword && is_string($filter_keyword))
        {
            $key =  new \MongoRegex('/'. $filter_keyword .'/i');
    
            $where = array();
            $where[] = array('name'=>$key);
            $where[] = array('isocode_2'=>$key);
            $where[] = array('isocode_3'=>$key);
    
            $this->setCondition('$or', $where);
        }
        
        $filter_only_enabled = $this->getState( 'filter.enabled', null );
    
        if( strlen( $filter_only_enabled ) ){
        	$this->setCondition( "enabled", (int)$filter_only_enabled );
    	}
        return $this;
    }

    /**
     * Helper method for creating select list options
     *
     * @param array $query
     * @return multitype:multitype:string NULL
     */
    public static function forSelection(array $query=array())
    {
        if (empty($this)) {
            $model = new static();
        } else {
            $model = clone $this;
        }
        
        $cursor = $model->collection()->find($query, array("isocode_2"=>1, "name"=>1) );
        $cursor->sort(array(
        	'isocode_2' => 1
        ));
        
        $result = array();
        foreach ($cursor as $doc) {
            $array = array(
            	'id' => $doc['isocode_2'],
                'text' => htmlspecialchars( $doc['isocode_2'] . ' - ' . $doc['name'], ENT_QUOTES ),
            );
            $result[] = $array;
        }
        
        return $result;
    }
    
    protected function afterSave()
    {
    	parent::afterSave();
    	$this->compressOrdering();
    }
    
}