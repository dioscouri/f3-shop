<?php 
namespace Shop\Prefabs;

class Variant extends \Dsc\Prefabs
{
    /**
     * Default document structure
     * @var array
     */
    protected $document = array(
        'id'=>null,             // the md5 hash of the dot-connected string of an alphabetized list of each attribute's MongoId
        'attributes'=>array(),  // array of \Shop\Prefabs\Attribute ids, sorted alphanumerically
        'sku'=>null,        
        'price'=>null,          // alternative base price.  FINAL price override for this variant.  given priority over attribute price_changes.        
        'quantity'=>null,
                    
        'model_number'=>null,        
        'upc'=>null,
        'weight'=>null,
        'image'=>null,
        'title'=>null          // e.g. Alternative Title for the product when this variant has been selected                                    
    );
}