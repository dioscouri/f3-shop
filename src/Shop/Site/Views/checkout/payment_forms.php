<h2>
    Checkout <small>Step 2 of 2</small>
</h2>

<form action="/shop/checkout/submit" method="post" id="checkout-payment-form">

    <div id="checkout-shipping-summary" class="well well-sm">
        <?php if ($cart->shippingRequired() && (!$cart->shippingMethod() || !$cart->validShippingAddress())) { ?>
            <?php \Base::instance()->reroute('/shop/checkout'); ?>
        <?php } ?>
        <legend>
            <small>Shipping Summary
            <a class="pull-right" href="/shop/checkout">Edit</a>
            </small>            
        </legend>
        <?php if ($cart->{'checkout.shipping_address'}) { ?>
            <address>
                <?php echo $cart->{'checkout.shipping_address.name'}; ?><br/>
                <?php echo $cart->{'checkout.shipping_address.line_1'}; ?><br/>
                <?php echo !empty($cart->{'checkout.shipping_address.line_2'}) ? $cart->{'checkout.shipping_address.line_2'} . '<br/>' : null; ?>
                <?php echo $cart->{'checkout.shipping_address.city'}; ?> <?php echo $cart->{'checkout.shipping_address.region'}; ?> <?php echo $cart->{'checkout.shipping_address.postal_code'}; ?><br/>
                <?php echo $cart->{'checkout.shipping_address.country'}; ?><br/>
            </address>
            <?php if (!empty($cart->{'checkout.shipping_address.phone_number'})) { ?>
            <div>
                <label>Phone:</label> <?php echo $cart->{'checkout.shipping_address.phone_number'}; ?>
            </div>
            <?php } ?>
        
        <?php } ?>
        
        <?php if ($method = $cart->shippingMethod()) { ?>
            <div>
                <label>Method:</label> <?php echo $method->{'name'}; ?> &mdash; $<?php echo $method->total(); ?>
            </div>
        <?php } ?>
        <?php if ($cart->{'checkout.order_comments'}) { ?>
            <div>
                <label>Comments:</label>
                <?php echo $cart->{'checkout.order_comments'}; ?>
            </div>
        <?php } ?>
        
    </div>

    <div id="checkout-payment-methods" class="well well-sm">
    <?php // TODO Multiple payment methods configured?  If so, display select list that displays additional form on change.  If not, just display the form of the one payment method available. ?>
        <legend>
            <small>Payment</small>
        </legend>
        
        <div id="checkout-payment-methods-container"></div>    
    
    </div>

    <div class="input-group form-group">
        <button id="submit-order" type="submit" class="btn btn-default custom-button btn-lg">Submit Order</button>
        <?php \Dsc\System::instance()->get('session')->set('site.shop.checkout.redirect', '/shop/checkout/confirmation'); ?>
    </div>

</form>

<script>
jQuery(document).ready(function(){
    window.checkout_payment_validation = new ShopValidation('#checkout-payment-form');
    
    jQuery('#checkout-payment-form').on('submit.shop', function(ev){
        var el = jQuery(this); 
        if (!window.checkout_payment_validation.validateForm()) {
            el.data('validated', false);
            ev.preventDefault();
            return false;
        }
        if (el.data('locked')) {
        	ev.preventDefault();
            return false;
        }
        el.data('locked', true);
        el.data('validated', true);
        jQuery('#checkout-payment-methods').css({ opacity: 0.5 });
        el.submit();    
    });

    if (!window.payment_methods_loaded)
    {
		// Appear to be loading.
		jQuery('#checkout-payment-methods').css({ opacity: 0.5 });
		jQuery(this).closest('form').data('locked', true);
		        
		jQuery('#checkout-payment-methods-container').load('/shop/checkout/payment-methods', function ( response, status, xhr )
		{
		    if ( status != "error" ) {
		        window.payment_methods_loaded = true;
		    }
		    
			jQuery('#checkout-payment-form').data('locked', false);
			jQuery('#checkout-payment-methods').css({ opacity: '' });
		});

    }
});
</script>