<?php 
namespace Shop\Site\Controllers;

class Gateway extends \Shop\Controllers\Gateway 
{
    public function notify()
    {
        
    }

    public function completePurchase()
    {
        $gateway_id = $this->inputfilter->clean( $this->app->get('PARAMS.gateway_id'), 'cmd' );
    
        // 1. Get \Shop\Models\Checkout
        // and the payment data to the checkout model
        $payment_data = (array) $this->app->get('REQUEST');
        $payment_data['payment_method'] = $gateway_id;
        $checkout = \Shop\Models\Checkout::instance();

        // the cart->id is in the $payment_data, so let the PaymentMethod load the cart        
        $cart = \Shop\Models\Carts::fetch();
        $checkout->addCart($cart)->addPaymentData($payment_data);
    
        // 3. validate the payment data against the cart
        try {
            $checkout->validatePayment();
        } catch (\Exception $e) {
    
            // Log this error message
            $order = $checkout->order();
            $order->setError($e->getMessage())
                ->set('errors', $order->getErrors())
                ->fail();

            \Dsc\System::addMessage( 'Checkout could not complete for the following reason:', 'error' );
            \Dsc\System::addMessage( $e->getMessage(), 'error' );
    
            // redirect to the ./shop/checkout/payment page unless a failure redirect has been set in the session (site.shop.checkout.redirect.fail)
            $redirect = '/shop/checkout/payment';
            if ($custom_redirect = \Dsc\System::instance()->get( 'session' )->get( 'site.shop.checkout.redirect.fail' ))
            {
                $redirect = $custom_redirect;
            }
    
            \Dsc\System::instance()->get( 'session' )->set( 'site.shop.checkout.redirect.fail', null );
            $this->app->reroute( $redirect );
    
            return;
    
        }
    
        // 4. since payment was validated, accept the order
        try {
            $checkout->acceptOrder();
        } catch (\Exception $e) {
            $checkout->setError( $e->getMessage() );
        }
    
        // if the order acceptance fails, let the user know
        if (!$checkout->orderAccepted() || !empty($checkout->getErrors()))
        {
            \Dsc\System::addMessage( 'Checkout could not be completed.  Please try again or contact us if you have further difficulty.', 'error' );
    
            // Add the errors to the stack and redirect
            foreach ($checkout->getErrors() as $exception)
            {
                \Dsc\System::addMessage( $exception->getMessage(), 'error' );
            }
    
            // redirect to the ./shop/checkout/payment page unless a failure redirect has been set in the session (site.shop.checkout.redirect.fail)
            $redirect = '/shop/checkout/payment';
            if ($custom_redirect = \Dsc\System::instance()->get( 'session' )->get( 'site.shop.checkout.redirect.fail' ))
            {
                $redirect = $custom_redirect;
            }
    
            \Dsc\System::instance()->get( 'session' )->set( 'site.shop.checkout.redirect.fail', null );
            $this->app->reroute( $redirect );
    
            return;
        }
    
        // if the order acceptance succeeds, trigger completion event
        try {
            // Fire an afterShopCheckout event
            $event_after = \Dsc\System::instance()->trigger('afterShopCheckout', array(
                'checkout' => $checkout
            ));
        } catch (\Exception $e) {
            \Dsc\System::addMessage( $e->getMessage(), 'warning' );
        }
    
        // Redirect to ./shop/checkout/confirmation unless a site.shop.checkout.redirect has been set
        $redirect = '/shop/checkout/confirmation';
        if ($custom_redirect = \Dsc\System::instance()->get( 'session' )->get( 'site.shop.checkout.redirect' ))
        {
            $redirect = $custom_redirect;
        }
    
        \Dsc\System::instance()->get( 'session' )->set( 'site.shop.checkout.redirect', null );
        $this->app->reroute( $redirect );
    
        return;
    }    
        
    public function completeCartPurchase()
    {
        $gateway_id = $this->inputfilter->clean( $this->app->get('PARAMS.gateway_id'), 'cmd' );
        $cart_id = $this->inputfilter->clean( $this->app->get('PARAMS.cart_id'), 'alnum' );
        
        // 1. Verify the cart ID (and update the cart with checkout data from the form?)
        $cart = (new \Shop\Models\Carts)->setState('filter.id', $cart_id)->getItem();
        if (empty($cart->id) || (string) $cart->id != (string) $cart_id) 
        {
            \Dsc\System::addMessage( 'Checkout unable to complete due to an invalid Cart ID.', 'error' );
            
            // redirect to the ./shop/checkout/payment page unless a failure redirect has been set in the session (site.shop.checkout.redirect.fail)
            $redirect = '/shop/checkout/payment';
            if ($custom_redirect = \Dsc\System::instance()->get( 'session' )->get( 'site.shop.checkout.redirect.fail' ))
            {
                $redirect = $custom_redirect;
            }
            
            \Dsc\System::instance()->get( 'session' )->set( 'site.shop.checkout.redirect.fail', null );
            $this->app->reroute( $redirect );
            
            return;            
        }

        // 2. Get \Shop\Models\Checkout
        // and Bind the cart and payment data to the checkout model
        $payment_data = (array) $this->app->get('REQUEST');
        $payment_data['payment_method'] = $gateway_id;
        $checkout = \Shop\Models\Checkout::instance();
        $checkout->addCart($cart)->addPaymentData($payment_data);        
        
        // 3. validate the payment data against the cart
        try {
            $checkout->validatePayment();
        } catch (\Exception $e) {
            
            // Log this error message
            $order = $checkout->order();
            $order->setError($e->getMessage())
                ->set('errors', $order->getErrors())
                ->fail();
                        
            \Dsc\System::addMessage( 'Checkout could not complete for the following reason:', 'error' );
            \Dsc\System::addMessage( $e->getMessage(), 'error' );
            
            // redirect to the ./shop/checkout/payment page unless a failure redirect has been set in the session (site.shop.checkout.redirect.fail)
            $redirect = '/shop/checkout/payment';
            if ($custom_redirect = \Dsc\System::instance()->get( 'session' )->get( 'site.shop.checkout.redirect.fail' ))
            {
                $redirect = $custom_redirect;
            }
            
            \Dsc\System::instance()->get( 'session' )->set( 'site.shop.checkout.redirect.fail', null );
            $this->app->reroute( $redirect );
            
            return;
                        
        }        
        
        // 4. since payment was validated, accept the order
        try {
            $checkout->acceptOrder();
        } catch (\Exception $e) {
            $checkout->setError( $e->getMessage() );
        }
        
        // if the order acceptance fails, let the user know
        if (!$checkout->orderAccepted() || !empty($checkout->getErrors()))
        {
            \Dsc\System::addMessage( 'Checkout could not be completed.  Please try again or contact us if you have further difficulty.', 'error' );
        
            // Add the errors to the stack and redirect
            foreach ($checkout->getErrors() as $exception)
            {
                \Dsc\System::addMessage( $exception->getMessage(), 'error' );
            }
        
            // redirect to the ./shop/checkout/payment page unless a failure redirect has been set in the session (site.shop.checkout.redirect.fail)
            $redirect = '/shop/checkout/payment';
            if ($custom_redirect = \Dsc\System::instance()->get( 'session' )->get( 'site.shop.checkout.redirect.fail' ))
            {
                $redirect = $custom_redirect;
            }
        
            \Dsc\System::instance()->get( 'session' )->set( 'site.shop.checkout.redirect.fail', null );
            $this->app->reroute( $redirect );
        
            return;
        }        
        
        // if the order acceptance succeeds, trigger completion event
        try {
            // Fire an afterShopCheckout event            
            $event_after = \Dsc\System::instance()->trigger('afterShopCheckout', array(
                'checkout' => $checkout
            ));
        } catch (\Exception $e) {
            \Dsc\System::addMessage( $e->getMessage(), 'warning' );
        }
        
        // Redirect to ./shop/checkout/confirmation unless a site.shop.checkout.redirect has been set
        $redirect = '/shop/checkout/confirmation';
        if ($custom_redirect = \Dsc\System::instance()->get( 'session' )->get( 'site.shop.checkout.redirect' ))
        {
            $redirect = $custom_redirect;
        }
        
        \Dsc\System::instance()->get( 'session' )->set( 'site.shop.checkout.redirect', null );
        $this->app->reroute( $redirect );
        
        return;
    }
}