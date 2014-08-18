<?php
namespace Shop\Models;

class CustomerAddresses extends \Shop\Models\Address
{
    public $user_id = null;
    public $primary_billing = null;
    public $primary_shipping = null;
    
    protected $__collection_name = 'shop.addresses';
    protected $__type = 'shop.addresses';
    protected $__config = array(
        'default_sort' => array(
            'metadata.last_modified.time' => -1
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
    
    /**
     * Get the current user's addresses
     *
     * @return array \Shop\Models\CustomerAddresses
     */
    public static function fetch()
    {
        $identity = \Dsc\System::instance()->get('auth')->getIdentity();
        if (empty($identity->id))
        {
            return array();
        }
        
        $items = (new static)->setState('filter.user', (string) $identity->id )->getItems();
    
        return $items;
    }
    
    /**
     * Get the addresses for a specified user id
     *
     * @return array \Shop\Models\CustomerAddresses
     */
    public static function fetchForId($id)
    {
        $items = (new static)->setState('filter.user', (string) $id )->getItems();
    
        return $items;
    }    
    
    /**
     * 
     * @return \Shop\Models\CustomerAddresses
     */
    public function setAsPrimaryBilling()
    {
        // set primary_billing = null for all user's addresses
        $this->__last_operation = $this->collection()->update(
            array(
                'user_id'=>$this->user_id
            ),
            array('$set' => array(
                'primary_billing'=>null
            )),
            array('multiple'=>true)
        );
                
        // set primary_billing = true for this address
        $this->update(array(
        	'primary_billing'=>true
        ), array(
            'overwrite'=>false
        ));
                
        return $this;
    }
    
    public function setAsPrimaryShipping()
    {
        // set primary_shipping = null for all user's addresses
        $this->__last_operation = $this->collection()->update(
            array(
                'user_id'=>$this->user_id
            ),
            array('$set' => array(
                'primary_shipping'=>null
            )),
            array('multiple'=>true)
        );
    
        // set primary_shipping = true for this address
        $this->update(array(
            'primary_shipping'=>true
        ), array(
            'overwrite'=>false
        ));
    
        return $this;
    }
}