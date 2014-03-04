<?php 
namespace Shop\Models;

class Categories extends \Dsc\Mongo\Collections\Categories 
{
    protected $__type = 'shop.categories';

    protected function fetchConditions()
    {
        parent::fetchConditions();
    
        $this->setCondition('type', $this->__type);
    
        return $this;
    }

    protected function beforeUpdate()
    {
        // If the title of the category has changed, update all products using this category
        $this->__update_products = false;
        /*
         * TODO Finish this
        if ((!empty($values['title']) && $values['title'] != $mapper->title)
            || ($values['slug'] != $mapper->slug)
            )
        {
            $this->__update_products = true;
        }
        */
        
        parent::beforeUpdate();
    }
    
    protected function afterUpdate()
    {
        parent::afterUpdate();
        
        /*
         * TODO Finish this
        if ($this->__update_products)
        {
            $id = $update->id;
            $title = $update->title;
            $slug = $update->slug;
        
            // update the category in the Products collection
            $model = \Shop\Admin\Models\Products::instance();
            $collection = $model->getCollection();
            $result = $collection->update(
                    array('metadata.categories.id' => $id ),
                    array(
                                    '$set' => array(
                                                    'metadata.categories.$.title' => $title,
                                                    'metadata.categories.$.slug' => $slug
                                    )
                    ),
                    array( 'multiple' => true )
            );
        }
        */
    }
    
    protected function afterDelete()
    {
        parent::afterDelete();
        
        // If a category is deleted, remove it from any nested 'categories' documents
        /*
        $id = $this->id;

        $model = \Shop\Admin\Models\Products::instance();
        $collection = $model->getCollection();
        $result = $collection->update(
                array('metadata.categories.id' => $id ),
                array(
                                '$pull' => array(
                                                'metadata.categories' => array( 'id' => $id )
                                )
                ),
                array( 'multiple' => true )
        );
        */
    }
}