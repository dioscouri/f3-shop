<?php 
namespace Shop\Site\Controllers;

class Cart extends \Dsc\Controller 
{
    /**
     * List a user's carts, 
     * e.g. abandoned, closed (ordered), named (wishlists, sorta), and default (current), etc
     * 
     */
    public function index()
    {
        
    }
    
    /**
     * Display a user's cart
     */
    public function read()
    {
        $cart = \Shop\Models\Carts::fetch();
        // Update product fields stored in cart
        foreach ($cart->validateProducts() as $change) {
        	\Dsc\System::addMessage($change);
        }
        \Base::instance()->set('cart', $cart);
        
        $view = \Dsc\System::instance()->get('theme');
        echo $view->renderTheme('Shop/Site/Views::cart/read.php');        
    }
        
    /**
     * Add an item to a cart
     */
    public function add()
    {
        $f3 = \Base::instance();
        
        // -----------------------------------------------------
        // Start: validation
        // -----------------------------------------------------
        // TODO validate the POST values
            // min: variant_id
        $variant_id = $this->input->get('variant_id');            
        
        // TODO load the product
        try {
            $product = (new \Shop\Models\Variants)->getById($variant_id);
        } catch (\Exception $e) {
            // TODO respond appropriately with failure message
            // return;
        }
        
        // TODO get the appropriate price using the user's groups, quantity added, and date
        // TODO check the quantity restrictions
        
        // TODO if validation fails, respond appropriately
        if ($f3->get('AJAX')) {
        
        } else {
            
        }        
        // -----------------------------------------------------
        // End: validation
        // -----------------------------------------------------
        
        // get the current user's cart, either based on session_id (visitor) or user_id (logged-in)
        $cart = \Shop\Models\Carts::fetch();
        
        // add the item
        try {
            $cart->addItem( $variant_id, $product, $f3->get('POST') );
        } catch (\Exception $e) {
        	// TODO respond appropriately with failure message
        	// return;
        }
        
        //echo \Dsc\Debug::dump( $cart );
                
        // TODO respond appropriately
            // ajax?  send response object
            // otherwise redirect to cart
        
        if ($f3->get('AJAX')) {

        } else {
            \Dsc\System::addMessage('Item added to cart');
        	$f3->reroute('/shop/cart');
        }
    }
    
    /**
     * Remove an item from the cart
     */
    public function remove()
    {
        $f3 = \Base::instance();
        
        // -----------------------------------------------------
        // Start: validation
        // -----------------------------------------------------
        // TODO validate the POST values
            // min: cartitem_hash
        $cartitem_hash = $this->inputfilter->clean( $f3->get('PARAMS.cartitem_hash'), 'cmd' );
        
        // TODO if validation fails, respond appropriately
        if ($f3->get('AJAX')) {
        
        } else {
        
        }
        // -----------------------------------------------------
        // End: validation
        // -----------------------------------------------------
        
        // get the current user's cart, either based on session_id (visitor) or user_id (logged-in)
        $cart = \Shop\Models\Carts::fetch();
        
        // remove the item
        try {
            $cart->removeItem( $cartitem_hash );
        } catch (\Exception $e) {
            // TODO respond appropriately with failure message
            // return;
        }
        
        //echo \Dsc\Debug::dump( $cart );
        
        // TODO respond appropriately
            // ajax?  send response object
            // otherwise redirect to cart
        
        if ($f3->get('AJAX')) {
        
        } else {
            \Dsc\System::addMessage('Item removed from cart');
            $f3->reroute('/shop/cart');
        }
    }
    
    /**
     * Update a cart
     */
    public function updateQuantities()
    {
        $f3 = \Base::instance();
        
        // get the current user's cart, either based on session_id (visitor) or user_id (logged-in)
        $cart = \Shop\Models\Carts::fetch();
        
        $quantities = $this->input->get( 'quantities', array(), 'array' );
        foreach ($cart->items as $item) 
        {
            if (isset($quantities[$item['hash']])) 
            {
                $new_quantity = (int) $quantities[$item['hash']];
                if ($new_quantity < 0) {
                    $cart->removeItem( $item['hash'] );
                } else {
                    $cart->updateItemQuantity( $item['hash'], $new_quantity );
                }
            }
        }
        
        // TODO respond appropriately
            // ajax?  send response object
            // otherwise redirect to cart
        
        if ($f3->get('AJAX')) {
        
        } else {
            \Dsc\System::addMessage('Quantities updated');
            $f3->reroute('/shop/cart');
        }
        
    }    
}