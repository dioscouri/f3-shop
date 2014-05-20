<?php 
namespace Shop\Models;

class OrderedGiftCards extends \Dsc\Mongo\Collections\Nodes
{
    public $code;
    public $initial_value;
    public $balance;
    
    protected $__collection_name = 'shop.orders.giftcards';
    protected $__type = 'shop.orders.giftcards';
    protected $__config = array(
        'default_sort' => array(
            'metadata.created.time' => -1
        )
    );
    
    protected function fetchConditions()
    {
        parent::fetchConditions();
    }
    
    /**
     * Create a gift card for each email address and email the code to the recipient
     * 
     * @param array $data
     * @param array $emails
     * @throws \Exception
     * @return boolean
     */
    public static function issueToEmails( array $data, array $emails ) 
    {
        if (empty($data['initial_value'])) {
            throw new \Exception( 'Gift card must have an initial value' );
        }
                
    	if (empty($emails)) {
    		throw new \Exception( 'Must provide email recipients' );
    	}
    	
    	foreach ((array) $emails as $email) 
    	{
    	    // TODO Put this in a try/catch and return details of successful/failed emails
    		$model = (new static)->bind(array(
    			'initial_value' => $data['initial_value'],
    		    '__email_recipient' => $email
    		))->save();
    	}
    	
    	return true;
    }
    
    protected function beforeValidate()
    {
        if (empty($this->code)) {
        	$this->code = $this->createCode();
        }
        
        return parent::beforeValidate();
    }
    
    public function validate()
    {
        if (empty($this->initial_value))
        {
        	$this->setError('Gift Cards must have an initial value');
        }
        
        return parent::validate();
    }
    
    protected function beforeCreate()
    {
        $this->balance = $this->initial_value;
    
        return parent::beforeCreate();
    }
    
    protected function afterSave()
    {
        if (!empty($this->__email_recipient)) 
        {
        	// TODO Send this code via email
        	\Dsc\System::addMessage( 'Would send a gift card via email to ' . $this->__email_recipient );
        }
        
        parent::afterSave();
    }
    
    /**
     * Creates a human-readable version of the OGC id (it's MongoID)
     * @return unknown
     */
    public function createCode()
    {
        if (empty($this->id))
        {
            $this->id = new \MongoId;
        }
    
        $id = (string) $this->id;
    
        $number = strtolower( substr( $id, 0, 4 ) . '-' . substr( $id, 4, 4 ) . '-' . substr( $id, 8, 4 ) . '-' . substr( $id, 12, 4 ) . '-' . substr( $id, 16, 4 ) . '-' . substr( $id, 20, 4 ) );
    
        return $number;
    }
    
    /**
     * 
     * @return number
     */
    public function balance()
    {
        return (float) $this->balance;
    }
    
    /**
     * Determines if this coupon is valid for a cart
     *
     * @param \Shop\Models\Carts $cart
     * @throws \Exception
     */
    public function cartValid( \Shop\Models\Carts $cart )
    {
        // Does the cart already have a gift card used?
        if (!empty($cart->giftcards)) 
        {
            throw new \Exception('Only one gift card allowed per cart');
        }        
        
    	// Does the cart have a total > 0?
    	if ($cart->total() <= (float) 0) 
    	{
    	    throw new \Exception('Cart total must be greater than 0 to use a gift card');
    	}
    	
    	// Does the card have available balance?
    	if ($this->balance() <= (float) 0) 
    	{
    	    throw new \Exception('This gift card does not have an available balance');
    	}
    	
        /**
         * if we made it this far, the cart is valid for this card
         */
        $this->__is_validated = true;
        
        return $this;
    }
    
    /**
     * Calculates the value of this card against the data in a cart
     *
     * @param \Shop\Models\Carts $cart
     * @return number
     */
    public function cartValue( \Shop\Models\Carts $cart )
    {
        $value = $this->balance();
        
        if ($cart->total() < $value) 
        {
        	$value = $cart->total();
        }
        
        return $value;
    }
}