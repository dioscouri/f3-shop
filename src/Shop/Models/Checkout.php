<?php
namespace Shop\Models;

class Checkout extends \Dsc\Singleton
{
    protected $__completed = false;
    protected $__cart = null;             // \Shop\Models\Carts object
    protected $__payment = array();
    
    /**
     * Process a checkout and mark it as completed if success.
     * Processing a checkout means creating an order object  
     * 
     * @return \Shop\Models\Checkout
     */
    public function process()
    {
        return $this;
    }
    
    /**
     * Determine if the checkout has been completed
     * 
     * @return boolean
     */
    public function isCompleted()
    {
        return true === $this->__completed;
    }
    
    /**
     * Mark the checkout as complete
     */
    public function setCompleted()
    {
        $this->__completed = true;
        
        return $this;
    }
    
    /**
     * Add cart data to the checkout model
     * 
     * @param \Shop\Models\Carts $cart
     * @return \Shop\Models\Checkout
     */
    public function addCart(\Shop\Models\Carts $cart) 
    {
        // TODO set model properties from cart
        $this->__cart = $cart;
        
    	return $this;
    }
    
    /**
     * Get the cart used for checkout
     */
    public function cart()
    {
        // TODO Handle when $this->__cart is empty.  throw error?
        
        return $this->__cart;
    }
    
    /**
     * Add payment data to the checkout model
     * 
     * @param array $data
     * @return \Shop\Models\Checkout
     */
    public function addPayment(array $data)
    {
    	$this->__payment = $data;
    	
    	return $this;
    }
    
    /**
     * Get the payment data used for checkout
     */
    public function payment()
    {
        // TODO Handle when $this->__payment is empty.  throw error?
    
        return $this->__payment;
    }
}