<?php 
namespace Shop\PaymentMethods\CCAvenue;

class PaymentMethod extends \Shop\PaymentMethods\PaymentAbstract 
{
    public $identifier = "ccavenue";
    public $title = "CCAvenue";
    public $namespace = "\Shop\PaymentMethods\CCAvenue";
        
    public function __construct(array $config=array())
    {
        $this->theme->registerViewPath( __dir__ . '/Views/', 'Shop/PaymentMethods/CCAvenue/Views' );
    
        return parent::__construct($config);
    }
    
    public function gatewayUrl()
    {
        switch ($this->model->{'settings.mode'})
        {
            case "live":
                $url = 'https://secure.ccavenue.com';                
                break;
                            
            case "test":
            default:
                $url = 'https://test.ccavenue.com';                                
                break;
        }
        
        return $url;
    }
    
    /**
     * Settings form in the admin
     */
    public function settings()
    {
        $this->app->set('pm', $this);
        $this->app->set('model', $this->model);
        
        echo $this->theme->render('Shop/PaymentMethods/CCAvenue/Views::settings.php');
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
            case "test":
                if (!empty($this->model->{'settings.merchant_id'})
                    && !empty($this->model->{'settings.access_code'})
                    && !empty($this->model->{'settings.encryption_key'})
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
        $default = 'Credit, Debit, or Cash Card';
        
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
        $return = $this->theme->renderView('Shop/PaymentMethods/CCAvenue/Views::checkout_form.php');

        return $return;
    }
    
    public function validatePayment() 
    {
        $cart = $this->model->cart();
        $checkout = $this->model->checkout();
        $paymentData = $this->model->paymentData();
        $user = $this->auth->getIdentity();
        
        /*
         * Response looks like:
        *
        Array
        (
            [encResp] => 8fdcf29e90be82c0d34d099a2a5642ad7a45baaa68386123c356622d24990ded32b4c7bab79468c9e89c32b912396b1078ea76adba7bdd4f1c3adebf9d916957838f58252e4e94cbb77dfd037d2a14d84d19ff01fd4b48beb103106ec3f1e0b1b33056a1bdeb5f2720e4e4cdfd70c9bc0024bc8539fd7e0fd2d32ac1a29acfc0ad9b2dfca64844d7fd55a64a3f6cf2615dd6c7d6dc5475e97d3ab230b224fa1907550fbf108c0c4255e29c6d502ede544e8a642e4aead61105f45ff98c87401202e8fd7bbc3831dc0bcdd53610ff15625bbdf7e711b5069c5b79d9bc61dd561eaff2a493732e399422811fd080f8eafd3e11a5d4433d22b68f245b22f58df34b9f3043a5e569a2f9bf42a9ce093c85e313e49d3cb77cd7502b5a0288c8921feb40b800ef05e5d89c048d660eaf7ec719a8d108b6d65868d115f7b37c0908b8ff269159535b875f80dc8c320fb281fd5a439bdaa1227fbd052e14398e2913fc4d1c0264fc9ab94cf1aa10dd2a5bacdfcf541a2ca2f2bd462b5fd3a7d481fb70be38937ef745c8c44ff7def72c144a9082beb2b690f54a441ca48e0c4886854839a5d12d489a9fd576b90565841c8343e48dca01b5d9d9b2419a866fc20c95cdca47e4452a82eb07b161c9227eb93bdb85afd39b5b01aabc53d70891ffbc0ff1ce703fb7c00557effd910e1ca86056d23013608b7f4457b7cb36cd384ff8b1fc8403748d38197230bff0476cb1ab3d0b44b8209b5b32536249b08bfdf33ccee94f006742a415f96929663ffdd6a8de37d9c3853b6fbbb2702ccf7e3dd0d503ac1162dc3596856d7b52ae1e3d297d5c4d3fac5bb4feb2d328ef3f6328062cebb1db9e7f05243edeabdb19b79f352169e8eef7033a3cab6391ab767768b49d2b77b97f784742cd57c8bf0147d7f327dabbfbc246a5e120ef6bce186716cade37b754cc4197fe34dda0ebd5110ec2ac007118bd9710357838e8544bfb5193e99a36d1
            [payment_method] => ccavenue
        )
        *
        * Decrypted data array looks like this
        *
        Array
        (
            [order_id] => 5410bed2f2f66f884f10a640
            [tracking_id] => 103000862840
            [bank_ref_no] => null
            [order_status] => Failure
            [failure_message] =>
            [payment_mode] => Credit Card
            [card_name] => Amex
            [status_code] => 2
            [status_message] => E5431-09171621: Invalid Field : CardSecurityCode
            [currency] => INR
            [amount] => 3010.0
            [billing_name] => Rafael Tushman
            [billing_address] => 850 West 176th Street #6C
            [billing_city] => New York
            [billing_state] => New York
            [billing_zip] => 10033
            [billing_country] => United States
            [billing_tel] => 7186441019
            [billing_email] => email@email.com
            [delivery_name] =>
            [delivery_address] =>
            [delivery_city] =>
            [delivery_state] =>
            [delivery_zip] =>
            [delivery_country] =>
            [delivery_tel] =>
            [merchant_param1] =>
            [merchant_param2] =>
            [merchant_param3] =>
            [merchant_param4] =>
            [merchant_param5] =>
            [vault] => N
        )
        */
        
        $merchant_id = $this->model->{'settings.merchant_id'};
        $working_key = $this->model->{'settings.encryption_key'};
        $access_code = $this->model->{'settings.access_code'};
                
        $encoded_response = isset($paymentData["encResp"]) ? $paymentData["encResp"] : null;
        if (empty($encoded_response)) 
        {
            $message = "Invalid response data from CCAvenue.";
            throw new \Exception($message);
        }
        
        $decrypted_string = \Shop\PaymentMethods\CCAvenue\Lib\Encrypt::decrypt($encoded_response, $working_key);
        $decrypted_values = explode('&', $decrypted_string);

        $payment_details = array();
        foreach ($decrypted_values as $value) 
        {
            $information = explode('=', $value);
            $key = $information[0];
            $value = isset($information[1]) ? $information[1] : null;
            $payment_details[$key] = $value;
        }
        
        $cart_id = isset($payment_details['order_id']) ? $payment_details['order_id'] : null;

        // Verify the cart ID
        if (empty($cart_id) || !\Dsc\Mongo\Collection::isValidId($cart_id))
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
        $order->payment_method_validation_result = $payment_details;

        if (empty($payment_details['order_status'])) 
        {
            throw new \Exception('Missing order status from CCAvenue');
        }
        
        switch ($payment_details['order_status']) 
        {
            case "Aborted":
            case "Failure":
                // Handle failures and try to give the user a useful message
                if (!empty($payment_details['status_message'])) 
                {
                    throw new \Exception($payment_details['status_message']);
                }
                elseif (!empty($payment_details['failure_message'])) 
                {
                    throw new \Exception($payment_details['failure_message']);
                }
                
                throw new \Exception('Your payment was not accepted by CCAvenue');

                break;
            case "Success":
                
                $order->financial_status = \Shop\Constants\OrderFinancialStatus::paid;
                $order->payment_method_id = $this->identifier;
                $order->payment_method_validation_result = $payment_details;
                $order->payment_method_tran_id = !empty($payment_details['tracking_id']) ? $payment_details['tracking_id'] : null;
                
                break;
            default:
                throw new \Exception('Invalid order status from CCAvenue');
                break;
        }
        
        return $payment_details;
    }
    
    public function processPayment()
    {
        $cart = $this->model->cart();
        $user = $this->auth->getIdentity();
        
        $merchant_id = $this->model->{'settings.merchant_id'};
        $working_key = $this->model->{'settings.encryption_key'};
        $access_code = $this->model->{'settings.access_code'};
        
        $signed_fields = array(
            'merchant_id' => $merchant_id,
            'order_id' => (string) $cart->id,
            'currency' => 'INR',
            'amount' => number_format( $cart->total(), 2, '.', '' ),
            'redirect_url' => \Dsc\Url::base() . 'shop/checkout/gateway/ccavenue/completePurchase/' . $cart->id,
            'cancel_url' => \Dsc\Url::base() . 'shop/checkout/payment',
            //'integration_type' => 'iframe_normal',
            'language' => 'EN',
        );

        $merchant_data = '';
        foreach ($signed_fields as $key => $value){
            $merchant_data.=$key.'='.$value.'&';
        }
        
        $encrypted_data = \Shop\PaymentMethods\CCAvenue\Lib\Encrypt::encrypt($merchant_data, $working_key);
        
        $hiddenFields = '
        <input type=hidden name="encRequest" value="'.$encrypted_data.'">
        <input type=hidden name="access_code" value="'.$access_code.'">            
        ';
        
        $output = '<!DOCTYPE html>
        <html>
            <head>
                <title>Redirecting...</title>
            </head>
            <body onload="document.forms[0].submit();">
                <form action="%1$s" method="post" style="height: 1px; overflow: hidden;">
                    <p>Redirecting to payment page...</p>
                    <p>
                        %2$s
                        <input type="submit" value="Continue" />
                    </p>
                </form>
            </body>
        </html>';
        
        $output = sprintf(
            $output,
            htmlentities($this->gatewayUrl().'/transaction/transaction.do?command=initiateTransaction', ENT_QUOTES, 'UTF-8', false),
            $hiddenFields
        );
        
        \Symfony\Component\HttpFoundation\Response::create($output)->send();
        exit;
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