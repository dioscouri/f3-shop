<?php
namespace Shop\PaymentMethods;

abstract class PaymentAbstract extends \Dsc\Singleton  
{
    //abstract function authorize();
    //abstract function capture();
    //abstract function purchase();
    //abstract function void();
    //abstract function refund();
        
    abstract function requiresRedirect();
    abstract function displayName();
    abstract function displayForm();
    
    /**
     * Process the payment.  Is used when a checkout form is submitted.
     * 
     * If payment processing fails, throw an exception.
     * If redirect is required, perform the redirect.
     * Otherwise, return the response object.
     * 
     * Throws \Exception
     * 
     * @return $this
     */
    abstract function processPayment();
    
    /**
     * Validates a payment, typically by the transaction ID.
     * 
     * Throws \Exception
     * 
     * @return mixed 
     */
    abstract function validatePayment();
    
    /**
     * Displays the settings form in the admin
     */
    abstract function settings();
    
    /**
     * Determines whether or not the payment method's settings have been completely configured for use
     * 
     * @return boolean
     */
    public function isConfigured()
    {
        return false;
    }

    /**
     * 
     * @param array $config
     */
    public function __construct(array $config=array())
    {
        foreach ($config as $key=>$value) {
            $this->$key = $value;
        }
    }
    
    /**
     * Bootstrap this Payment Method, including:
     * 1. Register any custom routes that the payment method needs
     * 2. Add Custom view paths
     *
     * @return \Shop\PaymentMethods\PaymentAbstract
     */
    public function bootstrap()
    {
        return $this;
    }    
}
