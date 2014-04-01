<?php
namespace Shop\Models;

class Checkout extends \Dsc\Singleton
{
    protected $__orderCreated = false;
    protected $__cart = null;             // \Shop\Models\Carts object
    protected $__paymentData = array();
    protected $__order = null;             // \Shop\Models\Carts object
    
    /**
     * Process a checkout and mark it as completed if successful.
     * Processing a checkout means creating an order object.  
     * 
     * @return \Shop\Models\Checkout
     */
    public function createOrder()
    {
        // TODO Convert the cart to an Order object
        $order = \Shop\Models\Order::fromCart( $this->cart() );
        
        // TODO Add payment details if applicable

        return $this;
    }
    
    /**
     * Determine if the checkout has been completed.
     * Being completed means an order has been created for the checkout(cart+payment) 
     * 
     * @return boolean
     */
    public function orderCreated()
    {
        return true === $this->__orderCreated;
    }
    
    /**
     * Mark the checkout as complete, which means the order has been created for the cart+payment
     */
    public function setOrderCreated()
    {
        // TODO before and after Events?
        
        $this->__orderCreated = true;
        
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
    public function addPaymentData(array $data)
    {
    	$this->__paymentData = $data;
    	
    	return $this;
    }
    
    /**
     * Get the payment data used for checkout
     */
    public function paymentData()
    {
        // TODO Handle when $this->__payment is empty.  throw error?
    
        return $this->__paymentData;
    }
}