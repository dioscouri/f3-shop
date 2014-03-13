<?php 
namespace Shop\Models;

class Manufacturers extends \Dsc\Mongo\Collections\Describable implements \MassUpdate\Service\Models\MassUpdateOperations
{
    protected $__collection_name = 'shop.manufacturers';
    protected $__type = 'shop.manufacturers';
    protected $__config = array(
        'default_sort' => array(
            'title' => 1
        ),
    );

    use \MassUpdate\Service\Traits\Model;
    
    protected function beforeUpdate()
    {
        // TODO if this item has changed its title, update products afterUpdate()
        /*
        $old = $this->load(array('_id' => $this->_id ));
        if ($old->parent != $this->parent || $old->title != $this->title) {
            // update children after save
            $this->__options['update_children'] = true;
        }
        */
        return parent::beforeUpdate();
    }
    
    protected function afterUpdate()
    {
        // TODO Update products to change the name
        /*
        if (!empty($this->__options['update_children']))
        {
            if ($children = $this->emptyState()->setState('filter.parent', $updated->id)->getItems())
            {
                foreach ($children as $child)
                {
                    unset($child->ancestors);
                    unset($child->path);
                    $child->update(array(), array('update_children' => true));
                }
            }
        }
        */
    }

    /**
     * This method gets list of attribute groups with operations
     */
    public function getMassUpdateOperationGroups(){
    	$arr = array();

    	$attr_title = new \MassUpdate\Service\Models\AttributeGroup;
    	$attr_title->setAttributeCollection('metadata.title')
    				->setAttributeTitle( "Title" )
    				->addOperation( new \MassUpdate\Operations\Update\ChangeTo, 'update' );
    	
    	$arr []= $attr_title;
    	return $arr;
    }
}