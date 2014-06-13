<h2>
    Checkout <small>Step 1 of 2</small>
</h2>

<form action="./shop/checkout/update" method="post" id="checkout-shipping-form">

    <div id="checkout-shipping-address" class="well well-sm">
        <legend>
            <small>Shipping Address</small>
        </legend>
        <?php if ($cart->shippingRequired()) { ?>        
            
            <?php if ($existing_addresses = \Shop\Models\CustomerAddresses::fetch()) { ?>
            <div class="form-group">
                <label>Use an existing address or provide a new one below.</label>
                <select name="checkout[shipping_address][id]" class="form-control" id="select-address">
                    <option id="new-address" value="">-- New Address --</option>
                <?php foreach ($existing_addresses as $address) { ?>
                    <option <?php if ($cart->{'checkout.shipping_address.id'} == (string) $address->id) { echo "selected"; } ?>
                        value="<?php echo $address->id; ?>" 
                        data-name="<?php echo htmlspecialchars( $address->name ); ?>"
                        data-line_1="<?php echo htmlspecialchars( $address->line_1 ); ?>"
                        data-line_2="<?php echo htmlspecialchars( $address->line_2 ); ?>"
                        data-city="<?php echo htmlspecialchars( $address->city ); ?>"
                        data-region="<?php echo htmlspecialchars( $address->region ); ?>"
                        data-country="<?php echo htmlspecialchars( $address->country ); ?>"
                        data-postal_code="<?php echo htmlspecialchars( $address->postal_code ); ?>"
                        data-phone_number="<?php echo htmlspecialchars( $address->phone_number ); ?>"
                    >
                        <?php echo $address->asString(', '); ?>
                    </option>
                <?php } ?>
                </select>
                <hr/>
            </div>
            <?php } ?>
            
            <div class="form-group">
                <input id="name" type="text" class="form-control name" data-required="true" name="checkout[shipping_address][name]" value="<?php echo $cart->{'checkout.shipping_address.name'}; ?>" placeholder="Full Name" autocomplete="name">
            </div>
            <div class="form-group">
                <input id="line_1" type="text" class="form-control address" data-required="true" name="checkout[shipping_address][line_1]" value="<?php echo $cart->{'checkout.shipping_address.line_1'}; ?>" placeholder="Address Line 1" autocomplete="address-line1">
            </div>
            <div class="form-group">
                <input id="line_2" type="text" class="form-control address" name="checkout[shipping_address][line_2]" value="<?php echo $cart->{'checkout.shipping_address.line_2'}; ?>" placeholder="Address Line 2" autocomplete="address-line2">
            </div>
            <div class="form-group">
                <input id="city" type="text" class="form-control city" data-required="true" name="checkout[shipping_address][city]" value="<?php echo $cart->{'checkout.shipping_address.city'}; ?>" placeholder="City" autocomplete="locality">
            </div>
            <div class="row">
                <div class="form-group col-xs-12 col-sm-12 col-md-6">
                    <select class="form-control region" data-required="true" name="checkout[shipping_address][region]" id="shipping-region" autocomplete="region">
                    <?php foreach (\Shop\Models\Regions::byCountry( $cart->shippingCountry() ) as $region) { ?>
                        <option value="<?php echo $region->code; ?>" <?php if ($cart->{'checkout.shipping_address.region'} == $region->code) { echo "selected"; } ?>><?php echo $region->name; ?></option>
                    <?php } ?>
                    </select>                        
                </div>
                <div class="form-group col-xs-12 col-sm-12 col-md-6">
                    <select class="form-control country" data-required="true" name="checkout[shipping_address][country]" id="shipping-country" autocomplete="country">
                    <?php foreach (\Shop\Models\Countries::defaultList() as $country) { ?>
                        <option value="<?php echo $country->isocode_2; ?>" <?php if ($cart->shippingCountry() == $country->isocode_2) { echo "selected"; } ?>><?php echo $country->name; ?></option>
                    <?php } ?>
                    </select>
                </div>            
            </div>            
            <div class="row">
                <div class="form-group col-xs-12 col-sm-12 col-md-4">
                    <input id="postal_code" type="text" class="form-control postal-code" data-required="true" name="checkout[shipping_address][postal_code]" value="<?php echo $cart->{'checkout.shipping_address.postal_code'}; ?>" placeholder="Postal Code" autocomplete="postal-code" >
                </div>
                <div class="form-group col-xs-12 col-sm-12 col-md-8">
                    <input id="phone_number" type="text" class="form-control phone" data-required="true" name="checkout[shipping_address][phone_number]" value="<?php echo $cart->{'checkout.shipping_address.phone_number'}; ?>" placeholder="Phone Number" autocomplete="tel">
                </div>            
            </div>
            
        <?php } else { ?>
            <p>Shipping is not required for this order.</p>
        <?php } ?>
    </div>
    
    <?php if ($cart->shippingRequired()) { ?>

    <div id="checkout-shipping-methods" class="well well-sm">
        <legend>
            <small>Shipping Method</small>
        </legend>
        
        <div id="checkout-shipping-methods-container"></div>     
    </div>
    
    <?php } ?>

    <div id="checkout-comments" class="well well-sm">
        <legend><small>Comments (optional)</small></legend>
        <textarea class="form-control" name="checkout[order_comments]"><?php echo $cart->{'checkout.order_comments'}; ?></textarea>
    </div>
    
    <?php echo $this->renderView('Shop/Site/Views::checkout/before_continue_button.php'); ?>

    <div class="input-group form-group">
        <button type="submit" class="btn btn-default custom-button btn-lg">Continue</button>
        <?php \Dsc\System::instance()->get('session')->set('site.shop.checkout.redirect', '/shop/checkout/payment'); ?>
    </div>
    
    <?php echo $this->renderView('Shop/Site/Views::checkout/after_continue_button.php'); ?>

</form>

<script>
jQuery(document).ready(function(){
    jQuery('#shipping-country').on('change', function(){
        var el = jQuery('#shipping-country');
        var regions = jQuery('#shipping-region');
        var val = el.val();
        var request = jQuery.ajax({
            type: 'get', 
            url: './shop/address/regions/'+val
        }).done(function(data){
            var lr = jQuery.parseJSON( JSON.stringify(data), false);
            if (lr.result) {
                jQuery('#parents').html(lr.result);
                regions.find('option').remove();
                jQuery.each(lr.result, function(index,value){
                    regions.append(jQuery("<option></option>").text(jQuery('<span>').html(value.name).text()).val(value.code));
                });
            }
        });        
    });

    var validation = new ShopValidation('#checkout-shipping-form');

    jQuery('#checkout-shipping-form').on('submit', function(){
        var el = jQuery(this); 
        if (!validation.validateForm()) {
            return false;
        }
        //el.submit();
        return true;
    });

    jQuery('#select-address').on('change', function(){
    	var el = jQuery(this);
        var val = el.val();
        
        if (el.children(":selected").attr('id') == 'new-address') {
            // empty all fields
            jQuery('#shipping-country').val( '<?php echo $cart->shippingCountry(); ?>' );
            jQuery('#name').val( '' );
            jQuery('#line_1').val( '' );
            jQuery('#line_2').val( '' );
            jQuery('#city').val( '' );
            jQuery('#postal_code').val( '' );
            jQuery('#phone_number').val( '' );
            jQuery('#shipping-region').val( '' );
            
        } else {
            // populate all fields
            var selected = el.children(":selected");
            jQuery('#shipping-country').val( selected.attr('data-country') );
            jQuery('#name').val( selected.attr('data-name') );
            jQuery('#line_1').val( selected.attr('data-line_1') );
            jQuery('#line_2').val( selected.attr('data-line_2') );
            jQuery('#city').val( selected.attr('data-city') );
            jQuery('#postal_code').val( selected.attr('data-postal_code') );
            jQuery('#phone_number').val( selected.attr('data-phone_number') );
            jQuery('#shipping-region').val( selected.attr('data-region') );
        }
                
    });

    if (!window.shipping_methods_loaded)
    {
    	// Trigger shipping method reload on shipping info change.
    	jQuery('#checkout-shipping-address').find('input,select,textarea').on('keyup', function ()
    	{
    		clearTimeout(window.checkout_shipping_timer);
    		window.checkout_shipping_timer = setTimeout("jQuery('#checkout-shipping-methods').trigger('reload');", 500);
    	});
    	
    	jQuery('#checkout-shipping-address').find('input,select,textarea').on('change', function ()
    	{
    		clearTimeout(window.checkout_shipping_timer);
    		window.checkout_shipping_timer = setTimeout("jQuery('#checkout-shipping-methods').trigger('reload');", 500);
    	});

		// Set original zip
		if (!jQuery(this).data('shipping-params'))
		{
			jQuery(this).data('shipping-params', jQuery('#checkout-shipping-info input.postal-code').val() + jQuery('#checkout-shipping-address select.region').val() + jQuery('#checkout-shipping-info select.country').val());
		}
    	
    	// Reload event.
    	jQuery('#checkout-shipping-methods').on('reload', function ()
    	{
    		// Get zip from checkout form.
    		var zip = jQuery('#checkout-shipping-address input.postal-code').val();
    		var region = jQuery('#checkout-shipping-address select.region').val();
    		var country = jQuery('#checkout-shipping-address select.country').val();

    		//if (!zip || !region || !country) return;
    		
    		// Zip changed?
    		if (jQuery(this).data('shipping-params') != zip+region+country)
    		{
    			jQuery(this).data('shipping-params', zip+region+country);
    			
    			// Appear to be loading.
    			jQuery('#checkout-shipping-methods').css({ opacity: 0.5 });
    			jQuery(this).closest('form').data('locked', true);
    			
    			// Get shipping data for update.
    			var form_data = jQuery('#checkout-shipping-form').serialize();

    	        var request = jQuery.ajax({
    	            type: 'post', 
    	            url: './shop/checkout/update',
    	            data: form_data
    	        });        
    	        
    			// Success.
    			request.done(function ()
    			{
    				jQuery('#checkout-shipping-methods-container').load('./shop/checkout/shipping-methods', function ()
    				{
    					jQuery(this).closest('form').data('locked', false);
    					jQuery('#checkout-shipping-methods').css({ opacity: '' });
    				});
    			});
    			
    			// Something went wrong.
    			request.error(function (error, text)
    			{
    				jQuery(this).closest('form').data('locked', false);
    				jQuery('#checkout-shipping-methods').css({ opacity: '' });
    			});
    		}
    	});
    	
    	// Remember this was loaded once.
    	window.shipping_methods_loaded = true;
    }

    jQuery('#checkout-shipping-methods').trigger('reload');
});
</script>

<?php // echo \Dsc\Debug::dump( $cart->autoCoupons() ); ?>