<?php
namespace Shop\Models;

class Regions extends \Dsc\Mongo\Collection
{
    public $name = null;
    public $code = null;
    public $country_isocode_2 = null;
    
    protected $__collection_name = 'shop.regions';
    protected $__config = array(
        'default_sort' => array(
            'name' => 1 
        ) 
    );
    
    public static function byCountry( $country_isocode_2 )
    {
        return \Shop\Models\Regions::find(array(
        	'country_isocode_2' => $country_isocode_2
        )); 
    }

    public static function forSelection()
    {
        if (empty($this)) {
            $model = new static();
        } else {
            $model = clone $this;
        }
    
        $cursor = $model->collection()->find();
        $cursor->sort(array(
            'country_isocode_2' => 1,
            'code' => 1
        ));
    
        $result = array();
        foreach ($cursor as $doc) {
            $array = array(
                'id' => $doc['code'],
                'text' => htmlspecialchars( $doc['country_isocode_2'] . ': ' . $doc['code'] . ' - ' . $doc['name'], ENT_QUOTES ),
            );
            $result[] = $array;
        }
    
        return $result;
    }
}