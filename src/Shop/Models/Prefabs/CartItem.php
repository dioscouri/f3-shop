<?php 
namespace Shop\Models\Prefabs;

class CartItem extends \Dsc\Prefabs
{
    /**
     * Default document structure
     * @var array
     */
    protected $document = array(
        'id'=>null,             // (string) MongoId
        'hash'=>null,           // (string) md5 hash of variant_id and params
        'variant_id'=>null,     // (string) MongoId
        'product_id'=>null,     // MongoId
        'options'=>array(),     // (array) custom field for plugins that add to cart items
        'price'=>null,
        'quantity'=>null,
        
        // Set these based on the variant_id and the product     
        // Make the fields mirror those of a \Shop\Models\Prefabs\Variant object
        'attribute_title'=>null,          // human-readable string; title of each attribute
        'attribute_titles'=>array(),      // array of each attribute's title        
        'attributes'=>array(),
        'sku'=>null,
        'model_number'=>null,        
        'upc'=>null,
        'weight'=>null,
        'image'=>null,

        'product'=>null        // complete \Shop\Models\Products object cast as an array                                       
    );
    
    public function __construct($source=array(), $options=array())
    {
        $this->set('id', (string) new \MongoId );
        parent::__construct( $source, $options );
        
        $this->hash();
    }
    
    public function hash()
    {
        $str = trim( (string) $this->variant_id . serialize( $this->options ) );
        $this->hash = md5($str);
        
        return $this;
    }
}