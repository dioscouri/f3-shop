<p class="alert alert-info">
    After clicking "submit order" you will be redirected to secure Paypal pages to complete payment.
</p>
<div class="text-center">
    <div>Paypal supports the following payment methods:</div>
    <?php // Latest buttons sourced from https://www.paypal.com/us/webapps/mpp/logos-buttons ?>
    <img src="https://www.paypalobjects.com/webstatic/en_US/i/buttons/cc-badges-ppmcvdam.png" alt="Buy now with PayPal" />
</div>

<?php
/*
$user = $this->auth->getIdentity();
$gateway = \Omnipay\Omnipay::create('PayPal_Express');

$gateway_settings = $gateway->getDefaultParameters();

$gateway->setUsername('info-facilitator_api1.dioscouri.com');
$gateway->setPassword('1378432757');
$gateway->setSignature('AFcWxV21C7fd0v3bYYYRCpSSRl31AKyA2GQEqJT5ULWuMj6JThvEWKBw');
$gateway->setTestMode(true);
$cardData = array(
    'firstName' => $user->first_name,
    'lastName' => $user->last_name
);

$card = new \Omnipay\Common\CreditCard($cardData);

$paymentDetails = array(
    'amount' => (float) $cart->total(),
    'returnUrl' => \Dsc\Url::base() . 'shop/checkout/gateway/PayPal_Express/completePurchase/' . $cart->id,
    'cancelUrl' => \Dsc\Url::base() . 'shop/checkout/payment',    
    'transactionId' => (string) $cart->id,
    'description' => 'Cart #' . $cart->id,
    'currency' => 'USD',
    'clientIp' => $_SERVER['REMOTE_ADDR'],
    'card' => $card
);

echo \Dsc\Debug::dump($paymentDetails);

try {
    $response = $gateway->purchase($paymentDetails)->send();    
}
catch (\Exception $e) {
    echo $e->getMessage();
    return;
}

echo \Dsc\Debug::dump($response);

if ($response->isRedirect()) 
{
    ?>    	
    <div>
    method='<?php echo $response->getRedirectMethod(); ?>' action='<?php echo $response->getRedirectUrl(); ?>'
    <?php
    foreach ((array) $response->getRedirectData() as $name => $value) 
    {
        ?>
        <p>name='<?php echo $name; ?>' value='<?php echo $value; ?>'</p>
        <?php
    }
    ?>
    </div>
    <?php
}
*/