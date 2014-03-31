<h2>
    Checkout <small>Step 2 of 2</small>
</h2>

<form action="./shop/checkout/submit" method="post" id="checkout-billing-form">

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

    <div id="checkout-billing-method" class="well well-sm">
    <?php // TODO Multiple payment methods configured?  If so, display select list that displays additional form on change.  If not, just display the form of the one payment method available. ?>
    
    <?php foreach (["number", "expiryMonth", "expiryYear", "cvv"] as $key) { ?>

        <div class="form-group">
            <label class="control-label" for="card_<?php echo $key; ?>"><?php echo $key; ?></label>
            <div class="controls">
                <input class="form-control" data-required="true" type="text" name="card[<?php echo $key; ?>]" id="card_<?php echo $key; ?>" value="<?php echo $cart->{'card.'.$key}; ?>" />
            </div>
        </div>

    <?php } ?>
    </div>

    <div class="input-group form-group">
        <button type="submit" class="btn btn-default custom-button btn-lg">Submit Order</button>
        <?php \Dsc\System::instance()->get('session')->set('site.shop.checkout.redirect', '/shop/checkout/complete'); ?>
    </div>

</form>

<script>
jQuery(document).ready(function(){
    var validation = new ShopValidation('#checkout-billing-form');
    
    jQuery('#checkout-billing-form').on('submit', function(){
        var el = jQuery(this); 
        if (!validation.validateForm()) {
            return false;
        }
        el.submit();    
    });
});
</script>