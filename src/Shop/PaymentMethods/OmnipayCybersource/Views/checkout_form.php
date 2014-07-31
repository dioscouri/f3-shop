<div>
        <div class="add-new-card">
            <div class="row">
                <div class="form-group col-xs-12 col-sm-12 col-md-5">
                    <input type="text" class="form-control number new-card-number" data-numeric="true" data-required="true" name="card[number]" value="" placeholder="Card Number" autocomplete="off">
                </div>
                <div class="form-group col-xs-12 col-sm-12 col-md-4">
                    <select class="form-control month new-card-month" data-required="true" name="card[month]">
                    <?php 
                    for ($i=1; $i<=12; $i++) {
                        $month_num = str_pad( $i, 2, 0, STR_PAD_LEFT );
                        $month_name = date('F', strtotime( date('Y') . '-' . $month_num ) );
                        ?>
                        <option value="<?php echo $month_num; ?>"><?php echo $month_num . ' - ' . $month_name; ?></option>
                    <?php } ?>
                    </select>        
                </div>
                <div class="form-group col-xs-12 col-sm-12 col-md-3">
                    <select class="form-control year new-card-year" data-required="true" name="card[year]">
                    <?php for ($n=date('Y'); $n<date('Y')+25; $n++) { ?>
                    	<option value="<?php echo $n; ?>"><?php echo $n; ?></option>
                    <?php } ?>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-xs-12 col-sm-12 col-md-4">
                    <input type="text" class="form-control cvv new-card-csc" data-numeric="true" data-required="true" name="card[csc]" value="" placeholder="Security Code" autocomplete="off">
                </div>
                <div class="form-group col-xs-12 col-sm-12 col-md-8">
                    <img src="./minify/Shop/Assets/images/cvv_mc_visa.gif" />
                    <img src="./minify/Shop/Assets/images/cvv_amex.gif" />
                </div>
            </div>
        </div>
        
        <div class="billing-address">
            <legend><small>Billing Address</small></legend>
            <?php if ($cart->shippingRequired()) { ?>
            <div class="form-group">
                <div class="checkbox">
                    <label>
                      <input type="checkbox" class="same-as-shipping" name="checkout[billing_address][same_as_shipping]" <?php if ($cart->billingSameAsShipping()) { echo 'checked'; } ?>> Same as shipping address
                    </label>
                </div>
            </div>
            <?php } ?>
            
            <?php if ($existing_addresses = \Shop\Models\CustomerAddresses::fetch()) { ?>
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

<template id="omnipay-cybersource-template" data-action="<?php echo $request->getEndpoint(); ?>">
    <form id="omnipay-cybersource-form" action="<?php echo $request->getEndpoint(); ?>" method="post">
        <?php foreach ($signed_fields as $key=>$value) { ?>
            <input type="hidden" name="<?php echo $key; ?>" value="<?php echo $value; ?>" />
        <?php } ?>
        <input type="hidden" name="signature" value="<?php echo $signature; ?>" />
        
        <input type="hidden" name="bill_to_surname" value="{bill_to_surname}" />
        <input type="hidden" name="bill_to_forename" value="{bill_to_forename}" />
        <input type="hidden" name="bill_to_address_line1" value="{bill_to_address_line1}" />
        <input type="hidden" name="bill_to_address_line2" value="{bill_to_address_line2}" />
        <input type="hidden" name="bill_to_address_city" value="{bill_to_address_city}" />
        <input type="hidden" name="bill_to_address_state" value="{bill_to_address_state}" />
        <input type="hidden" name="bill_to_address_country" value="{bill_to_address_country}" />
        <input type="hidden" name="bill_to_address_postal_code" value="{bill_to_address_postal_code}" />
        <input type="hidden" name="bill_to_phone" value="{bill_to_phone}" />
        
        <input type="hidden" name="card_number" value="{card_number}" />
        <input type="hidden" name="card_cvn" value="{card_cvn}" />
        <input type="hidden" name="card_expiry_date" value="{card_expiry_date}" />
        <input type="hidden" name="card_type" value="{card_type}" />                
    </form>    
</template>

<script>
OmnipayCybersourceGetBillingRegions = function(callback_function) {
    var pm = jQuery('#panel-<?php echo $pm->slug; ?>');
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

    var selected = el.find('option:selected');
    var requires_postal_code = selected.attr('data-requires_postal_code');
    var postal_code = pm.find('.billing-postal_code');
    if (requires_postal_code == 0) {            
        postal_code.attr('data-required', false);
    } else {
    	postal_code.attr('data-required', true);
    }
}

jQuery(document).ready(function(){
    var pm = jQuery('#panel-<?php echo $pm->slug; ?>');
    var billing_country = pm.find('.billing-country');
    var billing_region = pm.find('.billing-region');
        
    jQuery('[data-numeric]').payment('restrictNumeric');
    
    billing_country.on('change', function(event, callback){
        OmnipayCybersourceGetBillingRegions(callback);
    });
        
    <?php if ($cart->shippingRequired()) { ?>
    jQuery('#panel-<?php echo $pm->slug; ?>').find('.same-as-shipping').on('change', function(){
        var el = jQuery(this);
        isChecked = el.is(':checked');
        if (isChecked) {
        	pm.find('.select-address').val('');
        	pm.find('.existing-address').slideUp();
                        
            e = billing_country;
            if (e.length) {
                if (e.val() != e.attr('data-shipping')) {
                    e.val( e.attr('data-shipping') );
                    OmnipayCybersourceGetBillingRegions(function(){
                        r = jQuery('#panel-<?php echo $pm->slug; ?>').find('.billing-region');
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

    jQuery('#checkout-payment-form').on('submit.amrita_shipping', function() {
        if (jQuery(this).data('validated')) {
            jQuery(this).find('[data-shipping]').prop('disabled', false);
        }        
    });

    jQuery('#checkout-payment-form').on('submit.amrita', function(ev){
        var el = jQuery(this);

        selected_payment_method = el.find('input[name=payment_method]:checked').val();
        if (selected_payment_method != 'omnipay.cybersource') {
            return true;
        }        

        var name_arr = pm.find('.billing-name').val().split(" ");
        var bill_to_surname = name_arr.pop();
        var bill_to_forename = name_arr.join(" ");
        var bill_to_address_line1 = pm.find('.billing-line_1').val();
        var bill_to_address_line2 = pm.find('.billing-line_2').val();
        var bill_to_address_city = pm.find('.billing-city').val();
        var bill_to_address_state = pm.find('.billing-region').val();
        var bill_to_address_country = pm.find('.billing-country').val();
        var bill_to_address_postal_code = pm.find('.billing-postal_code').val();
        var bill_to_phone = pm.find('.billing-phone_number').val();

        var card_number = pm.find('.new-card-number').val();
        var card_cvn = pm.find('.new-card-csc').val();
        var card_expiry_date = pm.find('.new-card-month').val()+'-'+pm.find('.new-card-year').val();

        switch (jQuery.payment.cardType(card_number)) {
            case "visa":
                var card_type = '001';
                break;
            case "mastercard":
                var card_type = '002';
                break;
            case "amex":
                var card_type = '003';
                break;
            case "dinersclub":
                var card_type = '005';
                break;
            case "discover":
                var card_type = '004';
                break;
            case "unionpay":
                break;
            case "jcb":
                var card_type = '007';
                break;
            case "visaelectron":
                var card_type = '033';
                break;
            case "maestro":
                var card_type = '024';
                break;
            case "forbrugsforeningen":
                break;
            case "dankort":
                var card_type = '034';
                break;
        }
                

        // build our form
        var template = jQuery('#omnipay-cybersource-template').html();
        template = template.replace( new RegExp("{bill_to_surname}", 'g'), bill_to_surname);
        template = template.replace( new RegExp("{bill_to_forename}", 'g'), bill_to_forename);
        template = template.replace( new RegExp("{bill_to_address_line1}", 'g'), bill_to_address_line1);
        template = template.replace( new RegExp("{bill_to_address_line2}", 'g'), bill_to_address_line2);
        template = template.replace( new RegExp("{bill_to_address_city}", 'g'), bill_to_address_city);
        template = template.replace( new RegExp("{bill_to_address_state}", 'g'), bill_to_address_state);
        template = template.replace( new RegExp("{bill_to_address_country}", 'g'), bill_to_address_country);
        template = template.replace( new RegExp("{bill_to_address_postal_code}", 'g'), bill_to_address_postal_code);
        template = template.replace( new RegExp("{bill_to_phone}", 'g'), bill_to_phone);
        template = template.replace( new RegExp("{card_number}", 'g'), card_number);
        template = template.replace( new RegExp("{card_cvn}", 'g'), card_cvn);
        template = template.replace( new RegExp("{card_expiry_date}", 'g'), card_expiry_date);
        template = template.replace( new RegExp("{card_type}", 'g'), card_type);

        // then submit our form instead
        console.log('Submit our form instead');
                
        jQuery('body').append(template);        
        jQuery('#omnipay-cybersource-form').submit()

        console.log('omnipay.cybersource returning false');
		jQuery('body').scrollTo('body', 1000);
		
		jQuery(this).closest('form').data('locked', true);
    	ev.preventDefault();
        return false;
    });
	    
});
</script>