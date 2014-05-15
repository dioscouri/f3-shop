<?php 
namespace Shop\Models;

/**
 * UNFINISHED Class ultimately intended to simplify Variant management 
 * so it doesn't all have to go through the Products model
 * 
 * @author Rafael Diaz-Tushman
 *
 */
class Variants extends \Dsc\Mongo\Collection 
{
    public $enabled = 1;
    public $attributes = array();  // array of \Shop\Models\Prefabs\Attribute (string) ids
    public $sku = null;
    public $price = null;          // alternative base price.  FINAL price override for this variant.  given priority over attribute price_changes.
    public $quantity = null;
    
    public $model_number = null;
    public $upc = null;
    public $weight = null;
    public $image = null;
    public $title = null;          // e.g. Alternative Title for the product when this variant has been selected
    
    protected $__collection_name = 'shop.products';
    protected $__type = 'shop.products';
    
    
    /**
     * Searches Variants 
     * @return multitype:
     */
    public function search()
    {
        return array();
    }
    
    /**
     * Gets a single Variant based on ID.
     * Actually returns a \Shop\Models\Products object 
     * (with the Variant's overrides in place or something like that?)
     * 
     * @return 
     */
    public static function getById( $id )
    {
        $return = (new \Shop\Models\Products)->load( array('variants.id' => $id ) );
        
        if (empty($return->id)) {
        	throw new \Exception('Invalid Variant ID');
        }
        
        // TODO set the overrides? 
        
        return $return;
    }
    
    /**
     * Gets a single Variant based on a product_id and an array of attribute IDs.
     * Actually returns a \Shop\Models\Products object
     * (with the Variant's overrides in place or something like that?)
     *
     * @return
     */
    public static function getByAttributes( $product_id, array $attributes=array() )
    {
        $attributes = sort($attributes);
        
        $return = (new \Shop\Models\Products)->load( array( '_id' => new \MongoId( (string) $product_id ),  'variants.attributes' => $attributes ) );
    
        if (empty($return->id)) {
            throw new \Exception('Invalid Variant Attributes');
        }
    
        // TODO set the overrides?
    
        return $return;
    }
    
    /**
     * Get the available quantity of a single variant
     * 
     * @param unknown $variant_id
     */
    public static function quantity( $variant_id )
    {
        $quantity = 0;
        
        $product = static::getById($variant_id);
        
        if ($variant = $product->variant($variant_id)) {
            $quantity = (int) $variant['quantity'];
        }
        
        return $quantity;
    }
    
    /**
     * Creates a new variant for a product
     */
    public function creates( $product_id, $values, $options=array() )
    {
        
    }
    
    /**
     * Updates one or more fields in a Variant
     */
    public function updates( $variant_id, $values, $options=array() )
    {
        return $this->getCollection()->update(
    	   array( 'variants.id' => $variant_id ),
           array( '$set' => $values ),
           array( 'multiple' => false, 'upsert' => false ) 
        );
    }
    
    /**
     * Deletes a Variant
     */
    public function deletes()
    {
        
    }
}