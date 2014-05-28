<?php 
namespace Shop\Models;

class Credits extends \Dsc\Mongo\Collections\Nodes
{
    public $user_id = null;                     // MongoId
    public $credit_issued_to_user = false;      // bool
    public $amount = null;                      // float, can be negative
    public $balance_before;                     // float
    public $balance_after;                      // float
    public $message;                            // string
    
    protected $__collection_name = 'shop.credits';
    protected $__type = 'general';
    protected $__config = array(
        'default_sort' => array(
            'metadata.created.time' => -1
        )
    );
    
    protected function fetchConditions()
    {
        parent::fetchConditions();
        
        $filter_user_id = $this->getState('filter.user_id');
        if (strlen($filter_user_id))
        {
            $this->setCondition('user_id', new \MongoId( (string) $filter_user_id ) );
        }
    }
    
    public function validate()
    {
        if (empty($this->amount))
        {
        	$this->setError('Credits must have a value');
        }
        
        if (empty($this->user_id))
        {
            $this->setError('Credits must be issued to a user');
        }
        
        return parent::validate();
    }
    
    protected function beforeSave()
    {
        $this->credit_issued_to_user = (bool) $this->credit_issued_to_user;
        $this->amount = (float) $this->amount;
        $this->balance_before = (float) $this->balance_before;
        $this->balance_after = (float) $this->balance_after;
    
        return parent::beforeSave();
    }
    
    protected function afterSave()
    {
        if (!empty($this->__issue_to_user)) 
        {
            // TODO Update the user's record, changing the balance, and if successful, update $this->credit_issued_to_user, $this->balance_before, & $this->balance_after
        }
        
        parent::afterSave();
    }
}