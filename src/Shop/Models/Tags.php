<?php
namespace Shop\Models;

class Tags extends \Dsc\Models
{
    public $title = null;
    protected $__associated_products = array();
    protected $__config = array(
        'crud_item_key' => 'title' 
    );

    /**
     * Helper method for creating select list options
     *
     * @param array $query            
     * @return multitype:multitype:string NULL
     */
    public static function productsForSelection( $tag )
    {
        return \Shop\Models\Products::forSelection( array(
            'tags' => $tag 
        ) );
    }

    /**
     * Gets all productIDs assigned to this tag
     *
     * @param string $category_id            
     * @return multitype: multitype:string
     */
    public static function productIds( $tag = null )
    {
        $result = array();
        if (empty( $tag ))
        {
            return $result;
        }
        
        $cursor = (new \Shop\Models\Products())->collection()->find( array(
            'tags' => $tag 
        ), array(
            "_id" => 1 
        ) );
        
        foreach ( $cursor as $doc )
        {
            $result[] = (string) $doc['_id'];
        }
        
        return $result;
    }

    /**
     * Gets a count of all products assigned to this tag
     *
     * @param string $category_id            
     * @return multitype: multitype:string
     */
    public static function productCount( $tag = null )
    {
        $result = 0;
        if (empty( $tag ))
        {
            return $result;
        }
        
        $result = (new \Shop\Models\Products())->collection()->count( array(
            'tags' => $tag 
        ) );
        
        return $result;
    }

    /**
     */
    public function products()
    {
        if (empty( $this->__associated_products ))
        {
            $this->__associated_products = (new \Shop\Models\Products())->setState( 'filter.tag', $this->title )->getItems();
        }
        
        return $this->__associated_products;
    }

    /**
     * Updating a tag is a matter of re-assigning it to products
     * TODO and maybe changing its title?
     *
     * @param unknown $document            
     * @param unknown $options            
     * @return \Shop\Models\Tags
     */
    public function update( $document = array(), $options = array() )
    {
        $this->__options = $options;
        $this->bind( $document, $options );
        
        // handle the __products CSV, adding/removing product/tag associations where necessary
        if (isset( $this->__products ))
        {
            $product_ids = array();
            if (! is_array( $this->__products ))
            {
                $this->__products = trim( $this->__products );
                if (! empty( $this->__products ))
                {
                    $product_ids = \Base::instance()->split( (string) $this->__products );
                }
            }
            
            if (! empty( $product_ids ) && is_array( $product_ids ))
            {
                $this->assignToProducts( $product_ids );
            }
            // remove the tag from all products
            elseif (empty( $product_ids ))
            {
                $this->removeFromAllProducts();
            }
        }
        
        return $this;
    }

    /**
     * TBD
     * 
     * @return \Shop\Models\Tags
     */
    public function insert()
    {
        \Dsc\System::addMessage( \Dsc\Debug::dump( 'doing insert' ) );
        \Dsc\System::addMessage( \Dsc\Debug::dump( $this->cast() ) );
        
        return $this;
    }

    /**
     * Displaying a Tag model is just a matter of displaying its title
     */
    public function __toString()
    {
        return $this->title;
    }

    /**
     * Assigns a tag to a set of products.
     * If $remove=true, then the tag will be removed from all products that are not in the set.
     *
     * @param array $product_ids            
     * @param bool $remove            
     * @return \MongoId
     */
    public function assignToProducts( array $product_ids = array(), $remove = true )
    {
        // if a CSV of product_ids has been bound to the model, update the set of products associated with this tag
        if (! empty( $product_ids ))
        {
            // convert the array of product ids into an array of MongoIds
            $product_ids = array_map( function ( $input )
            {
                return new \MongoId( $input );
            }, $product_ids );
            
            $tag = $this->title;
            
            // OK, we have an array of product MongoIDs. Now make two queries:
            // 1. Add this tag to all products whose ID is in this array
            $add_result = (new \Shop\Models\Products())->collection()->update( array(
                '_id' => array(
                    '$in' => $product_ids 
                ) 
            ), array(
                '$addToSet' => array(
                    'tags' => $tag 
                ) 
            ), array(
                'multiple' => true 
            ) );
            
            if (! empty( $remove ))
            {
                // 2. Remove this tag from all products whose ID is not in this array
                $remove_result = (new \Shop\Models\Products())->collection()->update( array(
                    '_id' => array(
                        '$nin' => $product_ids 
                    ),
                    'tags' => $tag 
                ), array(
                    '$pull' => array(
                        'tags' => $tag 
                    ) 
                ), array(
                    'multiple' => true 
                ) );
            }
        }
        
        return $this;
    }

    /**
     * Remove a tag from all products
     */
    public function removeFromAllProducts()
    {
        $tag = $this->title;
        
        $remove_result = (new \Shop\Models\Products())->collection()->update( array(
            'tags' => $tag 
        ), array(
            '$pull' => array(
                'tags' => $tag 
            ) 
        ), array(
            'multiple' => true 
        ) );
        
        return $this;
    }
}