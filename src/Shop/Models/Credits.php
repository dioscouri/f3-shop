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
    public $history = array();
    
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
        $this->user_id = new \MongoId( (string) $this->user_id );
        
        return parent::beforeSave();
    }
    
    protected function afterSave()
    {
        if (!empty($this->__issue_to_user)) 
        {
            $this->issue();
        }
        
        parent::afterSave();
    }
    
    /**
     * Gets the associated user object
     *
     * @return unknown
     */
    public function user()
    {
        $user = (new \Users\Models\Users)->load(array('_id'=>$this->user_id));
    
        return $user;
    }
    
    /**
     * Gets a customer's full name,
     * defaulting to email
     *
     * @return unknown
     */
    public function customerName()
    {
        $name = $this->customer_name;
        if (empty($name)) {
            $user = (new \Users\Models\Users)->load(array('_id'=>$this->user_id));
            $name = $user->fullName();
        }
    
        if (empty($name)) {
            $name = $this->user_email;
        }
    
        return $name;
    }
    
    /**
     * Issues a credit, updating the user's balance appropriately
     * 
     * @return \Shop\Models\Credits
     */
    public function issue()
    {
        if (!$this->credit_issued_to_user) 
        {
        	$user = $this->user();
        	if (empty($user->id)) {
        		throw new \Exception('Invalid User');
        	}
        	
        	$this->balance_before = (float) $user->{'shop.credits.balance'};
        	$this->balance_after = $this->balance_before + (float) $this->amount;
        	// Add to the history
        	$this->history[] = array(
        	    'created' => \Dsc\Mongo\Metastamp::getDate('now'),
        	    'subject' => \Dsc\System::instance()->get('auth')->getIdentity()->fullName(),
        	    'verb' => 'issued',
        	    'object' => (float) $this->amount
        	);        	     
            $user->{'shop.credits.balance'} = (float) $this->balance_after;
            $user->save();
            
            $this->credit_issued_to_user = true;
            $this->save();
        }
        
        return $this;
    }
    
    /**
     * Revoke an issued credit, updating the user's balance appropriately
     *
     * @return \Shop\Models\Credits
     */
    public function revoke()
    {
        if ($this->credit_issued_to_user)
        {
            $user = $this->user();
            if (empty($user->id)) {
                throw new \Exception('Invalid User');
            }
             
            $this->balance_before = (float) $user->{'shop.credits.balance'};
            $this->balance_after = $this->balance_before - (float) $this->amount;
            // Add to the history
            $this->history[] = array(
                'created' => \Dsc\Mongo\Metastamp::getDate('now'),
                'subject' => \Dsc\System::instance()->get('auth')->getIdentity()->fullName(),
                'verb' => 'revoked',
                'object' => (float) $this->amount
            );
             
            $user->{'shop.credits.balance'} = (float) $this->balance_after;
            $user->save();
            
            $this->credit_issued_to_user = false;
            $this->save();
        }
    
        return $this;
    }
}