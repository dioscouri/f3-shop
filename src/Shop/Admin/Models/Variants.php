<?php 
namespace Shop\Admin\Models;

class Variants extends \Dsc\Models\Db\Mongo 
{
    protected $collection = 'shop.products';
    protected $type = 'shop.products';
    protected $default_ordering_direction = '1';
    protected $default_ordering_field = 'metadata.title';

    /**
     * Gets the prefab
     */
    public function prefab( $source=array(), $options=array() ) 
    {
        $prefab = new \Shop\Prefabs\Variant($source, $options);
        return $prefab;
    }
    
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
     * Actually returns a Product object with the Variant's overrides in place
     * 
     * @return 
     */
    public function read()
    {
        return array();
    }
    
    /**
     * Creates a new variant for a product
     */
    public function create( $product_id, $values, $options=array() )
    {
        
    }
    
    /**
     * Updates one or more fields in a Variant
     */
    public function update( $variant_id, $values, $options=array() )
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
    public function delete()
    {
        
    }
}