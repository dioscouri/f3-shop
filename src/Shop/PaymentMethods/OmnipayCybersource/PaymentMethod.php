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
                
        switch ($this->model->{'settings.mode'})
        {
            case "live":
                $gateway->setProfileId($this->model->{'settings.live.profile_id'});
                $gateway->setAccessKey($this->model->{'settings.live.access_key'});
                $gateway->setSecretKey($this->model->{'settings.live.secret_key'});
                $gateway->setTestMode(false);                
                break;
                
            case "test":
                $gateway->setProfileId($this->model->{'settings.test.profile_id'});
                $gateway->setAccessKey($this->model->{'settings.test.access_key'});
                $gateway->setSecretKey($this->model->{'settings.test.secret_key'});
                $gateway->setTestMode(true);                                
                break;
        }
        
        return $gateway;
    }
    
    /**
     * Settings form in the admin
     */
    public function settings()
    {
        $this->app->set('pm', $this);
        $this->app->set('model', $this->model);
        
        echo $this->theme->render('Shop/PaymentMethods/OmnipayCybersource/Views::settings.php');
    }

    /**
     * Determines whether or not the payment method's settings have been completely configured for use
     *
     * @return boolean
     */
    public function isConfigured()
    {
        switch ($this->model->{'settings.mode'}) 
        {
            case "live":
                if (!empty($this->model->{'settings.live.profile_id'})
                    && !empty($this->model->{'settings.live.access_key'})
                    && !empty($this->model->{'settings.live.secret_key'})
                ) 
                {
                    return true;
                }
                break;
            case "test":
                if (!empty($this->model->{'settings.test.profile_id'})
                    && !empty($this->model->{'settings.test.access_key'})
                    && !empty($this->model->{'settings.test.secret_key'})
                )
                {
                    return true;
                }                
                break;
            default:
                break;
        }
        
        return false;
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
            'amount' => number_format( $cart->total(), 2, '.', '' ),                        
            'bill_to_email' => $user->email(true),
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
            [req_bill_to_phone] => 800-123-4567
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
            [req_bill_to_address_line1] => 1 Main Street #6
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
            [req_bill_to_email] => email@dioscouri.com
            [req_access_key] => 4571ed37561d32419cab3fa6ad0ff7cb
            [actor_id] => 53bd8b8af02e25fa0af22e6d
            [affiliate_id] => 
        )
        */

        $response = $gateway->completeAuthorize($paymentData)->send();
        
        if (!$response->isSuccessful())
        {
            // OK, so lets figure out why payment was not successful
            
            switch($response->getReasonCode()) 
            {
                case "102": // One or more fields in the request contain invalid data.
                    // Check invalid_fields, a CSV of the invalid fields.
                    foreach ($response->getInvalidFields() as $invalid_field) 
                    {
                        switch($invalid_field) 
                        {
                            case "card_number":
                                $message = 'Invalid card number.  Please confirm the information submitted and try again.';
                                throw new \Exception($message);
                                break;
                            case "card_expiry_date":
                                $message = 'Invalid expiration date.  Please confirm the information submitted and try again.';
                                throw new \Exception($message);                                
                                break;
                            case "card_cvn":
                                $message = 'Invalid card security code.  Please confirm the information submitted and try again.';
                                throw new \Exception($message);
                                break;
                            case "card_type":
                                $message = 'Invalid card type.  Please try again with a different card.';
                                throw new \Exception($message);
                                break;
                            case "bill_to_phone":
                            case "customer_phone":
                                $message = 'Invalid Billing Phone Number.  Please correct and try again.';
                                throw new \Exception($message);
                                break;                                                                                                
                        }
                    } 
                    
                case "104": // The access_key and transaction_uuid fields for this authorization request matches the access_key and transaction_uuid of another authorization request that you sent within the past 15 minutes.
                case "207": // issuing bank unavailable                
                case "236": // processor failure
                case "240": // wrong card type
                    $message = 'There was an error submitting your order.  Please try again.';
                    throw new \Exception($message);
                    break;
                case "202": // expired card
                    $message = 'Your card has expired.  Please try again with a different card.';
                    throw new \Exception($message);
                    break;
                case "203": // general decline
                case "205": // lost/stolen card
                case "208": // inactive card
                case "210": // card over limit
                case "221": // customer in negative file
                case "222": // account frozen
                case "231": // account number invalid
                case "232": // card not accepted
                case "233": // general decline
                case "234": // error in cybersource setup
                case "520": // declined by Decision Manager
                    $message = 'Your card has been declined.  Please try again with a different card.';
                    throw new \Exception($message);                    
                    break;                   
                case "204":
                    $message = 'Your card has been declined due to insufficient funds.  Please try again with a different card.';
                    throw new \Exception($message);
                    break;
                case "211": // invalid CVN
                case "230": // CVN fail in cybersource 
                    $message = 'The billing information that you entered does not match your credit card information. Please confirm the Security Code you have provided.';
                    throw new \Exception($message);                    
                    break;
                case "200": // AVS fail in cybersource
                    $message = 'The billing information that you entered does not match your credit card information. Please confirm the Billing Address you have provided.';
                    throw new \Exception($message);
                    break;
                case "201": // call bank
                default:
                    break;
            }
            
            // check CVN response
            /*
            D The transaction was considered to be suspicious by the issuing bank.
            I The CVN failed the processor's data validation.
            M The CVN matched.
            N The CVN did not match.
            P The CVN was not processed by the processor for an unspecified reason.
            S The CVN is on the card but was not included in the request.
            U Card verification is not supported by the issuing bank.
            X Card verification is not supported by the card association.
            1 Card verification is not supported for this processor or card type.
            2 An unrecognized result code was returned by the processor for the card
            verification response.
            3 No result code was returned by the processor.
             */
            switch($response->getCVNCode()) 
            {
                case "D":
                case "I":
                case "N":
                case "S":
                    $message = 'The billing information that you entered does not match your credit card information. Please confirm the Security Code you have provided.';
                    throw new \Exception($message);
                    break;
                case "M":
                case "P":                
                case "U":
                case "X":
                case "1":
                case "2":
                case "3":
                default: // null
                    break;
            }
            
            // check AVS response 
            /*
            A Partial match Street address matches, but five digit and nine digit postal codes
            do not match.
            B Partial match Street address matches, but postal code is not verified.
            C No match Street address and postal code do not match.
            D & M Match Street address and postal code match.
            E Invalid AVS data is invalid or AVS is not allowed for this card type.
            F Partial match Card member’s name does not match, but billing postal code
            matches. Returned only for the American Express card type.
            G Not supported.
            H Partial match Card member’s name does not match, but street address and
            postal code match. Returned only for the American Express
            card type.
            I No match Address not verified.
            J Match Card member’s name, billing address, and postal code match.
            Shipping information verified and chargeback protection
            guaranteed through the Fraud Protection Program. Returned
            only if you are signed up to use AAV+ with the American
            Express Phoenix processor.
            K Partial match Card member’s name matches, but billing address and billing
            postal code do not match. Returned only for the American
            Express card type.
            L Partial match Card member’s name and billing postal code match, but billing
            address does not match. Returned only for the American
            Express card type.
            M Match Street address and postal code match.
            N No match One of the following:
             Street address and postal code do not match.
             Card member’s name, street address, and postal code do not
            match. Returned only for the American Express card type.
            O Partial match Card member’s name and billing address match, but billing
            postal code does not match. Returned only for the American
            Express card type.
            P Partial match Postal code matches, but street address is not verified.
            Q Match Card member’s name, billing address, and postal code match.
            Shipping information verified but chargeback protection not
            guaranteed (Standard program). Returned only if you are
            registered to use AAV+ with the American Express Phoenix
            processor.
            R System unavailable System unavailable.
            S Not supported U.S.-issuing bank does not support AVS.
            T Partial match Card member’s name does not match, but street address
            matches. Returned only for the American Express card type.
            U System unavailable Address information unavailable for one of these reasons:
             The U.S. bank does not support non-U.S. AVS.
             The AVS in a U.S. bank is not functioning properly.
            V Match Card member’s name, billing address, and billing postal code
            match. Returned only for the American Express card type.
            W Partial match Street address does not match, but nine digit postal code
            matches.
            X Match Street address and nine digit postal code match.
            Y Match Street address and five digit postal code match.
            Z Partial match Street address does not match, but 5-digit postal code matches.
            1 Not supported AVS is not supported for this processor or card type.
            2 Unrecognized The processor returned an unrecognized value for the AVS
            response.
            3 Match Address is confirmed. Returned only for PayPal Express
            Checkout.
            4 No match Address is not confirmed. Returned only for PayPal Express
            Checkout.
             */
            switch($response->getAVSCode())
            {
                case "C":
                case "E":
                case "I":
                case "N":
                case "4":
                    $message = 'The billing information that you entered does not match your credit card information. Please confirm the Billing Address you have provided.';
                    throw new \Exception($message);
                    break;
                default: // null
                    break;
            }            

            // if we reach here, throw a general error
            //throw new \Exception('Payment was not successful');
            $message = 'Your card has been declined.  Please try again with a different card.';
            throw new \Exception($message);
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
            throw new \Exception('Payment transaction has an invalid cart ID');
        }
        
        $cart = (new \Shop\Models\Carts)->setState('filter.id', $cart_id)->getItem();
        if (empty($cart->id))
        {
            throw new \Exception('Payment transaction has an unrecognized cart ID');
        }        
        
        $checkout->addCart($cart);
        $order = $checkout->order(true);
        
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
    
    public function avsStreetMatch( $data=array() )
    {
        if (is_object($data))
        {
            $data = \Joomla\Utilities\ArrayHelper::fromObject($data);
        }
        
        if (!is_array($data))
        {
            return null;
        }
                
        // check AVS response
        /*
        A Partial match Street address matches, but five digit and nine digit postal codes
        do not match.
        B Partial match Street address matches, but postal code is not verified.
        C No match Street address and postal code do not match.
        D & M Match Street address and postal code match.
        E Invalid AVS data is invalid or AVS is not allowed for this card type.
        F Partial match Card member’s name does not match, but billing postal code
        matches. Returned only for the American Express card type.
        G Not supported.
        H Partial match Card member’s name does not match, but street address and
        postal code match. Returned only for the American Express
        card type.
        I No match Address not verified.
        J Match Card member’s name, billing address, and postal code match.
        Shipping information verified and chargeback protection
        guaranteed through the Fraud Protection Program. Returned
        only if you are signed up to use AAV+ with the American
        Express Phoenix processor.
        K Partial match Card member’s name matches, but billing address and billing
        postal code do not match. Returned only for the American
        Express card type.
        L Partial match Card member’s name and billing postal code match, but billing
        address does not match. Returned only for the American
        Express card type.
        M Match Street address and postal code match.
        N No match One of the following:
        Street address and postal code do not match.
        Card member’s name, street address, and postal code do not
        match. Returned only for the American Express card type.
        O Partial match Card member’s name and billing address match, but billing
        postal code does not match. Returned only for the American
        Express card type.
        P Partial match Postal code matches, but street address is not verified.
        Q Match Card member’s name, billing address, and postal code match.
        Shipping information verified but chargeback protection not
        guaranteed (Standard program). Returned only if you are
        registered to use AAV+ with the American Express Phoenix
        processor.
        R System unavailable System unavailable.
        S Not supported U.S.-issuing bank does not support AVS.
        T Partial match Card member’s name does not match, but street address
        matches. Returned only for the American Express card type.
        U System unavailable Address information unavailable for one of these reasons:
        The U.S. bank does not support non-U.S. AVS.
        The AVS in a U.S. bank is not functioning properly.
        V Match Card member’s name, billing address, and billing postal code
        match. Returned only for the American Express card type.
        W Partial match Street address does not match, but nine digit postal code
        matches.
        X Match Street address and nine digit postal code match.
        Y Match Street address and five digit postal code match.
        Z Partial match Street address does not match, but 5-digit postal code matches.
        1 Not supported AVS is not supported for this processor or card type.
        2 Unrecognized The processor returned an unrecognized value for the AVS
        response.
        3 Match Address is confirmed. Returned only for PayPal Express
        Checkout.
        4 No match Address is not confirmed. Returned only for PayPal Express
        Checkout.
        */
        
        $avs_code = isset($data['auth_avs_code']) ? $data['auth_avs_code'] : null;
        switch($avs_code)
        {
            // Partial Matches
            case "A":
            case "B":
            case "H":
            case "O":
            case "T":
            // Complete Matches
            case "D":
            case "D & M":
            case "J":
            case "M":
            case "Q":
            case "V":
            case "X":
            case "Y":
            case "3":
                return true;
                break;
            // Partial Matches
            case "F":
            case "K":
            case "L":
            case "P":
            case "W":
            case "Z":                
            // No Match
            case "C":
            case "E":
            case "I":
            case "N":
            case "4":
                return false;
                break;
            // Unavailable
            case "G":
            case "R":
            case "S":
            case "U":
            case "1":
            case "2":
            default:
                return null;
                break;         
        }
    }
    
    public function avsZipMatch( $data=array() )
    {
        if (is_object($data))
        {
            $data = \Joomla\Utilities\ArrayHelper::fromObject($data);
        }
        
        if (!is_array($data))
        {
            return null;
        }
                
        // check AVS response
        /*
        A Partial match Street address matches, but five digit and nine digit postal codes
        do not match.
        B Partial match Street address matches, but postal code is not verified.
        C No match Street address and postal code do not match.
        D & M Match Street address and postal code match.
        E Invalid AVS data is invalid or AVS is not allowed for this card type.
        F Partial match Card member’s name does not match, but billing postal code
        matches. Returned only for the American Express card type.
        G Not supported.
        H Partial match Card member’s name does not match, but street address and
        postal code match. Returned only for the American Express
        card type.
        I No match Address not verified.
        J Match Card member’s name, billing address, and postal code match.
        Shipping information verified and chargeback protection
        guaranteed through the Fraud Protection Program. Returned
        only if you are signed up to use AAV+ with the American
        Express Phoenix processor.
        K Partial match Card member’s name matches, but billing address and billing
        postal code do not match. Returned only for the American
        Express card type.
        L Partial match Card member’s name and billing postal code match, but billing
        address does not match. Returned only for the American
        Express card type.
        M Match Street address and postal code match.
        N No match One of the following:
        Street address and postal code do not match.
        Card member’s name, street address, and postal code do not
        match. Returned only for the American Express card type.
        O Partial match Card member’s name and billing address match, but billing
        postal code does not match. Returned only for the American
        Express card type.
        P Partial match Postal code matches, but street address is not verified.
        Q Match Card member’s name, billing address, and postal code match.
        Shipping information verified but chargeback protection not
        guaranteed (Standard program). Returned only if you are
        registered to use AAV+ with the American Express Phoenix
        processor.
        R System unavailable System unavailable.
        S Not supported U.S.-issuing bank does not support AVS.
        T Partial match Card member’s name does not match, but street address
        matches. Returned only for the American Express card type.
        U System unavailable Address information unavailable for one of these reasons:
        The U.S. bank does not support non-U.S. AVS.
        The AVS in a U.S. bank is not functioning properly.
        V Match Card member’s name, billing address, and billing postal code
        match. Returned only for the American Express card type.
        W Partial match Street address does not match, but nine digit postal code
        matches.
        X Match Street address and nine digit postal code match.
        Y Match Street address and five digit postal code match.
        Z Partial match Street address does not match, but 5-digit postal code matches.
        1 Not supported AVS is not supported for this processor or card type.
        2 Unrecognized The processor returned an unrecognized value for the AVS
        response.
        3 Match Address is confirmed. Returned only for PayPal Express
        Checkout.
        4 No match Address is not confirmed. Returned only for PayPal Express
        Checkout.
        */
        
        $avs_code = isset($data['auth_avs_code']) ? $data['auth_avs_code'] : null;
        switch($avs_code)
        {
            // Partial Matches
            case "F":
            case "H":
            case "L":
            case "P":
            case "W":
            case "Z":
            // Complete Matches
            case "D":
            case "D & M":
            case "J":
            case "M":
            case "Q":
            case "V":
            case "X":
            case "Y":
            case "3":
                return true;
                break;
            // Partial Matches
            case "A":
            case "B":
            case "K":
            case "O":
            case "T":
            // No Match
            case "C":
            case "E":
            case "I":
            case "N":
            case "4":
                return false;
                break;
            // Unavailable
            case "G":
            case "R":
            case "S":
            case "U":
            case "1":
            case "2":
            default:
                return null;
                break;         
        }
    }

    public function cvnMatch( $data=array() )
    {
        if (is_object($data)) 
        {
            $data = \Joomla\Utilities\ArrayHelper::fromObject($data);
        }
        
        if (!is_array($data)) 
        {
            return null;
        }
            
        // check CVN response
        /*
        D The transaction was considered to be suspicious by the issuing bank.
        I The CVN failed the processor's data validation.
        M The CVN matched.
        N The CVN did not match.
        P The CVN was not processed by the processor for an unspecified reason.
        S The CVN is on the card but was not included in the request.
        U Card verification is not supported by the issuing bank.
        X Card verification is not supported by the card association.
        1 Card verification is not supported for this processor or card type.
        2 An unrecognized result code was returned by the processor for the card
        verification response.
        3 No result code was returned by the processor.
        */
        
        $cv_result = isset($data['auth_cv_result']) ? $data['auth_cv_result'] : null;
        switch($cv_result)
        {
            // No Match
            case "D":
            case "I":
            case "N":
            case "S":
                return false;
                break;
            // Match            
            case "M":
                return true;
                break;
            // Unavailable
            case "P":
            case "U":
            case "X":
            case "1":
            case "2":
            case "3":                
            default:
                return null; 
                break;
        }    
    }    
    
}