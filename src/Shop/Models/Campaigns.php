<?php
namespace Shop\Models;

class Campaigns extends \Dsc\Mongo\Collections\Describable
{
    use\Dsc\Traits\Models\Publishable;
    use\Dsc\Traits\Models\Ancestors;

    public $campaign_type = 'lto';

    protected $__collection_name = 'shop.campaigns';

    protected $__type = 'campaigns';
    
    protected $__config = array(
        'default_sort' => array(
            'path' => 1
        ),
    );

    protected function fetchConditions()
    {
        parent::fetchConditions();
        
        $this->ancestorsFetchConditions();
    }

    protected function beforeValidate()
    {
        $this->ancestorsBeforeValidate();
        
        return parent::beforeValidate();
    }

    protected function beforeUpdate()
    {
        $this->ancestorsBeforeUpdate();
        
        return parent::beforeUpdate();
    }

    protected function beforeSave()
    {
        $this->publishableBeforeSave();
        $this->ancestorsBeforeSave();
        
        return parent::beforeSave();
    }

    protected function afterUpdate()
    {
        $this->ancestorsAfterUpdate();
        
        return parent::afterUpdate();
    }
}