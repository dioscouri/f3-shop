<h2>
    Checkout <small>Step 1 of 2</small>
</h2>

<form action="./shop/checkout/update" method="post" id="checkout-shipping-form">

    <div id="checkout-shipping-address" class="well well-sm">
        <legend>
            <small>Shipping Address</small>
        </legend>
        <?php if ($cart->shipping_required()) { ?>        
            <p>Shipping Required? If so, display form for shipping address. If not, say so. <small>(Store address in cart and autocomplete when/if returning to this page later.)</small></p>
            <p>If account has stored addresses, display a select list and when one is selected, prefill the form fields.</p>
            
            <div class="form-group">
                <input type="text" class="form-control required" name="shipping_address[name]" value="<?php echo $cart->{'shipping_address.name'}; ?>" placeholder="Full Name" >
            </div>
            <div class="form-group">
                <input type="text" class="form-control required" name="shipping_address[line_1]" value="<?php echo $cart->{'shipping_address.line_1'}; ?>" placeholder="Address Line 1" >
            </div>
            <div class="form-group">
                <input type="text" class="form-control" name="shipping_address[line_2]" value="<?php echo $cart->{'shipping_address.line_2'}; ?>" placeholder="Address Line 2" >
            </div>
            <div class="form-group">
                <input type="text" class="form-control required" name="shipping_address[city]" value="<?php echo $cart->{'shipping_address.city'}; ?>" placeholder="City" >
            </div>            
            <div class="form-group">
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <select class="form-control" name="shipping_address[region]" id="shipping-region">
                        <?php foreach (\Shop\Models\Regions::byCountry( $cart->selected_country ) as $region) { ?>
                            <option value="<?php echo $region->code; ?>" <?php if ($cart->{'shipping_address.region'} == $region->code) { echo "selected"; } ?>><?php echo $region->name; ?></option>
                        <?php } ?>
                        </select>                        
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <select class="form-control" name="shipping_address[country]" id="shipping-country">
                        <?php foreach (\Shop\Models\Countries::find() as $country) { ?>
                            <option value="<?php echo $country->isocode_2; ?>" <?php if ($cart->selected_country == $country->isocode_2) { echo "selected"; } ?>><?php echo $country->name; ?></option>
                        <?php } ?>
                        </select>
                    </div>            
                </div>            
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-4">
                        <input type="text" class="form-control required" name="shipping_address[postal_code]" value="<?php echo $cart->{'shipping_address.postal_code'}; ?>" placeholder="Postal Code" >
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-8">
                        <input type="text" class="form-control required" name="shipping_address[phone_number]" value="<?php echo $cart->{'shipping_address.phone_number'}; ?>" placeholder="Phone Number" >
                    </div>            
                </div>                        
                
            </div>
            
        <?php } else { ?>
            <p>Shipping is not required for this order.</p>
        <?php } ?>
    </div>

    <div id="checkout-shipping-method" class="well well-sm">
    Shipping required and address provided? If so, display shipping method options. If not, hide entire well or display "provide address" message
    </div>

    <div id="checkout-comments" class="well well-sm">
        <legend><small>Comments (optional)</small></legend>
        <textarea class="form-control" name="order_comments"><?php echo $cart->{'order_comments'}; ?></textarea>
    </div>

    <div class="input-group form-group">
        <button type="submit" class="btn btn-default custom-button btn-lg">Continue</button>
        <p>validate on click</p>
        <?php \Dsc\System::instance()->get('session')->set('site.shop.checkout.redirect', '/shop/checkout/billing'); ?>
    </div>

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
        el.submit();    
    });
    
});
</script>