<?php
namespace Shop\Models;

class Countries extends \Dsc\Mongo\Collection
{
    public $name = null;
    public $isocode_2 = null;
    public $isocode_3 = null;
    public $enabled = null;
    public $ordering = null;
    
    protected $__collection_name = 'shop.countries';
    protected $__config = array(
        'default_sort' => array(
            'name' => 1 
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
}