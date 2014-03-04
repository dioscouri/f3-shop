<?php 
namespace Shop\Models;

class Manufacturers extends \Dsc\Mongo\Collections\Describable
{
    protected $__collection_name = 'shop.manufacturers';
    protected $__type = 'shop.manufacturers';
    protected $__config = array(
        'default_sort' => array(
            'title' => 1
        ),
    );
    
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
}