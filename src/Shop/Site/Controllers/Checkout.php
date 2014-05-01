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
        \Base::instance()->set( 'cart', $cart );
        
        $identity = $this->getIdentity();
        if (empty( $identity->id ))
        {
            $flash = \Dsc\Flash::instance();
            \Base::instance()->set('flash', $flash );
                        
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
            $flash = \Dsc\Flash::instance();
            \Base::instance()->set('flash', $flash );
                        
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
        $just_completed_order = \Dsc\System::instance()->get('session')->get('shop.just_completed_order' );
        $just_completed_order_id = \Dsc\System::instance()->get('session')->get('shop.just_completed_order_id' );
        
        if (!empty($just_completed_order_id)) 
        {
            try {
                $order = (new \Shop\Models\Orders)->load(array('_id' => new \MongoId( (string) $just_completed_order_id ) ));
            } catch (\Exception $e) {
            	// TODO Handle when it's an invalid order
            }
            
            if (!empty($order->id)) 
            {
                \Base::instance()->set('order', $order);
            }
        }
        
        $view = \Dsc\System::instance()->get( 'theme' );
        echo $view->render( 'Shop/Site/Views::checkout/confirmation.php' );
        
        \Dsc\System::instance()->get('session')->set('shop.just_completed_order', false );
        \Dsc\System::instance()->get('session')->set('shop.just_completed_order_id', null );
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
        
        // Do the selective update, saving the data to the Cart if it validates
        $checkout = $this->input->get( 'checkout', array(), 'array' );
        $cart_checkout = array_merge( (array) $cart->{'checkout'}, $checkout );
        $cart->set('checkout', $cart_checkout);
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

        // Update the cart with checkout data from the form
        $cart = \Shop\Models\Carts::fetch();
        $checkout_inputs = $this->input->get( 'checkout', array(), 'array' );
        if (!empty($checkout_inputs['billing_address']['same_as_shipping'])) {
            $checkout_inputs['billing_address']['same_as_shipping'] = true;
        } else {
            $checkout_inputs['billing_address']['same_as_shipping'] = false;
        }
        $cart_checkout = array_merge( (array) $cart->{'checkout'}, $checkout_inputs );
        $cart->checkout = $cart_checkout;
        $cart->save();        
        
        // Get \Shop\Models\Checkout
            // Bind the cart and payment data to the checkout model
        $checkout = \Shop\Models\Checkout::instance();
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
        if (!$checkout->orderCompleted()) 
        {
            // the standard checkout process
            try {
                $checkout->completeOrder();
            } catch (\Exception $e) {
                $checkout->setError( $e->getMessage() );
            }
            
            if (!$checkout->orderCompleted() || !empty($checkout->getErrors()))
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
    
    public function register()
    {
        $f3 = \Base::instance();
        
        $checkout_method = strtolower( $this->input->get( 'checkout_method', null, 'alnum' ) );
        switch ($checkout_method) 
        {
            // if $checkout_method == guest
            // store email in cart object and then continue
            // create a guest mongoid
            // TODO Enable this
        	//case "guest":
        	    //break;
            
            // if $checkout_method == register
            // validate data
            // create user
            // redirect back to checkout
    	    case "register":
    	        
    	        $email = trim( strtolower( $this->input->get( 'email_address', null, 'string' ) ) );
    	        
    	        $data = array(
    	            'first_name' => $this->input->get( 'first_name', null, 'string' ),
    	            'last_name' => $this->input->get( 'last_name', null, 'string' ),
    	            'email' => $email,
    	            'new_password' => $this->input->get( 'new_password', null, 'string' ),
    	            'confirm_new_password' => $this->input->get( 'confirm_new_password', null, 'string' )
    	        );
    	        
    	        $user = (new \Users\Models\Users)->bind($data);
    	        
    	        // Check if the email already exists and give a custom message if so
    	        if (!empty($user->email) && $existing = $user->emailExists( $user->email ))
    	        {
    	            if ((empty($user->id) || $user->id != $existing->id))
    	            {
    	                \Dsc\System::addMessage( 'This email is already registered.', 'error' );
    	                \Dsc\System::instance()->setUserState('shop.checkout.register.flash_filled', true);
    	                $flash = \Dsc\Flash::instance();
    	                $flash->store($user->cast());
    	                $f3->reroute( '/shop/checkout' );    	        
    	                return;
    	            }
    	        }
    	        
    	        try
    	        {
    	            // this will handle other validations, such as username uniqueness, etc
    	            $settings = \Users\Models\Settings::fetch();
    	            $registration_action = $settings->{'general.registration.action'};    	            
    	            switch ($registration_action)
    	            {
    	            	case "auto_login":
    	            	    $user->active = true;
    	            	    $user->save();
    	            	    break;
    	            	case "auto_login_with_validation":
    	            	    $user->active = false;
    	            	    $user->save();
    	            	    $user->sendEmailValidatingEmailAddress();
    	            	    break;
    	            	default:
    	            	    $user->active = false;
    	            	    $user->save();
    	            	    $user->sendEmailValidatingEmailAddress();
    	            	    break;
    	            }    	            
    	        }
    	        catch(\Exception $e)
    	        {
    	            \Dsc\System::addMessage( 'Could not create account.', 'error' );
    	            \Dsc\System::addMessage( $e->getMessage(), 'error' );
    	            \Dsc\System::instance()->setUserState('shop.checkout.register.flash_filled', true);
    	            $flash = \Dsc\Flash::instance();
    	            $flash->store($user->cast());
    	            $f3->reroute('/shop/checkout');
    	            return;
    	        }
    	        
    	        // if we have reached here, then all is right with the form
    	        $flash = \Dsc\Flash::instance();
    	        $flash->store(array());    	  

    	        // login the user, trigger Listeners
    	        \Dsc\System::instance()->get( 'auth' )->login( $user );
    	        
    	        $f3->reroute( '/shop/checkout' );
    	        
    	        break;
        	         
            // if $checkout_method something else,
            // add message?
            // redirect back to checkout
    	    default:
    	        \Dsc\System::addMessage( 'Invalid Checkout Method', 'error' );
    	        $f3->reroute( '/shop/checkout' );
	            break;
	             
        }

    }
}