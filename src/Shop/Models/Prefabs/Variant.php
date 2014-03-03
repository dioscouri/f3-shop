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
        'attributes'=>array(),  // array of \Shop\Models\Prefabs\Attribute (string) ids
        'sku'=>null,        
        'price'=>null,          // alternative base price.  FINAL price override for this variant.  given priority over attribute price_changes.        
        'quantity'=>null,
                    
        'model_number'=>null,        
        'upc'=>null,
        'weight'=>null,
        'image'=>null,
        'title'=>null          // e.g. Alternative Title for the product when this variant has been selected                                    
    );
    
    public function __construct($source=array(), $options=array())
    {
        $this->set('id', (string) new \MongoId );
        parent::__construct( $source, $options );
    }
}