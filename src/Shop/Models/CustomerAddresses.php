<?php
namespace Shop\Models;

class CustomerAddresses extends \Shop\Models\Address
{
    public $user_id = null;
    
    protected $__collection_name = 'shop.addresses';
    protected $__type = 'shop.addresses';
    protected $__config = array(
        'default_sort' => array(
            'title' => 1
        )
    );
    
    protected function fetchConditions()
    {
        parent::fetchConditions();
    
        $this->setCondition( 'type', $this->__type );
    
        $filter_user = $this->getState('filter.user');
        if (strlen($filter_user))
        {
            $this->setCondition('user_id', new \MongoId((string) $filter_user));
        }
    
        return $this;
    }
    
    protected function beforeValidate()
    {
        if (empty($this->user_id)) 
        {
            $identity = \Dsc\System::instance()->get( 'auth' )->getIdentity();
            if (!empty( $identity->id ))
            {
                $this->set('user_id', $identity->id);
            }
        }
    
        return parent::beforeValidate();
    }
    
    public function validate()
    {
        if (empty($this->user_id)) 
        {
        	$this->setError('Addresses must have an associated customer');
        }
        
        return parent::validate();
    }
}