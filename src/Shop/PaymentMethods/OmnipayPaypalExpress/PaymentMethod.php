<?php 
namespace Shop\PaymentMethods\OmnipayPaypalExpress;

class PaymentMethod extends \Shop\PaymentMethods\PaymentAbstract 
{
    public $identifier = "omnipay.paypal_express";
    public $title = "Paypal Express (via Omnipay)";
    public $namespace = "\Shop\PaymentMethods\OmnipayPaypalExpress";
        
    public function __construct(array $config=array())
    {
        $this->theme->registerViewPath( __dir__ . '/Views/', 'Shop/PaymentMethods/OmnipayPaypalExpress/Views' );
    
        return parent::__construct($config);
    }
    
    public function gateway()
    {
        $gateway = \Omnipay\Omnipay::create('PayPal_Express');
        
        // TODO Get these from DB, via admin
        $gateway->setUsername('info-facilitator_api1.dioscouri.com');
        $gateway->setPassword('1378432757');
        $gateway->setSignature('AFcWxV21C7fd0v3bYYYRCpSSRl31AKyA2GQEqJT5ULWuMj6JThvEWKBw');
        $gateway->setTestMode(true);      
        
        return $gateway;
    }    
    
    /**
     * Settings form in the admin
     */
    public function settings()
    {
        echo $this->theme->render('Shop/PaymentMethods/OmnipayPaypalExpress/Views::settings.php');
    }    
    
    /**
     * Does this payment method require a redirect?
     * 
     * @return boolean
     */
    public function requiresRedirect()
    {
        return true;
    }
    
    /**
     * The name of the payment method,
     * as displayed in the radiolist of payment methods during checkout
     * 
     * @return string
     */
    public function displayName() 
    {
        // Latest buttons sourced from https://www.paypal.com/us/webapps/mpp/logos-buttons
        $default = '<img src="https://www.paypalobjects.com/webstatic/en_US/i/buttons/checkout-logo-large.png" alt="Checkout with PayPal" />';
        
        // TODO Check if there is an override in the admin
        // if not, use the default
        
        $return = $default;
        
        return $return; 
    }
    
    /**
     * The form for the payment method
     * when displayed in the radiolist accordion of payment methods during checkout 
     * 
     * @return unknown
     */
    public function displayForm()
    {
        $return = $this->theme->renderView('Shop/PaymentMethods/OmnipayPaypalExpress/Views::checkout_form.php');

        return $return;
    }
    
    /**
     * Process the payment.  Is used when a checkout form is submitted.
     *
     * If payment processing fails, throw an exception.
     * If redirect is required, perform the redirect.
     * Otherwise, return the response object.
     */    
    public function processPayment()
    {
        $cart = $this->model->cart();
        $paymentData = $this->model->paymentData();
        $user = $this->auth->getIdentity();
        
        $gateway = $this->gateway();
        
        $cardData = array(
            'firstName' => $user->first_name,
            'lastName' => $user->last_name
        );
        
        $card = new \Omnipay\Common\CreditCard($cardData);
        
        $paymentDetails = array(
            'amount' => (float) $cart->total(),
            'returnUrl' => \Dsc\Url::base() . 'shop/checkout/gateway/omnipay.paypal_express/completePurchase/' . $cart->id,
            'cancelUrl' => \Dsc\Url::base() . 'shop/checkout/payment',
            'transactionId' => (string) $cart->id,
            'description' => 'Cart #' . $cart->id,
            'currency' => 'USD',
            'clientIp' => $_SERVER['REMOTE_ADDR'],
            'card' => $card
        );
                
        $response = $gateway->purchase($paymentDetails)->send();
        
        if ($response->isSuccessful()) 
        {
            return $response;
        } 
        
        if ($response->isRedirect()) 
        {
            // redirect to offsite payment gateway
            $response->redirect();
        } 
        else 
        {
            // payment failed
            throw new \Exception( $response->getMessage() );
        }        
    }
    
    public function validatePayment() 
    {
        $cart = $this->model->cart();
        $paymentData = $this->model->paymentData();
        $user = $this->auth->getIdentity();
        $order = $this->model->order();

        $gateway = $this->gateway();
        
        /*
         Paypal Express returns this in the request after a checkout:
        Array
        (
            [token] => EC-74S42539DC567584C
            [PayerID] => L3BUDRTU6MPKC
        )
        */

        $cardData = array(
            'firstName' => $user->first_name,
            'lastName' => $user->last_name
        );
        
        $card = new \Omnipay\Common\CreditCard($cardData);
                
        $paymentDetails = array(
            'amount' => (float) $cart->total(),
            'returnUrl' => \Dsc\Url::base() . 'shop/checkout/gateway/omnipay.paypal_express/completePurchase/' . $cart->id,
            'cancelUrl' => \Dsc\Url::base() . 'shop/checkout/payment',
            'transactionId' => (string) $cart->id,
            'description' => 'Cart #' . $cart->id,
            'currency' => 'USD',
            'clientIp' => $_SERVER['REMOTE_ADDR'],
            'card' => $card
        );
        
        $purchase_response = $gateway->completePurchase($paymentDetails)->send();
        $purchase_data = $purchase_response->getData();        
        
        $params = array(
            'token' => @$paymentData['token']
        );
        
        $response = $gateway->fetchCheckout($params)->send();
        
        $success = $response->isSuccessful();
        
        if (!$success) 
        {
            throw new \Exception('Payment was not successful');
        }
        
        $data = $response->getData();
        
        if (empty($data['INVNUM']) || (string) $data['INVNUM'] != (string) $cart->id)
        {
            throw new \Exception('Payment transaction not associated with this cart');
        }
        
        // Is any further validation required on the payment response?        
        $order->financial_status = \Shop\Constants\OrderFinancialStatus::paid;
        $order->payment_method_id = $this->identifier;
        $order->payment_method_result = $purchase_data;
        $order->payment_method_validation_result = $data;
        $order->payment_method_status = !empty($purchase_data['PAYMENTINFO_0_PAYMENTSTATUS']) ? $purchase_data['PAYMENTINFO_0_PAYMENTSTATUS'] : null;
        $order->payment_method_auth_id = !empty($purchase_data['TOKEN']) ? $purchase_data['TOKEN'] : null;
        $order->payment_method_tran_id = $purchase_response->getTransactionReference();
        
        return $data;
    }
    
}