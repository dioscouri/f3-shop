<?php 
namespace Shop\Site\Controllers;

class Checkout extends \Dsc\Controller 
{    
    public function index() 
    {
        $cart = \Shop\Models\Carts::fetch();
        $cart->selected_country = $cart->{'shipping_address.country'} ? $cart->{'shipping_address.country'} : \Shop\Models\Settings::fetch()->{'country'};
        
        \Base::instance()->set('cart', $cart);
        
        $identity = $this->getIdentity();
        if (empty($identity->id))
        {
            $view = \Dsc\System::instance()->get('theme');
            echo $view->render('Shop/Site/Views::checkout/identity.php');
            return;
        }
        
        $view = \Dsc\System::instance()->get('theme');
        echo $view->render('Shop/Site/Views::checkout/index.php');
    }
    
    /**
     * Displays step 2 (of 2) of the default checkout process
     */
    public function billing()
    {
        $cart = \Shop\Models\Carts::fetch();
        \Base::instance()->set('cart', $cart);
        
        $identity = $this->getIdentity();
        if (empty($identity->id))
        {
            $view = \Dsc\System::instance()->get('theme');
            echo $view->render('Shop/Site/Views::checkout/identity.php');
            return;
        }        
        
        $view = \Dsc\System::instance()->get('theme');
        echo $view->render('Shop/Site/Views::checkout/billing.php');
    }
    
    /**
     * Adds POST data to the user's cart.  
     * Typically the target of checkout forms, allowing custom workflows.
     * Responds according to request method.
     * Validates only the provided data, not the cart. 
     */
    public function update()
    {
        // TODO Do the selective update, saving the data to the Cart if it validates
        // If the select data doesn't validate, return an error message while redirecting back to referring page (if http request)
        // or outputting json_encoded response with array of errrors
        
        $redirect = '/shop/checkout/billing';
        if ($custom_redirect = \Dsc\System::instance()->get('session')->get('site.shop.checkout.redirect'))
        {
            $redirect = $custom_redirect;
        }
        
        \Dsc\System::instance()->get('session')->set('site.shop.checkout.redirect', null);
        \Base::instance()->reroute($redirect);
    }
    
    /**
     * Validates a cart for checkout and returns either a "good to go" message 
     * or data on why the cart is not ready to be submitted 
     */
    public function validate()
    {
        
    }
}