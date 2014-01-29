<?php 
namespace Tienda\Prefabs;

class Product extends \Dsc\Prefabs
{
    protected $default_options = array(
        // 'append' => true // set this to true so that ->bind() adds fields to $this->document even if they aren't in the default document structure
    );
        
    /**
     * Default document structure
     * @var array
     */
    protected $document = array(
        // Start Normalized Content Fields --------------------------------------                    
        'metadata'=>array(
            'title'=>null,          
            'slug'=>null,           
            'categories'=>array()   // with _id and title
            // type=>null           // string, \Tienda\Admin\Models::$type
            // creator=>array()     // handled by \Dsc\Nodes, with _id and _name
            // created=>array()     // \Dsc\Mongo\Metastamp handled by \Dsc\Nodes 
            // last_modified=>array() // \Dsc\Mongo\Metastamp handled by \Dsc\Nodes
        ),
        'details'=>array(
            'copy'=>null,           // string
            'featured_image'=>array(
        	   'slug'=>null         // slug to f3-asset
            ),
            'images'=>array()       // array of f3-asset slugs
        ),
        'publication'=>array(
        	'status'=>null,         // string
            'start_date'=>null,     // YYYY-MM-DD
            'start_time'=>null,     // HH::SS
            'end_date'=>null,       // YYYY-MM-DD
            'end_time'=>null,       // HH:SS
            'start'=>null,          // \Dsc\Mongo\Metastamp
            'end'=>null             // \Dsc\Mongo\Metastamp
        ),
        // End Normalized Content Fields --------------------------------------
        'template'=>null,           // product template, prefab set of custom fields
        'manufacturer'=>array(      // _id and title of a tienda.manufacturer, or treat this like tags?

        ),
        'tracking'=>array(
            'sku'=>null,
            'upc'=>null
        ),
        'visibility'=>array(
        	
        ),
        'prices'=>array(
            'default'=>null,
            'list'=>null,
            'special'=>array(
            
            )
        ),
        'shipping'=>array(
        	'weight'=>null,
        	'dimensions'=>array(
        	   'length'=>null,
        	   'width'=>null,
        	   'height'=>null
            )
        ),
        'taxes'=>array(
        	'enabled'=>true,
        	'class'=>null
        ),
        'attributes'=>array(
        	
        ),
        // pre-built matrix of all possible product variations based on the attributes above
        'variants'=>array(
        	
        ),
        'policies'=>array(
        	
        )
        
    );
}