<?php
namespace Shop\Models;

class Campaigns extends \Dsc\Mongo\Collections\Describable
{
    use\Dsc\Traits\Models\Publishable;
    use\Dsc\Traits\Models\Ancestors;
    use\Dsc\Traits\Models\ForSelection;
    
    public $campaign_type = 'lto';
    
    public $rule_min_spent = null;
    public $reward_groups = array();
    public $expire_groups = array();

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
        $this->forSelectionBeforeValidate('reward_groups');
        $this->forSelectionBeforeValidate('expire_groups');
        
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