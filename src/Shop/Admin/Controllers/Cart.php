<?php
namespace Shop\Admin\Controllers;

class Cart extends \Admin\Controllers\BaseAuth
{
    use\Dsc\Traits\Controllers\CrudItemCollection;

    protected $list_route = '/admin/shop/carts';

    protected $create_item_route = '/admin/shop/cart/create';

    protected $get_item_route = '/admin/shop/cart/read/{id}';

    protected $edit_item_route = '/admin/shop/cart/edit/{id}';

    protected function getModel()
    {
        $model = new \Shop\Models\Carts();
        return $model;
    }

    protected function getItem()
    {
        $f3 = \Base::instance();
        $id = $this->inputfilter->clean($f3->get('PARAMS.id'), 'alnum');
        $model = $this->getModel()->setState('filter.id', $id);
        
        try
        {
            $item = $model->getItem();
        }
        catch (\Exception $e)
        {
            \Dsc\System::instance()->addMessage("Invalid Item: " . $e->getMessage(), 'error');
            $f3->reroute($this->list_route);
            return;
        }
        
        return $item;
    }

    protected function displayCreate()
    {
        /**
         * Nulled deliberately
         */
    }

    protected function displayRead()
    {
        $flash = \Dsc\Flash::instance();
        
        $this->app->set('meta.title', 'View Cart | Shop');
        
        $view = $this->theme;
        $view->event = $view->trigger('onDisplayShopCartsRead', array(
            'item' => $this->getItem(),
            'tabs' => array(),
            'content' => array()
        ));
        echo $view->render('Shop/Admin/Views::carts/read.php');
    }

    protected function displayEdit()
    {
        /**
         * Nulled deliberately
         */
    }

    /**
     * Display the form for creating an order from an existing cart by cart_id
     * Step 1 of 2, Shipping
     */
    public function createOrderShipping()
    {
        $cart_id = $this->inputfilter->clean($this->app->get('PARAMS.id'), 'alnum');
        
        $flash = \Dsc\Flash::instance();
        
        $this->app->set('meta.title', 'Shipping | Create Order from Cart | Shop');
        
        $item = $this->getItem();
        
        if (empty($item->id)) 
        {
            \Dsc\System::addMessage('Invalid Cart ID', 'error');
            $this->app->reroute('/admin/shop/carts');
        }
        
        $this->app->set('cart', $item);
        
        $view = $this->theme;
        $view->event = $view->trigger('onShopCreateOrderShipping', array(
            'item' => $item,
            'tabs' => array(),
            'content' => array()
        ));
        echo $view->render('Shop/Admin/Views::carts/create_order.php');
    }

    /**
     * Display the form for creating an order from an existing cart by cart_id
     * Step 2 of 2, Payment -- perform a normal authorization/payment-request
     */
    public function createOrderPayment()
    {
        $cart_id = $this->inputfilter->clean($this->app->get('PARAMS.id'), 'alnum');
        
        $flash = \Dsc\Flash::instance();
        
        $this->app->set('meta.title', 'Payment | Create Order from Cart | Shop');
        
        $item = $this->getItem();
        if (empty($item->id))
        {
            \Dsc\System::addMessage('Invalid Cart ID', 'error');
            $this->app->reroute('/admin/shop/carts');
        }
                
        $this->app->set('cart', $item);
        
        $this->app->set('manual_payment', false);
        
        $view = $this->theme;
        $view->event = $view->trigger('onShopCreateOrderPayment', array(
            'item' => $item,
            'tabs' => array(),
            'content' => array()
        ));
        echo $view->render('Shop/Admin/Views::carts/create_order_payment.php');
    }
    
    /**
     * Display the form for creating an order from an existing cart by cart_id
     * Step 2 of 2, Payment -- enter authorization/payment-data manually
     */
    public function createOrderPaymentManually()
    {
        $cart_id = $this->inputfilter->clean($this->app->get('PARAMS.id'), 'alnum');
    
        $flash = \Dsc\Flash::instance();
    
        $this->app->set('meta.title', 'Payment | Create Order from Cart | Shop');
    
        $item = $this->getItem();
        $this->app->set('cart', $item);
        
        $this->app->set('manual_payment', true);
        
        $view = $this->theme;
        $view->event = $view->trigger('onShopCreateOrderPaymentManually', array(
            'item' => $item,
            'tabs' => array(),
            'content' => array()
        ));
        echo $view->render('Shop/Admin/Views::carts/create_order_payment.php');
    }

    /**
     * POST target for creating an order from an existing cart_id
     */
    public function createOrder()
    {
        $cart_id = $this->inputfilter->clean($this->app->get('PARAMS.id'), 'alnum');
        
        $redirect = '/admin/shop/carts';
        if ($custom_redirect = \Dsc\System::instance()->get('session')->get('shop.checkout.redirect'))
        {
            $redirect = $custom_redirect;
        }
        \Dsc\System::instance()->get('session')->set('shop.checkout.redirect', null);
        
        // -----------------------------------------------------
        // Start: validation
        // -----------------------------------------------------
        try
        {
            $cart = $this->getItem();
            if (empty($cart->id))
            {
                throw new \Exception('Invalid Cart ID');
            }
        }
        catch (\Exception $e)
        {
            if ($this->app->get('AJAX'))
            {
                return $this->outputJson($this->getJsonResponse(array(
                    'result' => false
                )));
            }
            else
            {
                \Dsc\System::addMessage($e->getMessage(), 'error');
                $this->app->reroute($redirect);
                return;
            }
        }
        // -----------------------------------------------------
        // End: validation
        // -----------------------------------------------------
        
        // -----------------------------------------------------
        // Start: create the order
        // -----------------------------------------------------
        try
        {
            // Update the cart with checkout data from the form
            $checkout_inputs = $this->input->get( 'checkout', array(), 'array' );
            if (!empty($checkout_inputs['billing_address']['same_as_shipping'])) {
                $checkout_inputs['billing_address']['same_as_shipping'] = true;
            } else {
                $checkout_inputs['billing_address']['same_as_shipping'] = false;
            }
            $cart_checkout = array_merge( (array) $cart->{'checkout'}, $checkout_inputs );
            $cart->checkout = $cart_checkout;
            $cart->save();
            
            // Bind the cart and payment data to the checkout model
            $checkout = \Shop\Models\Checkout::instance();
            $checkout->addCart($cart)->addPaymentData($this->app->get('POST'));
            
            $order_inputs = $this->input->get( 'order', array(), 'array' );
            $checkout->order()->bind($order_inputs);
        }
        catch (\Exception $e)
        {
            if ($this->app->get('AJAX'))
            {
                return $this->outputJson($this->getJsonResponse(array(
                    'result' => false
                )));
            }
            else
            {
                \Dsc\System::addMessage($e->getMessage(), 'error');
                $this->app->reroute($redirect);
                return;
            }
        }
        
        try
        {
            $checkout->acceptOrder();
        }
        catch (\Exception $e)
        {
            $checkout->setError($e->getMessage());
        }
        
        if (!$checkout->orderAccepted() || !empty($checkout->getErrors()))
        {
            \Dsc\System::addMessage('Checkout could not be completed.', 'error');
            
            // Add the errors to the stack and redirect
            foreach ($checkout->getErrors() as $exception)
            {
                \Dsc\System::addMessage($exception->getMessage(), 'error');
            }
            
            // redirect to the ./shop/checkout/payment page unless a failure redirect has been set in the session (site.shop.checkout.redirect.fail)
            $redirect = '/admin/shop/carts';
            if ($custom_redirect = \Dsc\System::instance()->get('session')->get('shop.checkout.redirect_fail'))
            {
                $redirect = $custom_redirect;
            }
            
            \Dsc\System::instance()->get('session')->set('shop.checkout.redirect_fail', null);
            $this->app->reroute($redirect);
            
            return;
        }      
        
        // the order WAS accepted
        // Fire an afterShopCheckout event
        try {
            $event_after = \Dsc\System::instance()->trigger('afterShopCheckout', array('checkout' => $checkout));
        } catch (\Exception $e) {
            \Dsc\System::addMessage( $e->getMessage(), 'warning' );
        }        
        
        // -----------------------------------------------------
        // End: create the order
        // -----------------------------------------------------
        
        if ($this->app->get('AJAX'))
        {
            return $this->outputJson($this->getJsonResponse(array(
                'result' => true
            )));
        }
        else
        {
            \Dsc\System::addMessage('Order created');
            $this->app->reroute($redirect);
        }
    }

    /**
     * Adds POST data to the user's cart.
     *
     * Typically the target of checkout forms, allowing custom workflows.
     * Responds according to request method.
     * Validates only the provided data, not the cart.
     */
    public function checkoutUpdate()
    {
        // TODO If the select data doesn't validate, return an error message while redirecting back to referring page (if http request)
        // or outputting json_encoded response with array of errrors
        $custom_redirect = \Dsc\System::instance()->get('session')->get('shop.checkout.redirect');
        $redirect = $custom_redirect ? $custom_redirect : '/admin/shop/carts';
        
        try
        {
            $id = $this->inputfilter->clean($this->app->get('PARAMS.id'), 'alnum');
            $cart = (new \Shop\Models\Carts())->setState('filter.id', $id)->getItem();
            
            if (empty($cart->id))
            {
                throw new \Exception('Invalid Cart ID');
            }
            
            // Do the selective update, saving the data to the Cart if it validates
            $checkout = $this->input->get('checkout', array(), 'array');
            $cart_checkout = array_merge((array) $cart->{'checkout'}, $checkout);
            $cart->set('checkout', $cart_checkout);
            
            $cart->save();
            
            if ($this->app->get('AJAX'))
            {
                return $this->outputJson($this->getJsonResponse(array(
                    'result' => true
                )));
            }
            else
            {
                $this->session->set('shop.checkout.redirect', null);
                $this->app->reroute($redirect);
            }
        }
        catch (\Exception $e)
        {
            
            if ($this->app->get('AJAX'))
            {
                return $this->outputJson($this->getJsonResponse(array(
                    'result' => false,
                    'message' => $e->getMessage()
                )));
            }
            else
            {
                \Dsc\System::addMessage($e->getMessage(), 'error');
                $this->session->set('shop.checkout.redirect', null);
                $this->app->reroute($redirect);
            }
        }
        
        return;
    }

    /**
     * Gets valid shipping methods for the cart
     */
    public function shippingMethods()
    {
        try
        {
            $cart = $this->getItem();
            if (empty($cart->id))
            {
                throw new \Exception('Invalid Cart ID');
            }
            
            \Base::instance()->set('cart', $cart);
            
            echo $this->theme->renderView('Shop/Admin/Views::carts/shipping_methods.php');
        }
        catch (\Exception $e)
        {
            echo $e->getMessage();
        }
    }
    
    /**
     * Gets valid payment methods for the cart
     */
    public function paymentMethods()
    {
        try
        {
            $cart = $this->getItem();
            if (empty($cart->id))
            {
                throw new \Exception('Invalid Cart ID');
            }
        
            \Base::instance()->set('cart', $cart);
        
            echo $this->theme->renderView('Shop/Admin/Views::carts/payment_methods.php');
        }
        catch (\Exception $e)
        {
            echo $e->getMessage();
        }
    }    

    /**
     */
    public function addCoupon()
    {
        $redirect = '/admin/shop/carts';
        if ($custom_redirect = \Dsc\System::instance()->get('session')->get('addcoupon.redirect'))
        {
            $redirect = $custom_redirect;
        }
        \Dsc\System::instance()->get('session')->set('addcoupon.redirect', null);
        
        // -----------------------------------------------------
        // Start: validation
        // -----------------------------------------------------
        $coupon_code = trim(strtolower($this->input->get('coupon_code', null, 'string')));
        
        try
        {
            $cart = $this->getItem();
            if (empty($cart->id))
            {
                throw new \Exception('Invalid Cart ID');
            }
            
            if (empty($coupon_code))
            {
                throw new \Exception('Please provide a coupon code');
            }
            
            // load the coupon, and if it exists, try to add it to the cart
            $coupon = (new \Shop\Models\Coupons())->setState('filter.code', $coupon_code)->getItem();
            
            if (empty($coupon->id))
            {
                throw new \Exception('Invalid Coupon Code');
            }
            
            // are we using a generated code? or a primary code?
            if (strtolower($coupon->code) != $coupon_code)
            {
                $coupon->generated_code = $coupon_code;
            }
        }
        catch (\Exception $e)
        {
            if ($this->app->get('AJAX'))
            {
                return $this->outputJson($this->getJsonResponse(array(
                    'result' => false
                )));
            }
            else
            {
                \Dsc\System::addMessage($e->getMessage(), 'error');
                $this->app->reroute($redirect);
                return;
            }
        }
        // -----------------------------------------------------
        // End: validation
        // -----------------------------------------------------
        
        // -----------------------------------------------------
        // Start: add the item
        // -----------------------------------------------------
        try
        {
            $cart->addCoupon($coupon);
        }
        catch (\Exception $e)
        {
            if ($this->app->get('AJAX'))
            {
                return $this->outputJson($this->getJsonResponse(array(
                    'result' => false
                )));
            }
            else
            {
                \Dsc\System::addMessage('Discount not applied.', 'error');
                \Dsc\System::addMessage($e->getMessage(), 'error');
                $this->app->reroute($redirect);
                return;
            }
        }
        // -----------------------------------------------------
        // End: add the item
        // -----------------------------------------------------
        
        if ($this->app->get('AJAX'))
        {
            return $this->outputJson($this->getJsonResponse(array(
                'result' => true
            )));
        }
        else
        {
            \Dsc\System::addMessage('Added coupon: ' . $coupon_code);
            $this->app->reroute($redirect);
        }
    }

    /**
     * Remove an item from the cart
     */
    public function removeCoupon()
    {
        $redirect = '/admin/shop/carts';
        if ($custom_redirect = \Dsc\System::instance()->get('session')->get('removecoupon.redirect'))
        {
            $redirect = $custom_redirect;
        }
        \Dsc\System::instance()->get('session')->set('removecoupon.redirect', null);
        
        // -----------------------------------------------------
        // Start: validation
        // -----------------------------------------------------
        // validate the POST values
        if (!$code = $this->inputfilter->clean($this->app->get('PARAMS.code'), 'string'))
        {
            // if validation fails, respond appropriately
            if ($this->app->get('AJAX'))
            {
                return $this->outputJson($this->getJsonResponse(array(
                    'result' => false
                )));
            }
            else
            {
                \Dsc\System::addMessage('Invalid Coupon Code', 'error');
                $this->app->reroute($redirect);
            }
        }
        
        // -----------------------------------------------------
        // End: validation
        // -----------------------------------------------------
        
        // remove the item
        try
        {
            $cart = $this->getItem();
            if (empty($cart->id))
            {
                throw new \Exception('Invalid Cart ID');
            }
            
            $cart->removeCoupon($code);
            
            if ($this->app->get('AJAX'))
            {
                return $this->outputJson($this->getJsonResponse(array(
                    'result' => true
                )));
            }
            else
            {
                \Dsc\System::addMessage('Coupon removed from cart');
                $this->app->reroute($redirect);
            }
        }
        catch (\Exception $e)
        {
            if ($this->app->get('AJAX'))
            {
                return $this->outputJson($this->getJsonResponse(array(
                    'result' => false
                )));
            }
            else
            {
                \Dsc\System::addMessage($e->getMessage(), 'error');
                $this->app->reroute($redirect);
            }
        }
    }

    /**
     */
    public function addGiftCard()
    {
        $redirect = '/admin/shop/carts';
        if ($custom_redirect = \Dsc\System::instance()->get('session')->get('addgiftcard.redirect'))
        {
            $redirect = $custom_redirect;
        }
        \Dsc\System::instance()->get('session')->set('addgiftcard.redirect', null);
        
        // -----------------------------------------------------
        // Start: validation
        // -----------------------------------------------------
        $giftcard_code = trim($this->input->get('giftcard_code', null, 'alnum'));
        
        try
        {
            $cart = $this->getItem();
            if (empty($cart->id))
            {
                throw new \Exception('Invalid Cart ID');
            }
            
            if (empty($giftcard_code))
            {
                throw new \Exception('Please provide a gift card code');
            }
            
            $regex = '/^[0-9a-z]{24}$/';
            if (!preg_match($regex, (string) $giftcard_code))
            {
                throw new \Exception('Please enter a valid gift card code');
            }
            
            // load the giftcard, and if it exists, try to add it to the cart
            $giftcard = (new \Shop\Models\OrderedGiftCards())->setState('filter.id', $giftcard_code)->getItem();
            if (empty($giftcard->id))
            {
                throw new \Exception('Invalid Gift Card Code');
            }
        }
        catch (\Exception $e)
        {
            if ($this->app->get('AJAX'))
            {
                return $this->outputJson($this->getJsonResponse(array(
                    'result' => false
                )));
            }
            else
            {
                \Dsc\System::addMessage($e->getMessage(), 'error');
                $this->app->reroute($redirect);
                return;
            }
        }
        // -----------------------------------------------------
        // End: validation
        // -----------------------------------------------------
        
        // -----------------------------------------------------
        // Start: add the item
        // -----------------------------------------------------
        try
        {
            $cart->addGiftcard($giftcard);
        }
        catch (\Exception $e)
        {
            if ($this->app->get('AJAX'))
            {
                return $this->outputJson($this->getJsonResponse(array(
                    'result' => false
                )));
            }
            else
            {
                \Dsc\System::addMessage('Gift card not added to cart', 'error');
                \Dsc\System::addMessage($e->getMessage(), 'error');
                $this->app->reroute($redirect);
                return;
            }
        }
        // -----------------------------------------------------
        // End: add the item
        // -----------------------------------------------------
        
        if ($this->app->get('AJAX'))
        {
            return $this->outputJson($this->getJsonResponse(array(
                'result' => true
            )));
        }
        else
        {
            \Dsc\System::addMessage('Added Gift Card');
            $this->app->reroute($redirect);
        }
    }

    /**
     * Remove an item from the cart
     */
    public function removeGiftCard()
    {
        $redirect = '/admin/shop/carts';
        if ($custom_redirect = \Dsc\System::instance()->get('session')->get('removegiftcard.redirect'))
        {
            $redirect = $custom_redirect;
        }
        \Dsc\System::instance()->get('session')->set('removegiftcard.redirect', null);
        
        // -----------------------------------------------------
        // Start: validation
        // -----------------------------------------------------
        // validate the POST values
        if (!$code = $this->inputfilter->clean($this->app->get('PARAMS.code'), 'alnum'))
        {
            // if validation fails, respond appropriately
            if ($this->app->get('AJAX'))
            {
                return $this->outputJson($this->getJsonResponse(array(
                    'result' => false
                )));
            }
            else
            {
                \Dsc\System::addMessage('Invalid Gift Card', 'error');
                $this->app->reroute($redirect);
            }
        }
        
        // -----------------------------------------------------
        // End: validation
        // -----------------------------------------------------

        // remove the item
        try
        {
            $cart = $this->getItem();
            if (empty($cart->id))
            {
                throw new \Exception('Invalid Cart ID');
            }
            
            $cart->removeGiftCard($code);
            
            if ($this->app->get('AJAX'))
            {
                return $this->outputJson($this->getJsonResponse(array(
                    'result' => true
                )));
            }
            else
            {
                \Dsc\System::addMessage('Gift card removed from cart');
                $this->app->reroute($redirect);
            }
        }
        catch (\Exception $e)
        {
            if ($this->app->get('AJAX'))
            {
                return $this->outputJson($this->getJsonResponse(array(
                    'result' => false
                )));
            }
            else
            {
                \Dsc\System::addMessage($e->getMessage());
                $this->app->reroute($redirect);
            }
        }
    }
}