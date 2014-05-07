<?php
namespace Shop\Models;

class Categories extends \Dsc\Mongo\Collections\Categories implements \MassUpdate\Service\Models\MassUpdateOperations
{
    use\MassUpdate\Service\Traits\Model;
    protected $__type = 'shop.categories';

    protected function fetchConditions()
    {
        parent::fetchConditions();
        
        $this->setCondition( 'type', $this->__type );
        
        return $this;
    }

    protected function beforeUpdate()
    {
        // If the title of the category has changed, update all products using this category
        $this->__update_products = false;
        /*
         * TODO Finish this if ((!empty($values['title']) && $values['title'] != $mapper->title) || ($values['slug'] != $mapper->slug) ) { $this->__update_products = true; }
         */
        
        parent::beforeUpdate();
    }

    protected function afterUpdate()
    {
        parent::afterUpdate();
        
        /*
         * TODO Finish this if ($this->__update_products) { $id = $update->id; $title = $update->title; $slug = $update->slug; // update the category in the Products collection $model = \Shop\Admin\Models\Products::instance(); $collection = $model->getCollection(); $result = $collection->update( array('metadata.categories.id' => $id ), array( '$set' => array( 'metadata.categories.$.title' => $title, 'metadata.categories.$.slug' => $slug ) ), array( 'multiple' => true ) ); }
         */
    }

    protected function afterDelete()
    {
        parent::afterDelete();
        
        // If a category is deleted, remove it from any nested 'categories' documents
        /*
         * $id = $this->id; $model = \Shop\Admin\Models\Products::instance(); $collection = $model->getCollection(); $result = $collection->update( array('metadata.categories.id' => $id ), array( '$pull' => array( 'metadata.categories' => array( 'id' => $id ) ) ), array( 'multiple' => true ) );
         */
    }

    /**
     * This method gets list of attribute groups with operations
     *
     * @return Array with attribute groups
     */
    public function getMassUpdateOperationGroups()
    {
        if ($this->needInitializationMassUpdate())
        {
            
            $attr_cat = new \MassUpdate\Service\Models\AttributeGroup();
            $attr_cat->setAttributeCollection( 'ancestors.id' )->setModel( $this )->setAttributeTitle( "Parent Categories" )->addOperation( new \MassUpdate\Operations\Condition\Category(), 'where', array(
                'mode' => 1 
            ) );
            
            $attr_created = new \MassUpdate\Service\Models\AttributeGroup();
            $attr_created->setAttributeCollection( 'metadata.created.time' )->setAttributeTitle( "Category Created" )->setModel( $this )->addOperation( new \MassUpdate\Operations\Condition\DateTimeCompare(), 'where', array(
                'mode' => 1 
            ) );
            
            $attr_title = new \MassUpdate\Service\Models\AttributeGroup();
            $attr_title->setAttributeCollection( 'title' )->setAttributeTitle( "Category Name" )->setModel( $this )->addOperation( new \MassUpdate\Operations\Update\ModifyTo(), 'update' );
            
            $attr_last_modified = new \MassUpdate\Service\Models\AttributeGroup();
            $attr_last_modified->setAttributeCollection( 'metadata.last_modified' )->setAttributeTitle( "Last Modified" )->setModel( $this )->addOperation( new \MassUpdate\Operations\Update\ChangeDateTime(), 'update' );
            
            $this->addAttributeGroupMassUpdate( $attr_title );
            $this->addAttributeGroupMassUpdate( $attr_cat );
            $this->addAttributeGroupMassUpdate( $attr_created );
            $this->addAttributeGroupMassUpdate( $attr_last_modified );
        }
        
        return $this->getAttributeGroupsMassUpdate();
    }

    /**
     * Gets all products assigned to a category, regardless of publication status, etc
     *
     * @param unknown $category_id            
     */
    public static function products( $category_id, $filters = array() )
    {
        $model = new \Shop\Models\Products();
        $model->setState( 'filter.category.id', $category_id );
        
        foreach ( $filters as $key => $value )
        {
            $model->setState( $key, $value );
        }
        $items = $model->getItems();
        
        return $items;
    }

    /**
     * Gets all productIDs assigned to this category
     *
     * @param string $category_id            
     * @return multitype: multitype:string
     */
    public static function productIds( $category_id = null )
    {
        $result = array();
        if (empty( $category_id ))
        {
            return $result;
        }
        
        $cursor = (new \Shop\Models\Products())->collection()->find( array(
            'categories.id' => new \MongoId( (string) $category_id ) 
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
     * Gets a count of all products assigned to this category
     *
     * @param string $category_id
     * @return multitype: multitype:string
     */
    public static function productCount( $category_id = null )
    {
        $result = 0;
        if (empty( $category_id ))
        {
            return $result;
        }
    
        $result = (new \Shop\Models\Products())->collection()->count( array(
            'categories.id' => new \MongoId( (string) $category_id )
        ));
    
        return $result;
    }

    /**
     * 
     */
    public function distinctAttributeOptions($query=array()) 
    {
        $category_id = $this->id;
        
        $query = $query + array(
            'categories.id' => new \MongoId( (string) $category_id )
        );
        
        $distinct = array();
        
        $distinct = (new \Shop\Models\Products)->collection()->distinct("attributes.options.value", $query);
        $distinct = array_values( array_filter( $distinct ) );
        
        return $distinct;    	
    }

    /**
     * 
     * @return \MongoId
     */
    protected function afterSave()
    {
        // if a CSV of product_ids has been bound to the model, update the set of products associated with this category
        if (! empty( $this->__products ))
        {
            if (! is_array( $this->__products ))
            {
                $this->__products = trim( $this->__products );
                if (! empty( $this->__products ))
                {
                    $this->__products = \Base::instance()->split( (string) $this->__products );
                }
            }
            
            if (! empty( $this->__products ) && is_array( $this->__products ))
            {
                // convert the array of product ids into an array of MongoIds
                $this->__products = array_map( function ( $input )
                {
                    return new \MongoId( $input );
                }, $this->__products );
                
                // TODO Update this to include "path" when we make that update
                $category = array(
                    'id' => new \MongoId( (string) $this->id ),
                    'title' => $this->title,
                    'slug' => $this->slug 
                );
                
                $category_id = new \MongoId( (string) $this->id );
                
                // OK, we have an array of product MongoIDs. Now make two queries:
                // 1. Add this category to all products whose ID is in this array
                $add_result = (new \Shop\Models\Products())->collection()->update( array(
                    '_id' => array(
                        '$in' => $this->__products 
                    ) 
                ), array(
                    '$addToSet' => array(
                        'categories' => $category 
                    ) 
                ), array(
                    'multiple' => true 
                ) );
                
                // 2. Remove this category from all products whose ID is not in this array
                $remove_result = (new \Shop\Models\Products())->collection()->update( array(
                    '_id' => array(
                        '$nin' => $this->__products 
                    ),
                    'categories.id' => $category_id
                ), array(
                    '$pull' => array(
                        'categories' => array( 'id'=>$category_id ) 
                    ) 
                ), array(
                    'multiple' => true 
                ) );
            }
            
            unset($this->__products);
        }
        
        return parent::afterSave();
    }
}