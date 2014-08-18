<h3>
    Create Order from Cart <small>Step 1 of 2</small>
</h3>

<form action="./admin/shop/cart/checkout-update/<?php echo $cart->id; ?>" method="post" id="checkout-shipping-form">

    <div id="checkout-shipping-address" class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">Shipping Address</h4>
        </div>
        <div class="panel-body">
        <?php if ($cart->shippingRequired()) { ?>        
            
            <?php if ($existing_addresses = \Shop\Models\CustomerAddresses::fetchForId($cart->user_id)) { ?>
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
                    <option value=""> - Please Select - </option>
                    <?php foreach (\Shop\Models\Regions::byCountry( $cart->shippingCountry() ) as $region) { ?>
                        <option value="<?php echo $region->code; ?>" <?php if ($cart->{'checkout.shipping_address.region'} == $region->code) { echo "selected"; } ?>><?php echo $region->name; ?></option>
                    <?php } ?>
                    </select>                        
                </div>
                <div class="form-group col-xs-12 col-sm-12 col-md-6">
                    <select class="form-control country" data-required="true" name="checkout[shipping_address][country]" id="shipping-country" autocomplete="country">
                    <?php foreach (\Shop\Models\Countries::defaultList() as $country) { ?>
                        <option data-requires_postal_code="<?php echo $country->requires_postal_code; ?>" value="<?php echo $country->isocode_2; ?>" <?php if ($cart->shippingCountry() == $country->isocode_2) { echo "selected"; } ?>><?php echo $country->name; ?></option>
                    <?php } ?>
                    </select>
                </div>            
            </div>            
            <div class="row">
                <div class="form-group col-xs-12 col-sm-12 col-md-4">
                    <input id="postal_code" type="text" class="form-control postal-code" data-required="<?php echo \Shop\Models\Countries::fromCode($cart->shippingCountry())->requires_postal_code ? 'true' : 'false'; ?>" name="checkout[shipping_address][postal_code]" value="<?php echo $cart->{'checkout.shipping_address.postal_code'}; ?>" placeholder="Postal Code" autocomplete="postal-code" >
                </div>
                <div class="form-group col-xs-12 col-sm-12 col-md-8">
                    <input id="phone_number" type="text" class="form-control phone" data-required="true" name="checkout[shipping_address][phone_number]" value="<?php echo $cart->{'checkout.shipping_address.phone_number'}; ?>" placeholder="Phone Number" autocomplete="tel">
                </div>            
            </div>
            
        <?php } else { ?>
            <p>Shipping is not required for this order.</p>
        <?php } ?>
        </div>
    </div>
    
    <?php if ($cart->shippingRequired()) { ?>

    <div id="checkout-shipping-methods" class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">Shipping Method</h4>
        </div>
        <div class="panel-body">
            <div id="checkout-shipping-methods-container"></div>
        </div>     
    </div>
    
    <?php } ?>

    <div id="checkout-comments" class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">Comments (optional)</h4>
        </div>
        <div class="panel-body">        
            <textarea class="form-control" name="checkout[order_comments]"><?php echo $cart->{'checkout.order_comments'}; ?></textarea>
        </div>
    </div>
    
    <?php echo $this->renderView('Shop/Admin/Views::checkout/before_continue_button.php'); ?>

    <div class="form-group">
        <button type="submit" class="btn btn-primary btn-lg">Provide Payment</button>
        <?php \Dsc\System::instance()->get('session')->set('shop.checkout.redirect', '/admin/shop/cart/create-order-payment/' . $cart->id ); ?>
    </div>
    
    <?php echo $this->renderView('Shop/Admin/Views::checkout/after_continue_button.php'); ?>

</form>

<script>
jQuery(document).ready(function(){
    jQuery('#shipping-country').on('change', function(event, callback){
        var el = jQuery('#shipping-country');
        var regions = jQuery('#shipping-region');
        var val = el.val();
        var request = jQuery.ajax({
            type: 'get', 
            url: './shop/address/regions/'+val
        }).done(function(data){
            var lr = jQuery.parseJSON( JSON.stringify(data), false);
            if (lr.result) {
                regions.find('option').remove();
                regions.append(jQuery("<option></option>").text(jQuery('<span>').html('- Please Select -').text()).val(''));
                var count = lr.result.length;
                var n = 0;
                jQuery.each(lr.result, function(index,value){
                    regions.append(jQuery("<option></option>").text(jQuery('<span>').html(value.name).text()).val(value.code));
                    n++;
                    if (n == count) {
                        if (typeof callback === 'function') {
                        	callback(lr);
                        }                   
                    }
                });
            }
        });

        var selected = el.find('option:selected');
        var requires_postal_code = selected.attr('data-requires_postal_code');
        var postal_code = jQuery('#postal_code');
        if (requires_postal_code == 0) {            
            postal_code.attr('data-required', false);
        } else {
        	postal_code.attr('data-required', true);
        }
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
        var selected = el.children(":selected");

        if (selected.attr('id') == 'new-address') {
        	jQuery('#shipping-country').val( '<?php echo $cart->shippingCountry(); ?>' );
        } else {
        	jQuery('#shipping-country').val( selected.attr('data-country') );
        }
        
    	jQuery('#shipping-country').trigger('change', [ function(){
            
            if (selected.attr('id') == 'new-address') {
                // empty all fields
                jQuery('#name').val( '' );
                jQuery('#line_1').val( '' );
                jQuery('#line_2').val( '' );
                jQuery('#city').val( '' );
                jQuery('#postal_code').val( '' );
                jQuery('#phone_number').val( '' );
                jQuery('#shipping-region').val( '' );
                
            } else {
                // populate all fields
                jQuery('#name').val( selected.attr('data-name') );
                jQuery('#line_1').val( selected.attr('data-line_1') );
                jQuery('#line_2').val( selected.attr('data-line_2') );
                jQuery('#city').val( selected.attr('data-city') );
                jQuery('#postal_code').val( selected.attr('data-postal_code') );
                jQuery('#phone_number').val( selected.attr('data-phone_number') );
                jQuery('#shipping-region').val( selected.attr('data-region') );
            }
                        	
        } ] );
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
    	            url: './admin/shop/cart/checkout-update/<?php echo $cart->id; ?>',
    	            data: form_data
    	        });        
    	        
    			// Success.
    			request.done(function ()
    			{
    				jQuery('#checkout-shipping-methods-container').load('./admin/shop/cart/shipping-methods/<?php echo $cart->id; ?>', function ()
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