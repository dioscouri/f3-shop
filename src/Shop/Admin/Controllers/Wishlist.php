<?php
namespace Shop\Admin\Controllers;

class Wishlist extends \Admin\Controllers\BaseAuth
{
    use\Dsc\Traits\Controllers\CrudItemCollection;

    protected $list_route = '/admin/shop/wishlists';

    protected $create_item_route = '/admin/shop/wishlist/create';

    protected $get_item_route = '/admin/shop/wishlist/read/{id}';

    protected $edit_item_route = '/admin/shop/wishlist/edit/{id}';

    protected function getModel()
    {
        $model = new \Shop\Models\Wishlists();
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
        
        $this->app->set('meta.title', 'View Wishlist | Shop');
        
        $view = $this->theme;
        $view->event = $view->trigger('onDisplayShopWishlistsRead', array(
            'item' => $this->getItem(),
            'tabs' => array(),
            'content' => array()
        ));
        echo $view->render('Shop/Admin/Views::wishlists/read.php');
    }

    protected function displayEdit()
    {
        /**
         * Nulled deliberately
         */
    }

    /**
     * Display the form for creating an order from an existing wishlist by wishlist_id
     * Step 1 of 2, Shipping
     */
    public function createOrderShipping()
    {
        $wishlist_id = $this->inputfilter->clean($this->app->get('PARAMS.id'), 'alnum');
        
        $flash = \Dsc\Flash::instance();
        
        $this->app->set('meta.title', 'Shipping | Create Order from Wishlist | Shop');
        
        $item = $this->getItem();
        
        if (empty($item->id)) 
        {
            \Dsc\System::addMessage('Invalid Wishlist ID', 'error');
            $this->app->reroute('/admin/shop/wishlists');
        }
        
        $this->app->set('wishlist', $item);
        
        $view = $this->theme;
        $view->event = $view->trigger('onShopCreateOrderShipping', array(
            'item' => $item,
            'tabs' => array(),
            'content' => array()
        ));
        echo $view->render('Shop/Admin/Views::wishlists/create_order.php');
    }

    /**
     * Display the form for creating an order from an existing wishlist by wishlist_id
     * Step 2 of 2, Payment -- perform a normal authorization/payment-request
     */
    public function createOrderPayment()
    {
        $wishlist_id = $this->inputfilter->clean($this->app->get('PARAMS.id'), 'alnum');
        
        $flash = \Dsc\Flash::instance();
        
        $this->app->set('meta.title', 'Payment | Create Order from Wishlist | Shop');
        
        $item = $this->getItem();
        if (empty($item->id))
        {
            \Dsc\System::addMessage('Invalid Wishlist ID', 'error');
            $this->app->reroute('/admin/shop/wishlists');
        }
                
        $this->app->set('wishlist', $item);
        
        $this->app->set('manual_payment', false);
        
        $view = $this->theme;
        $view->event = $view->trigger('onShopCreateOrderPayment', array(
            'item' => $item,
            'tabs' => array(),
            'content' => array()
        ));
        echo $view->render('Shop/Admin/Views::wishlists/create_order_payment.php');
    }
    
    /**
     * Display the form for creating an order from an existing wishlist by wishlist_id
     * Step 2 of 2, Payment -- enter authorization/payment-data manually
     */
    public function createOrderPaymentManually()
    {
        $wishlist_id = $this->inputfilter->clean($this->app->get('PARAMS.id'), 'alnum');
    
        $flash = \Dsc\Flash::instance();
    
        $this->app->set('meta.title', 'Payment | Create Order from Wishlist | Shop');
    
        $item = $this->getItem();
        $this->app->set('wishlist', $item);
        
        $this->app->set('manual_payment', true);
        
        $view = $this->theme;
        $view->event = $view->trigger('onShopCreateOrderPaymentManually', array(
            'item' => $item,
            'tabs' => array(),
            'content' => array()
        ));
        echo $view->render('Shop/Admin/Views::wishlists/create_order_payment.php');
    }

    /**
     * POST target for creating an order from an existing wishlist_id
     */
    public function createOrder()
    {
        $wishlist_id = $this->inputfilter->clean($this->app->get('PARAMS.id'), 'alnum');
        
        $redirect = '/admin/shop/wishlists';
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
            $wishlist = $this->getItem();
            if (empty($wishlist->id))
            {
                throw new \Exception('Invalid Wishlist ID');
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
            // Update the wishlist with checkout data from the form
            $checkout_inputs = $this->input->get( 'checkout', array(), 'array' );
            if (!empty($checkout_inputs['billing_address']['same_as_shipping'])) {
                $checkout_inputs['billing_address']['same_as_shipping'] = true;
            } else {
                $checkout_inputs['billing_address']['same_as_shipping'] = false;
            }
            $wishlist_checkout = array_merge( (array) $wishlist->{'checkout'}, $checkout_inputs );
            $wishlist->checkout = $wishlist_checkout;
            $wishlist->save();
            
            // Bind the wishlist and payment data to the checkout model
            $checkout = \Shop\Models\Checkout::instance();
            $checkout->addWishlist($wishlist)->addPaymentData($this->app->get('POST'));
            
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
            $redirect = '/admin/shop/wishlists';
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
     * Adds POST data to the user's wishlist.
     *
     * Typically the target of checkout forms, allowing custom workflows.
     * Responds according to request method.
     * Validates only the provided data, not the wishlist.
     */
    public function checkoutUpdate()
    {
        // TODO If the select data doesn't validate, return an error message while redirecting back to referring page (if http request)
        // or outputting json_encoded response with array of errrors
        $custom_redirect = \Dsc\System::instance()->get('session')->get('shop.checkout.redirect');
        $redirect = $custom_redirect ? $custom_redirect : '/admin/shop/wishlists';
        
        try
        {
            $id = $this->inputfilter->clean($this->app->get('PARAMS.id'), 'alnum');
            $wishlist = (new \Shop\Models\Wishlists())->setState('filter.id', $id)->getItem();
            
            if (empty($wishlist->id))
            {
                throw new \Exception('Invalid Wishlist ID');
            }
            
            // Do the selective update, saving the data to the Wishlist if it validates
            $checkout = $this->input->get('checkout', array(), 'array');
            $wishlist_checkout = array_merge((array) $wishlist->{'checkout'}, $checkout);
            $wishlist->set('checkout', $wishlist_checkout);
            
            $wishlist->save();
            
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
     * Gets valid shipping methods for the wishlist
     */
    public function shippingMethods()
    {
        try
        {
            $wishlist = $this->getItem();
            if (empty($wishlist->id))
            {
                throw new \Exception('Invalid Wishlist ID');
            }
            
            \Base::instance()->set('wishlist', $wishlist);
            
            echo $this->theme->renderView('Shop/Admin/Views::wishlists/shipping_methods.php');
        }
        catch (\Exception $e)
        {
            echo $e->getMessage();
        }
    }
    
    /**
     * Gets valid payment methods for the wishlist
     */
    public function paymentMethods()
    {
        try
        {
            $wishlist = $this->getItem();
            if (empty($wishlist->id))
            {
                throw new \Exception('Invalid Wishlist ID');
            }
        
            \Base::instance()->set('wishlist', $wishlist);
        
            echo $this->theme->renderView('Shop/Admin/Views::wishlists/payment_methods.php');
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
        $redirect = '/admin/shop/wishlists';
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
            $wishlist = $this->getItem();
            if (empty($wishlist->id))
            {
                throw new \Exception('Invalid Wishlist ID');
            }
            
            if (empty($coupon_code))
            {
                throw new \Exception('Please provide a coupon code');
            }
            
            // load the coupon, and if it exists, try to add it to the wishlist
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
            $wishlist->addCoupon($coupon);
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
     * Remove an item from the wishlist
     */
    public function removeCoupon()
    {
        $redirect = '/admin/shop/wishlists';
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
            $wishlist = $this->getItem();
            if (empty($wishlist->id))
            {
                throw new \Exception('Invalid Wishlist ID');
            }
            
            $wishlist->removeCoupon($code);
            
            if ($this->app->get('AJAX'))
            {
                return $this->outputJson($this->getJsonResponse(array(
                    'result' => true
                )));
            }
            else
            {
                \Dsc\System::addMessage('Coupon removed from wishlist');
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
        $redirect = '/admin/shop/wishlists';
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
            $wishlist = $this->getItem();
            if (empty($wishlist->id))
            {
                throw new \Exception('Invalid Wishlist ID');
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
            
            // load the giftcard, and if it exists, try to add it to the wishlist
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
            $wishlist->addGiftcard($giftcard);
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
                \Dsc\System::addMessage('Gift card not added to wishlist', 'error');
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
     * Remove an item from the wishlist
     */
    public function removeGiftCard()
    {
        $redirect = '/admin/shop/wishlists';
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
            $wishlist = $this->getItem();
            if (empty($wishlist->id))
            {
                throw new \Exception('Invalid Wishlist ID');
            }
            
            $wishlist->removeGiftCard($code);
            
            if ($this->app->get('AJAX'))
            {
                return $this->outputJson($this->getJsonResponse(array(
                    'result' => true
                )));
            }
            else
            {
                \Dsc\System::addMessage('Gift card removed from wishlist');
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