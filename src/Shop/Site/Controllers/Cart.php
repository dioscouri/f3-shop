<?php 
namespace Shop\Site\Controllers;

class Cart extends \Dsc\Controller 
{
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
        $cart->applyCredit();
        
        \Base::instance()->set('cart', $cart);
        
        $this->app->set('meta.title', 'Shopping Cart');
        
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
        $variant_id = $this->input->get('variant_id');            
        
        // load the product
        try {
            $product = (new \Shop\Models\Variants)->getById($variant_id);
        } catch (\Exception $e) {
            if ($f3->get('AJAX')) {
                return $this->outputJson( $this->getJsonResponse( array(
                    'result'=>false
                ) ) );
            } else {
                \Dsc\System::addMessage('Item not added to cart - Invalid product', 'error');
                $f3->reroute('/shop/cart');
                return;
            }
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
            if ($f3->get('AJAX')) {
                return $this->outputJson( $this->getJsonResponse( array(
                    'result'=>false
                ) ) );
            } else {
                \Dsc\System::addMessage('Item not added to cart', 'error');
                \Dsc\System::addMessage($e->getMessage(), 'error');
                $f3->reroute('/shop/cart');
                return;
            }
        }

        if ($f3->get('AJAX')) {
            return $this->outputJson( $this->getJsonResponse( array(
                'result'=>true
            ) ) );
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
            return $this->outputJson( $this->getJsonResponse( array(
                'result'=>true
            ) ) );        
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
            return $this->outputJson( $this->getJsonResponse( array(
                'result'=>true
            ) ) );        
        } else {
            \Dsc\System::addMessage('Quantities updated');
            $f3->reroute('/shop/cart');
        }
    }

    /**
     * 
     */
    public function addCoupon()
    {
        $f3 = \Base::instance();

        $redirect = '/shop/cart';
        if ($custom_redirect = \Dsc\System::instance()->get( 'session' )->get( 'site.addcoupon.redirect' ))
        {
            $redirect = $custom_redirect;
        }
        \Dsc\System::instance()->get( 'session' )->set( 'site.addcoupon.redirect', null );
        
        // -----------------------------------------------------
        // Start: validation
        // -----------------------------------------------------
        $coupon_code = $this->input->get( 'coupon_code', null, 'string' );
        
        // load the product
        try {
            // load the coupon, and if it exists, try to add it to the cart
            $coupon = (new \Shop\Models\Coupons)->load(array('code'=>$coupon_code));
            if (empty($coupon->id)) 
            {
            	throw new \Exception('Invalid Coupon Code');
            }
                
        } catch (\Exception $e) {
            if ($f3->get('AJAX')) {
                return $this->outputJson( $this->getJsonResponse( array(
                    'result'=>false
                ) ) );
            } else {
                \Dsc\System::addMessage($e->getMessage(), 'error');
                $f3->reroute($redirect);
                return;
            }
        }
        // -----------------------------------------------------
        // End: validation
        // -----------------------------------------------------

        $cart = \Shop\Models\Carts::fetch();
        
        // -----------------------------------------------------
        // Start: add the item
        // -----------------------------------------------------        
        try {
            $cart->addCoupon( $coupon );
        } catch (\Exception $e) {
            if ($f3->get('AJAX')) {
                return $this->outputJson( $this->getJsonResponse( array(
                    'result'=>false
                ) ) );
            } else {
                \Dsc\System::addMessage('Coupon not added to cart', 'error');
                \Dsc\System::addMessage($e->getMessage(), 'error');
                $f3->reroute($redirect);
                return;
            }
        }
        // -----------------------------------------------------
        // End: add the item
        // -----------------------------------------------------                
        
        if ($f3->get('AJAX')) {
            return $this->outputJson( $this->getJsonResponse( array(
                'result'=>true
            ) ) );        
        } else {
            \Dsc\System::addMessage('Added coupon: ' . $coupon_code);
            $f3->reroute($redirect);
        }
    }
    
    /**
     * Remove an item from the cart
     */
    public function removeCoupon()
    {
        $f3 = \Base::instance();
        
        $redirect = '/shop/cart';
        if ($custom_redirect = \Dsc\System::instance()->get( 'session' )->get( 'site.removecoupon.redirect' ))
        {
            $redirect = $custom_redirect;
        }
        \Dsc\System::instance()->get( 'session' )->set( 'site.removecoupon.redirect', null );
            
        // -----------------------------------------------------
        // Start: validation
        // -----------------------------------------------------
        // TODO validate the POST values
        // min: cartitem_hash
        $code = $this->inputfilter->clean( $f3->get('PARAMS.code'), 'string' );
    
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
            $cart->removeCoupon( $code );
        } catch (\Exception $e) {
            // TODO respond appropriately with failure message
            // return;
        }
    
        //echo \Dsc\Debug::dump( $cart );
    
        // TODO respond appropriately
        // ajax?  send response object
        // otherwise redirect to cart
    
        if ($f3->get('AJAX')) {
            return $this->outputJson( $this->getJsonResponse( array(
                'result'=>true
            ) ) );
        } else {
            \Dsc\System::addMessage('Coupon removed from cart');
            $f3->reroute($redirect);
        }
    }
    
    /**
     *
     */
    public function addGiftCard()
    {
        $f3 = \Base::instance();
    
        $redirect = '/shop/cart';
        if ($custom_redirect = \Dsc\System::instance()->get( 'session' )->get( 'site.addgiftcard.redirect' ))
        {
            $redirect = $custom_redirect;
        }
        \Dsc\System::instance()->get( 'session' )->set( 'site.addgiftcard.redirect', null );
    
        // -----------------------------------------------------
        // Start: validation
        // -----------------------------------------------------
        $giftcard_code = $this->input->get( 'giftcard_code', null, 'alnum' );
    
        try {
            // load the giftcard, and if it exists, try to add it to the cart
            $giftcard = (new \Shop\Models\OrderedGiftCards)->load(array('_id'=>new \MongoId($giftcard_code)));
            if (empty($giftcard->id))
            {
                throw new \Exception('Invalid Gift Card Code');
            }
    
        } catch (\Exception $e) {
            if ($f3->get('AJAX')) {
                return $this->outputJson( $this->getJsonResponse( array(
                    'result'=>false
                ) ) );
            } else {
                \Dsc\System::addMessage($e->getMessage(), 'error');
                $f3->reroute($redirect);
                return;
            }
        }
        // -----------------------------------------------------
        // End: validation
        // -----------------------------------------------------
    
        $cart = \Shop\Models\Carts::fetch();
    
        // -----------------------------------------------------
        // Start: add the item
        // -----------------------------------------------------
        try {
            $cart->addGiftcard( $giftcard );
        } catch (\Exception $e) {
            if ($f3->get('AJAX')) {
                return $this->outputJson( $this->getJsonResponse( array(
                    'result'=>false
                ) ) );
            } else {
                \Dsc\System::addMessage('Gift card not added to cart', 'error');
                \Dsc\System::addMessage($e->getMessage(), 'error');
                $f3->reroute($redirect);
                return;
            }
        }
        // -----------------------------------------------------
        // End: add the item
        // -----------------------------------------------------
    
        if ($f3->get('AJAX')) {
            return $this->outputJson( $this->getJsonResponse( array(
                'result'=>true
            ) ) );
        } else {
            \Dsc\System::addMessage('Added Gift Card');
            $f3->reroute($redirect);
        }
    }
    
    /**
     * Remove an item from the cart
     */
    public function removeGiftCard()
    {
        $f3 = \Base::instance();
    
        $redirect = '/shop/cart';
        if ($custom_redirect = \Dsc\System::instance()->get( 'session' )->get( 'site.removegiftcard.redirect' ))
        {
            $redirect = $custom_redirect;
        }
        \Dsc\System::instance()->get( 'session' )->set( 'site.removegiftcard.redirect', null );
    
        // -----------------------------------------------------
        // Start: validation
        // -----------------------------------------------------
        // TODO validate the POST values
        // min: cartitem_hash
        $code = $this->inputfilter->clean( $f3->get('PARAMS.code'), 'alnum' );
    
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
            $cart->removeGiftCard( $code );
        } catch (\Exception $e) {
            // TODO respond appropriately with failure message
            // return;
        }
    
        //echo \Dsc\Debug::dump( $cart );
    
        // TODO respond appropriately
        // ajax?  send response object
        // otherwise redirect to cart
    
        if ($f3->get('AJAX')) {
            return $this->outputJson( $this->getJsonResponse( array(
                'result'=>true
            ) ) );
        } else {
            \Dsc\System::addMessage('Gift card removed from cart');
            $f3->reroute($redirect);
        }
    }
}