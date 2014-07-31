<?php 
namespace Shop\PaymentMethods\OmnipayCybersource;

class PaymentMethod extends \Shop\PaymentMethods\PaymentAbstract 
{
    public $identifier = "omnipay.cybersource";
    public $title = "Cybersource (via Omnipay)";
    public $namespace = "\Shop\PaymentMethods\OmnipayCybersource";
        
    public function __construct(array $config=array())
    {
        $this->theme->registerViewPath( __dir__ . '/Views/', 'Shop/PaymentMethods/OmnipayCybersource/Views' );
    
        return parent::__construct($config);
    }
    
    public function gateway()
    {
        $gateway = \Omnipay\Omnipay::create('Cybersource');
                
        // TODO Get these from DB, via admin
        $gateway->setProfileId('bangles');
        $gateway->setAccessKey('4571ed37561d32419cab3fa6ad0ff7cb');
        $gateway->setSecretKey('43a05941f88947cb881dfcb0e857f80345bb3f23d4ef431c97d07da5e62d4e8a188cce3cd147436e88d9ba1d94e1d48434ff127090064b0190a4390a279b04b207c340821a2449c1a31a37cb5da9f0db2402d1d6c26f4ab09757c19eec1fa1c848b61e2df73941debf9938005a193e4de0ac66dbc0e94e7eae6aa95f6e04cd35');        
        $gateway->setTestMode(true);
        
        return $gateway;
    }
    
    /**
     * Settings form in the admin
     */
    public function settings()
    {
        echo $this->theme->render('Shop/PaymentMethods/OmnipayCybersource/Views::settings.php');
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
        $default = 'Credit Card';
        
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
        $cart = $this->model->cart();
        $user = $this->auth->getIdentity();
        
        $gateway = $this->gateway();
        
        $transaction_type = 'authorization,create_payment_token';
        
        $signed_fields = array(
            'access_key' => $gateway->getAccessKey(),
            'profile_id' => $gateway->getProfileId(),
            'transaction_uuid' => (string) $cart->id . "." . time(),
            'payment_method' => 'card',
            'locale' => 'en-us',
            'currency' => 'USD',
            'transaction_type' => $transaction_type,
            'reference_number' => (string) $cart->id,
            'amount' => number_format( $cart->total(), 2 ),                        
            'bill_to_email' => $user->email,
            'signed_date_time' => gmdate("Y-m-d\TH:i:s\Z"),
            'signed_field_names' => '',
            'unsigned_field_names' => 'card_number,card_cvn,card_expiry_date,card_type,bill_to_forename,bill_to_surname,bill_to_phone,bill_to_address_line1,bill_to_address_line2,bill_to_address_city,bill_to_address_state,bill_to_address_country,bill_to_address_postal_code',
        );

        $signed_fields['signed_field_names'] = implode(",", array_keys($signed_fields) );
        
        $signature = $gateway->generateSignature($signed_fields);
        
        $request = $gateway->authorize($signed_fields);
        
        $this->app->set('request', $request);
        $this->app->set('signed_fields', $signed_fields);
        $this->app->set('signature', $signature);        
        $this->app->set('pm', $this->model);
        $this->app->set('gateway', $this->gateway() );
                
        $return = $this->theme->renderView('Shop/PaymentMethods/OmnipayCybersource/Views::checkout_form.php');

        return $return;
    }
    
    public function validatePayment() 
    {
        $cart = $this->model->cart();
        $checkout = $this->model->checkout();
        $paymentData = $this->model->paymentData();
        $user = $this->auth->getIdentity();
        $order = $this->model->order();
        
        $gateway = $this->gateway();

        /*
         * Success response looks like: 
         * 
        Array
        (
            [utf8] =>
            [req_bill_to_address_country] => US
            [auth_avs_code] => X
            [req_bill_to_phone] => 718-644-1019
            [req_card_number] => xxxxxxxxxxxx1111
            [req_card_expiry_date] => 01-2017
            [decision] => ACCEPT
            [req_bill_to_address_state] => NY
            [signed_field_names] => transaction_id,decision,req_access_key,req_profile_id,req_transaction_uuid,req_transaction_type,req_reference_number,req_amount,req_currency,req_locale,req_payment_method,req_bill_to_forename,req_bill_to_surname,req_bill_to_email,req_bill_to_phone,req_bill_to_address_line1,req_bill_to_address_city,req_bill_to_address_state,req_bill_to_address_country,req_bill_to_address_postal_code,req_card_number,req_card_type,req_card_expiry_date,message,reason_code,auth_avs_code,auth_avs_code_raw,auth_response,auth_amount,auth_code,auth_trans_ref_no,auth_time,payment_token,signed_field_names,signed_date_time
            [req_payment_method] => card
            [req_transaction_type] => authorization,create_payment_token
            [auth_code] => 888888
            [signature] => Tjvr0UPJdcXp10Ouo1IoS8O5+e1C1IKHD0qIMKcGrmQ=
            [req_locale] => en-us
            [reason_code] => 100
            [req_bill_to_address_postal_code] => 10033
            [req_bill_to_address_line1] => 850 West 176th Street #6C
            [req_card_type] => 001
            [auth_amount] => 32.63
            [req_bill_to_address_city] => New York
            [signed_date_time] => 2014-07-31T04:46:04Z
            [req_currency] => USD
            [req_reference_number] => 53d8fa97f02e25c641d3783f
            [auth_avs_code_raw] => I1
            [transaction_id] => 4067819643530176195662
            [req_amount] => 32.63
            [auth_time] => 2014-07-31T044604Z
            [message] => Request was processed successfully.
            [auth_response] => 100
            [req_profile_id] => bangles
            [req_transaction_uuid] => 53d8fa97f02e25c641d3783f.1406781952
            [payment_token] => 4067819643530176195662
            [auth_trans_ref_no] => 81884333NY88NKVM
            [req_bill_to_surname] => Tushman
            [req_bill_to_forename] => Rafael
            [req_bill_to_email] => rdiaztushman@dioscouri.com
            [req_access_key] => 4571ed37561d32419cab3fa6ad0ff7cb
            [actor_id] => 53bd8b8af02e25fa0af22e6d
            [affiliate_id] => 
        )
        */

        $response = $gateway->completeAuthorize($paymentData)->send();
        
        if (!$response->isSuccessful())
        {
            throw new \Exception('Payment was not successful');
        }
        
        // Check the signature        
        if (!$response->validateSignature( $gateway->getSecretKey() ))
        {
            throw new \Exception('Payment data could not be verified');
        }        
        
        $cart_id = $response->getMerchantTransactionReference();
                
        // Verify the cart ID
        if (!\Dsc\Mongo\Collection::isValidId($cart_id))
        {
            throw new \Exception('Payment transaction has an invalid cart');
        }
        
        $cart = $cart->setState('filter.id', $cart_id)->getItem();
        if (empty($cart->id) || (string) $cart->id != (string) $cart_id)
        {
            throw new \Exception('Payment transaction not associated with this cart');
        }        

        //$checkout->addCart($cart);
        
        $data = $response->getData();
        
        // Is any further validation required on the payment response?        
        $order->financial_status = \Shop\Constants\OrderFinancialStatus::authorized;
        $order->payment_method_id = $this->identifier;
        //$order->payment_method_result = array();
        $order->payment_method_validation_result = $data;
        //$order->payment_method_status = null;
        $order->payment_method_auth_id = !empty($data['auth_trans_ref_no']) ? $data['auth_trans_ref_no'] : null;
        $order->payment_method_tran_id = $response->getTransactionReference();
        
        return $data;
    }
    
    public function processPayment()
    {
        return $this->validatePayment();
    }
    
}