
    <div id="checkout-billing-address" class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">Billing Address</h4>
        </div>
        <div class="panel-body billing-address">

            <?php if ($cart->shippingRequired()) { ?>
            <div class="form-group">
                <div class="checkbox">
                    <label>
                      <input type="checkbox" class="same-as-shipping" name="checkout[billing_address][same_as_shipping]" <?php if ($cart->billingSameAsShipping()) { echo 'checked'; } ?>> Same as shipping address
                    </label>
                </div>
            </div>
            <?php } ?>
            
            <?php if ($existing_addresses = \Shop\Models\CustomerAddresses::fetchForId($cart->user_id)) { ?>
            <div class="form-group existing-address">
                <label>Use an existing address or provide a new one below.</label>
                <select name="checkout[billing_address][id]" class="form-control select-address">
                    <option class="new-address" value="">-- New Address --</option>
                <?php foreach ($existing_addresses as $address) { ?>
                    <option <?php if ($cart->{'checkout.billing_address.id'} == (string) $address->id) { echo "selected"; } ?>
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
                <input type="text" class="form-control name billing-name" data-required="true" data-shipping="<?php echo $cart->{'checkout.shipping_address.name'}; ?>" name="checkout[billing_address][name]" value="<?php echo $cart->billingName( $cart->{'checkout.shipping_address.name'} ); ?>" placeholder="Full Name" autocomplete="name" <?php if ($cart->billingSameAsShipping()) { echo 'disabled'; } ?>>
            </div>
            <div class="form-group">
                <input type="text" class="form-control address billing-line_1" data-required="true" data-shipping="<?php echo $cart->{'checkout.shipping_address.line_1'}; ?>" name="checkout[billing_address][line_1]" value="<?php echo $cart->billingLine1( $cart->{'checkout.shipping_address.line_1'} ); ?>" placeholder="Address Line 1" autocomplete="address-line1" <?php if ($cart->billingSameAsShipping()) { echo 'disabled'; } ?>>
            </div>
            <div class="form-group">
                <input type="text" class="form-control address billing-line_2" data-shipping="<?php echo $cart->{'checkout.shipping_address.line_2'}; ?>" name="checkout[billing_address][line_2]" value="<?php echo $cart->billingLine2( $cart->{'checkout.shipping_address.line_2'} ); ?>" placeholder="Address Line 2" autocomplete="address-line2" <?php if ($cart->billingSameAsShipping()) { echo 'disabled'; } ?>>
            </div>
            <div class="form-group">
                <input type="text" class="form-control city billing-city" data-required="true" data-shipping="<?php echo $cart->{'checkout.shipping_address.city'}; ?>" name="checkout[billing_address][city]" value="<?php echo $cart->billingCity( $cart->{'checkout.shipping_address.city'} ); ?>" placeholder="City" autocomplete="locality" <?php if ($cart->billingSameAsShipping()) { echo 'disabled'; } ?>>
            </div>
            <div class="row">
                <div class="form-group col-xs-12 col-sm-12 col-md-6">
                    <select class="form-control region billing-region" data-required="true" data-shipping="<?php echo $cart->{'checkout.shipping_address.region'}; ?>" name="checkout[billing_address][region]" autocomplete="region" <?php if ($cart->billingSameAsShipping()) { echo 'disabled'; } ?>>
                    <option value=""> - Please Select - </option>
                    <?php foreach (\Shop\Models\Regions::byCountry( $cart->billingCountry( $cart->shippingCountry() ) ) as $region) { ?>
                        <option value="<?php echo $region->code; ?>" <?php if ($cart->billingRegion( $cart->{'checkout.shipping_address.region'} ) == $region->code) { echo "selected"; } ?>><?php echo $region->name; ?></option>
                    <?php } ?>
                    </select>                        
                </div>
                <div class="form-group col-xs-12 col-sm-12 col-md-6">
                    <select class="form-control country billing-country" data-required="true" data-shipping="<?php echo $cart->shippingCountry(); ?>" name="checkout[billing_address][country]" autocomplete="country" <?php if ($cart->billingSameAsShipping()) { echo 'disabled'; } ?>>
                    <?php foreach (\Shop\Models\Countries::defaultList() as $country) { ?>
                        <option data-requires_postal_code="<?php echo $country->requires_postal_code; ?>" value="<?php echo $country->isocode_2; ?>" <?php if ($cart->billingCountry( $cart->shippingCountry() ) == $country->isocode_2) { echo "selected"; } ?>><?php echo $country->name; ?></option>
                    <?php } ?>
                    </select>
                </div>            
            </div>            
            <div class="row">
                <div class="form-group col-xs-12 col-sm-12 col-md-4">
                    <input type="text" class="form-control postal-code billing-postal_code" data-required="<?php echo \Shop\Models\Countries::fromCode( $cart->billingCountry( $cart->shippingCountry() ) )->requires_postal_code ? 'true' : 'false'; ?>" data-shipping="<?php echo $cart->{'checkout.shipping_address.postal_code'}; ?>" name="checkout[billing_address][postal_code]" value="<?php echo $cart->billingPostalCode( $cart->{'checkout.shipping_address.postal_code'} ); ?>" placeholder="Postal Code" autocomplete="postal-code" <?php if ($cart->billingSameAsShipping()) { echo 'disabled'; } ?>>
                </div>
                <div class="form-group col-xs-12 col-sm-12 col-md-8">
                    <input type="text" class="form-control phone billing-phone_number" data-required="true" data-shipping="<?php echo $cart->{'checkout.shipping_address.phone_number'}; ?>" name="checkout[billing_address][phone_number]" value="<?php echo $cart->billingPhone( $cart->{'checkout.shipping_address.phone_number'} ); ?>" placeholder="Phone Number" autocomplete="tel" <?php if ($cart->billingSameAsShipping()) { echo 'disabled'; } ?>>
                </div>            
            </div>
        </div>        
    </div>

<script>
ShopGetBillingRegions = function(callback_function) {
    var pm = jQuery('#checkout-billing-address');
    var billing_country = pm.find('.billing-country');
    var regions = pm.find('.billing-region');
    var val = billing_country.val();
    var request = jQuery.ajax({
        type: 'get', 
        url: './shop/address/regions/'+val
    }).done(function(data){
        var response = jQuery.parseJSON( JSON.stringify(data), false);
        if (response.result) {
            regions.find('option').remove();
            regions.append(jQuery("<option></option>").text(jQuery('<span>').html('- Please Select -').text()).val(''));
            var count = response.result.length;
            var n = 0;            
            jQuery.each(response.result, function(index,value){
                regions.append(jQuery("<option></option>").text(jQuery('<span>').html(value.name).text()).val(value.code));
                n++;
                if (n == count) {
                    if ( typeof callback_function === 'function') {
                        callback_function( response );
                    }
                }                
            });
        }
    });

    var selected = billing_country.find('option:selected');
    var requires_postal_code = selected.attr('data-requires_postal_code');
    var postal_code = pm.find('.billing-postal_code');
    if (requires_postal_code == 0) {            
        postal_code.attr('data-required', false);
    } else {
    	postal_code.attr('data-required', true);
    }
}

jQuery(document).ready(function(){
    var pm = jQuery('#checkout-billing-address');
    var billing_country = pm.find('.billing-country');
    var billing_region = pm.find('.billing-region');
    
    billing_country.on('change', function(event, callback){
        ShopGetBillingRegions(callback);
    });
        
    <?php if ($cart->shippingRequired()) { ?>
    pm.find('.same-as-shipping').on('change', function(){
        var el = jQuery(this);
        isChecked = el.is(':checked');
        if (isChecked) {
        	pm.find('.select-address').val('');
        	pm.find('.existing-address').slideUp();
                        
            e = billing_country;
            if (e.length) {
                if (e.val() != e.attr('data-shipping')) {
                    e.val( e.attr('data-shipping') );
                    ShopGetBillingRegions(function(){
                        r = pm.find('.billing-region');
                        r.val( r.attr('data-shipping') );
                    });
                }                
            }
            jQuery('[data-shipping]').each(function() {
                e = jQuery(this); 
                e.val( e.attr('data-shipping') ).prop('disabled', true);
            });
        }
        else {
        	pm.find('.existing-address').slideDown();
        	
            jQuery('[data-shipping]').each(function() {
                jQuery(this).prop('disabled', false); 
            });            
        }
    });

    pm.find('.same-as-shipping').trigger('change');

    pm.find('.select-address').on('change', function(){
        
    	var el = jQuery(this);
        var val = el.val();
        var selected = el.children(":selected");

        if (selected.hasClass('new-address')) {
        	billing_country.val( '<?php echo $cart->shippingCountry(); ?>' );
        } else {
        	billing_country.val( selected.attr('data-country') );
        }
                
    	var el = jQuery(this);
        var val = el.val();

        billing_country.trigger('change', [ function(){
        
            if (selected.hasClass('new-address')) {
                // empty all fields
                pm.find('.billing-name').val( '' );
                pm.find('.billing-line_1').val( '' );
                pm.find('.billing-line_2').val( '' );
                pm.find('.billing-city').val( '' );
                pm.find('.billing-postal_code').val( '' );
                pm.find('.billing-phone_number').val( '' );
                pm.find('.billing-region').val( '' );
                
            } else {
                // populate all fields
                pm.find('.billing-name').val( selected.attr('data-name') );
                pm.find('.billing-line_1').val( selected.attr('data-line_1') );
                pm.find('.billing-line_2').val( selected.attr('data-line_2') );
                pm.find('.billing-city').val( selected.attr('data-city') );
                pm.find('.billing-postal_code').val( selected.attr('data-postal_code') );
                pm.find('.billing-phone_number').val( selected.attr('data-phone_number') );
                pm.find('.billing-region').val( selected.attr('data-region') );
            }

        } ] );
                
    });    
    <?php } ?>
	    
});
</script>