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
}