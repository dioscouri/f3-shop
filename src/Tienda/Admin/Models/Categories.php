<?php 
namespace Tienda\Admin\Models;

class Categories extends \Dsc\Models\Categories 
{
    protected $type = 'tienda.categories';

    protected function fetchFilters()
    {
        $this->filters = parent::fetchFilters();
    
        $this->filters['type'] = $this->type;
    
        return $this->filters;
    }
    
    public function update( $mapper, $values, $options=array() ) 
    {
        // If the title of the category has changed, update all posts using this category
        $doUpdate = false;
        if (!empty($values['title']) && $values['title'] != $mapper->title) 
        {
            $doUpdate = true;
        }
        
        $update = parent::update( $mapper, $values, $options );
        
        if ($doUpdate) 
        {
            $id = (string) $update->id;
            $title = $update->title;
            /*
            // update the category in the Products collection
            $model = \Tienda\Admin\Models\Products::instance();
            $collection = $model->getCollection();
            $result = $collection->update(
                    array('metadata.categories.id' => $id ),
                    array(
                            '$set' => array(
                                    'metadata.categories.$.title' => $title
                            )
                    ),
                    array( 'multiple' => true )
            );
            */
        }
        
        return $update;
    }
    
    public function delete( $mapper, $options=array() )
    {
        // If a category is deleted, remove it from any nested documents
        $id = (string) $mapper->id;
        
        if ($delete = parent::delete( $mapper, $options )) 
        {
            /*
            // delete the category from the Products collection
            $model = \Tienda\Admin\Models\Products::instance();
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
        
        return $delete;
    }
}