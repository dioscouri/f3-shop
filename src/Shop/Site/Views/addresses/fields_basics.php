<?php 
$selected_country = $flash->old('country') ? $flash->old('country') : \Shop\Models\Settings::fetch()->{'store_address.country'};
?>

<div id="address" class="well well-sm">
    <div class="form-group">
        <input type="text" class="form-control name" data-required="true" name="name" value="<?php echo $flash->old('name'); ?>" placeholder="Full Name" autocomplete="name">
    </div>
    <div class="form-group">
        <input type="text" class="form-control address" data-required="true" name="line_1" value="<?php echo $flash->old('line_1'); ?>" placeholder="Address Line 1" autocomplete="address-line1">
    </div>
    <div class="form-group">
        <input type="text" class="form-control address" name="line_2" value="<?php echo $flash->old('line_2'); ?>" placeholder="Address Line 2" autocomplete="address-line2">
    </div>
    <div class="form-group">
        <input type="text" class="form-control city" data-required="true" name="city" value="<?php echo $flash->old('city'); ?>" placeholder="City" autocomplete="locality">
    </div>
    <div class="row">
        <div class="form-group col-xs-12 col-sm-12 col-md-6">
            <select class="form-control region" data-required="true" name="region" id="region" autocomplete="region">
            <?php foreach (\Shop\Models\Regions::byCountry( $selected_country ) as $region) { ?>
                <option value="<?php echo $region->code; ?>" <?php if ($flash->old('region') == $region->code) { echo "selected"; } ?>><?php echo $region->name; ?></option>
            <?php } ?>
            </select>                        
        </div>
        <div class="form-group col-xs-12 col-sm-12 col-md-6">
            <select class="form-control country" data-required="true" name="country" id="country" autocomplete="country">
            <?php foreach (\Shop\Models\Countries::find() as $country) { ?>
                <option value="<?php echo $country->isocode_2; ?>" <?php if ($selected_country == $country->isocode_2) { echo "selected"; } ?>><?php echo $country->name; ?></option>
            <?php } ?>
            </select>
        </div>            
    </div>            
    <div class="row">
        <div class="form-group col-xs-12 col-sm-12 col-md-4">
            <input type="text" class="form-control postal-code" data-required="true" name="postal_code" value="<?php echo $flash->old('postal_code'); ?>" placeholder="Postal Code" autocomplete="postal-code" >
        </div>
        <div class="form-group col-xs-12 col-sm-12 col-md-8">
            <input type="text" class="form-control phone" data-required="true" name="phone_number" value="<?php echo $flash->old('phone_number'); ?>" placeholder="Phone Number" autocomplete="tel">
        </div>            
    </div>            
</div>

<script>
    jQuery(document).ready(function(){
        jQuery('#country').on('change', function(){
            var el = jQuery('#country');
            var regions = jQuery('#region');
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
    
        var validation = new ShopValidation('#address-form');
    
        jQuery('#address-form').on('submit', function(){
            var el = jQuery(this); 
            if (!validation.validateForm()) {
                return false;
            }
            el.submit();    
        });
    });
    </script>