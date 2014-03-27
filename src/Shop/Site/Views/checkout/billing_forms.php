<h2>
    Checkout <small>Step 2 of 2</small>
</h2>

<form action="./shop/checkout/update" method="post">

    <div id="checkout-shipping-summary" class="well well-sm">
        <?php // TODO Validate that it's all present, and if not, redirect to /shop/checkout ?>
        <legend>
            <small>Shipping Summary
            <a class="pull-right" href="./shop/checkout">Edit</a>
            </small>            
        </legend>
        <?php if ($cart->{'checkout.shipping_address'}) { ?>
            <address>
                <?php echo $cart->{'checkout.shipping_address.name'}; ?><br/>
                <?php echo $cart->{'checkout.shipping_address.line_1'}; ?><br/>
                <?php echo !empty($cart->{'checkout.shipping_address.line_2'}) ? $cart->{'checkout.shipping_address.line_2'} . '<br/>' : null; ?>
                <?php echo $cart->{'checkout.shipping_address.city'}; ?> <?php echo $cart->{'checkout.shipping_address.region'}; ?> <?php echo $cart->{'checkout.shipping_address.postal_code'}; ?><br/>
                <?php echo $cart->{'checkout.shipping_address.country'}; ?><br/>
                <?php if (!empty($cart->{'checkout.shipping_address.phone_number'})) { ?>
                <div>
                    Phone: <?php echo $cart->{'checkout.shipping_address.phone_number'}; ?>
                </div>
                <?php } ?>
            </address>
        <?php } ?>
        <?php if (!empty($cart->{'checkout.shipping_method'})) { ?>
        <div>
            Method: <?php echo $cart->{'checkout.shipping_method.title'}; ?>
        </div>
        <?php } ?>
        
    </div>

    <div id="checkout-billing-method" class="well well-sm">Multiple payment methods configured?  If so, display select list that displays additional form on change.  If not, just display the form of the one payment method available.</div>

    <div class="input-group form-group">
        <button type="submit" class="btn btn-default custom-button btn-lg">Submit Order</button>
        <?php \Dsc\System::instance()->get('session')->set('site.shop.checkout.redirect', '/shop/checkout/complete'); ?>
    </div>

</form>
