<?php
namespace Shop\Site\Controllers;

class Checkout extends \Dsc\Controller
{

    public function index()
    {
        $cart = \Shop\Models\Carts::fetch();
        // Update product fields stored in cart
        foreach ($cart->validateProducts() as $change) {
        	\Dsc\System::addMessage($change);
        }
        
        $cart->selected_country = $cart->{'checkout.shipping_address.country'} ? $cart->{'checkout.shipping_address.country'} : \Shop\Models\Settings::fetch()->{'country'};
        \Base::instance()->set( 'cart', $cart );
        
        $identity = $this->getIdentity();
        if (empty( $identity->id ))
        {
            $view = \Dsc\System::instance()->get( 'theme' );
            echo $view->render( 'Shop/Site/Views::checkout/identity.php' );
            return;
        }
        
        $view = \Dsc\System::instance()->get( 'theme' );
        echo $view->render( 'Shop/Site/Views::checkout/index.php' );
    }

    /**
     * Displays step 2 (of 2) of the default checkout process
     */
    public function payment()
    {
        $cart = \Shop\Models\Carts::fetch();
        \Base::instance()->set( 'cart', $cart );
        
        $identity = $this->getIdentity();
        if (empty( $identity->id ))
        {
            $view = \Dsc\System::instance()->get( 'theme' );
            echo $view->render( 'Shop/Site/Views::checkout/identity.php' );
            return;
        }
        
        $view = \Dsc\System::instance()->get( 'theme' );
        echo $view->render( 'Shop/Site/Views::checkout/payment.php' );
    }
    
    /**
     * Displays an order confirmation page
     */
    public function confirmation()
    {
        $view = \Dsc\System::instance()->get( 'theme' );
        echo $view->render( 'Shop/Site/Views::checkout/confirmation.php' );
    }

    /**
     * Adds POST data to the user's cart.
     *
     * Typically the target of checkout forms, allowing custom workflows.
     * Responds according to request method.
     * Validates only the provided data, not the cart.
     */
    public function update()
    {
        $cart = \Shop\Models\Carts::fetch();
        
        // TODO Do the selective update, saving the data to the Cart if it validates
        $checkout = $this->input->get( 'checkout', array(), 'array' );
        $cart_checkout = array_merge( (array) $cart->{'checkout'}, $checkout );
        $cart->checkout = $cart_checkout;
        $cart->save();
        
        // TODO If the select data doesn't validate, return an error message while redirecting back to referring page (if http request)
        // or outputting json_encoded response with array of errrors
        
        $f3 = \Base::instance();
        if ($f3->get( 'AJAX' ))
        {
            
            // TODO Update the cart and return a response object with success message and cart
        }
        else
        {
            
            $redirect = '/shop/checkout/payment';
            if ($custom_redirect = \Dsc\System::instance()->get( 'session' )->get( 'site.shop.checkout.redirect' ))
            {
                $redirect = $custom_redirect;
            }
            
            \Dsc\System::instance()->get( 'session' )->set( 'site.shop.checkout.redirect', null );
            \Base::instance()->reroute( $redirect );
        }
        
        return;
    }

    /**
     * Validates a cart for checkout and returns either a "good to go" message
     * or data on why the cart is not ready to be submitted
     */
    public function validate()
    {
    }

    /**
     * Gets valid shipping methods for the cart
     */
    public function shippingMethods()
    {
        $cart = \Shop\Models\Carts::fetch();
        \Base::instance()->set( 'cart', $cart );
        
        $view = \Dsc\System::instance()->get('theme');
        echo $view->renderView('Shop/Site/Views::checkout/shipping_methods.php');
    }
    
    /**
     * Gets valid payment methods for the cart
     */
    public function paymentMethods()
    {
        $cart = \Shop\Models\Carts::fetch();
        \Base::instance()->set( 'cart', $cart );
    
        $view = \Dsc\System::instance()->get('theme');
        echo $view->renderView('Shop/Site/Views::checkout/payment_methods.php');
    }
    
    /**
     * Submits a completed cart checkout processing
     * 
     */
    public function submit()
    {
        $f3 = \Base::instance();
        
        // Get \Shop\Models\Checkout
            // Bind the cart and payment data to the checkout model
        $checkout = \Shop\Models\Checkout::instance();            
        $cart = \Shop\Models\Carts::fetch();
        $checkout->addCart($cart)->addPaymentData($f3->get('POST'));
        
        // Fire a beforeShopCheckout event that allows Listeners to hijack the checkout process
        // Payment processing & authorization could occur at this event, and the Listener would update the checkout object
            // Add the checkout model to the event
        $event = new \Joomla\Event\Event( 'beforeShopCheckout' );
        $event->addArgument('checkout', $checkout);
        
        try {
            $event = \Dsc\System::instance()->getDispatcher()->triggerEvent($event);
        }
        catch (\Exception $e) {
            $checkout->setError( $e->getMessage() );
            $event->setArgument('checkout', $checkout);
        }
        
        $checkout = $event->getArgument('checkout');

        // option 1: ERRORS in checkout from beforeShopCheckout        
        if (!empty($checkout->getErrors())) 
        {
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
            $f3->reroute( $redirect );
            
            return;
        }
        
        // option 2: NO ERROR in checkout from beforeShopCheckout
        
        // If checkout is not completed, do the standard checkout process
        // If checkout was completed by a Listener during the beforeShopCheckout process, skip the standard checkout process and go to the afterShopCheckout event
        if (!$checkout->orderCreated()) 
        {
            // the standard checkout process
            try {
                $checkout->createOrder();
            } catch (\Exception $e) {
                $checkout->setError( $e->getMessage() );
            }
            
            if (!$checkout->orderCreated() || !empty($checkout->getErrors()))
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
                $f3->reroute( $redirect );
            
                return;
            }        
        }
        
        // Fire an afterShopCheckout event
        $event_after = new \Joomla\Event\Event( 'afterShopCheckout' );
        $event_after->addArgument('checkout', $checkout);
        
        try {
            $event_after = \Dsc\System::instance()->getDispatcher()->triggerEvent($event_after);
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
        $f3->reroute( $redirect );
        
        return;
    }
}