<?php 
namespace Shop\Models\Prefabs;

class Variant extends \Dsc\Prefabs
{
    /**
     * Default document structure
     * @var array
     */
    protected $document = array(
        'id'=>null,             // (string) MongoId
        'key'=>null,            // alphabetized, hyphenated string of each attribute's MongoId
        'enabled'=>1,
        'attribute_title'=>null,          // human-readable string; pipe-concatenated string with title of each attribute 
        'attribute_titles'=>array(),      // array of each attribute's title
        'attributes'=>array(),  // array of \Shop\Models\Prefabs\Attribute (string) ids, sorted
        'sku'=>null,        
        'price'=>null,          // alternative base price.  FINAL price override for this variant.  given priority over attribute price_changes.        
        'quantity'=>null,
                    
        'model_number'=>null,        
        'upc'=>null,
        'weight'=>null,
        'image'=>null                                    
    );
    
    public function __construct($source=array(), $options=array())
    {
        $this->set('id', (string) new \MongoId );
        parent::__construct( $source, $options );
    }
}