<h2 class="clearfix">
    Checkout <small>Step 2 of 2</small>
    <span class="pull-right">
    <small><i class="fa fa-chevron-left"></i></small>
    <a href="./shop/checkout"><small>Back to Step 1</small></a>
    </span>
</h2>

<form action="./shop/checkout/submit" method="post" id="checkout-payment-form">

    <div id="checkout-shipping-summary" class="well well-sm">
        <?php if ($cart->shippingRequired() && (!$cart->shippingMethod() || !$cart->validShippingAddress())) { ?>
            <?php \Base::instance()->reroute('/shop/checkout'); ?>
        <?php } ?>
        <legend>
            <small>Shipping Summary
            <a class="pull-right" href="./shop/checkout">Edit Shipping Info</a>
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

    <div id="checkout-payment-methods" class="clearfix">
    <?php // TODO Multiple payment methods configured?  If so, display select list that displays additional form on change.  If not, just display the form of the one payment method available. ?>
        <legend>
            <small>Payment</small>
        </legend>
        
        <div id="checkout-payment-methods-container"></div>    
    
    </div>

    <?php echo $this->renderView('Shop/Site/Views::checkout/before_submit_button.php'); ?>
    
    <div class="input-group form-group">
        <button id="submit-order" type="submit" class="btn btn-default custom-button btn-lg pull-left">Submit Order</button>
        <div id="submit-working" class="working pull-left hidden">
        	<img src="./minify/Shop/Assets/images/working.gif" alt="Working" />
        	Working ... Please wait
        </div>
        <div class="hidden">
            <div id="submit-working-modal">
                <div class="container">
                    <div class="well">
                        <h3>Submitting your order</h3>
                        <p>This can take up to one minute.  Please don't use your browser back button or it may submit your order twice. Thanks for your patience!</p>
                    </div>
                </div>
            </div>        
        </div>
        <?php \Dsc\System::instance()->get('session')->set('site.shop.checkout.redirect', '/shop/checkout/confirmation'); ?>
    </div>

    <?php echo $this->renderView('Shop/Site/Views::checkout/after_submit_button.php'); ?>
    
</form>

<script>
jQuery(document).ready(function(){
    window.checkout_payment_validation = new ShopValidation('#checkout-payment-form');
    
    jQuery('#checkout-payment-form').on('submit.shop', function(ev){
        var el = jQuery(this); 
        if (!window.checkout_payment_validation.validateForm()) {
            el.data('validated', false);
            ev.preventDefault();
            jQuery('#submit-order').trigger('reset');
            jQuery('<p class="margin-top alert alert-danger validation-errors">Please complete all required fields highlighted in red.</p>').insertBefore('#checkout-payment-methods');
            jQuery('<p class="margin-top alert alert-danger validation-errors">Please complete all required fields highlighted in red.</p>').insertBefore('#submit-order');
            jQuery('body').scrollTo('body', 1000);        
            return false;
        }
        if (el.data('locked')) {
        	ev.preventDefault();
        	jQuery('#submit-order').trigger('reset');
            return false;
        }

        el.data('locked', false);
        el.data('validated', true);
        jQuery('#checkout-payment-methods').css({ opacity: 0.5 });
        //el.submit();
        jQuery('#submit-working-modal').popup('show');
        return true;    
    });

    if (!window.payment_methods_loaded)
    {
		// Appear to be loading.
		jQuery('#checkout-payment-methods').css({ opacity: 0.5 });
		jQuery(this).closest('form').data('locked', true);
		        
		jQuery('#checkout-payment-methods-container').load('./shop/checkout/payment-methods', function ( response, status, xhr )
		{
		    if ( status != "error" ) {
		        window.payment_methods_loaded = true;
		    }
		    
			jQuery('#checkout-payment-form').data('locked', false);
			jQuery('#checkout-payment-methods').css({ opacity: '' });
		});

    }

	var submit_order = jQuery('#submit-order');
	submit_order.on('click', function(e){
		jQuery('.validation-errors').remove();
		jQuery('#system-message-container').remove();
		$this = jQuery( e.target );
		// display working image
		jQuery('#submit-order').addClass('hidden').hide();
		jQuery('#submit-working').removeClass('hidden').show();
		e.preventDefault();
		jQuery('#checkout-payment-form').submit();	
	});
	
	submit_order.on('reset', function(e){
		$this = jQuery( e.target );
		// hide working image
		jQuery('#checkout-payment-methods').css({ opacity: '' });
		jQuery('#submit-order').removeClass('hidden').show();
		jQuery('#submit-working').addClass('hidden').hide();
		jQuery('#submit-working-modal').popup('hide');
	});

	jQuery('#submit-working-modal').popup({
	      color: 'white',
	      opacity: 0.75,
	      transition: '0.3s',
	      scrolllock: true,
	      pagecontainer: '#content-container',
	      escape: false,
	      blur: false
    });	
	
});
</script>

<?php // echo \Dsc\Debug::dump( $cart->autoCoupons() ); ?>