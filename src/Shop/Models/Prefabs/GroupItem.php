<?php 
namespace Shop\Models\Prefabs;

class GroupItem extends \Dsc\Prefabs
{
    /**
     * Default document structure
     * @var array
     */
    protected $document = array(
        'id'=>null,                         // (string) MongoId
        'sku'=>null,      
    	'quantity'=> 1, //amount of this sku included in group
        'model_number'=>null,
    	'title'=>null,
    	'description'=>null,
        'upc'=>null,
        'image'=>null
    );
    
    public function __construct($source=array(), $options=array())
    {
    	
        $this->set('id', (string) new \MongoId($source['id']) );
        
        parent::__construct( $source, $options );
    }
}