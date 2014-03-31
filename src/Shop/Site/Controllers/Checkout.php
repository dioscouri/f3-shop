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
}