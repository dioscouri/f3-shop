<?php
namespace Shop\PaymentMethods;

abstract class PaymentAbstract extends \Dsc\Singleton  
{
    abstract function requiresRedirect();
    abstract function displayName();
    abstract function displayForm();
    
    /**
     * Process the payment.  Is used when a checkout form is submitted.
     * 
     * If payment processing fails, throw an exception.
     * If redirect is required, perform the redirect.
     * Otherwise, return the response object.
     */
    abstract function processPayment();
    
    /**
     * Validates a payment, typically by the transaction ID
     */
    abstract function validatePayment();
    
    //abstract function authorize();
    //abstract function capture();
    //abstract function purchase();
    //abstract function void();
    //abstract function refund();
    
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
