<h3 class="">General Settings</h3>
<hr />

<div class="">
    <label>Store Address</label>
    
    <div class="row">
        <div class="col-md-12">
            <label>Line 1</label>
            <input name="store_address[line_1]" placeholder="Address Line 1" value="<?php echo $flash->old('store_address.line_1'); ?>" class="form-control" type="text" />
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <label>Line 2</label>
            <input name="store_address[line_2]" placeholder="Address Line 2" value="<?php echo $flash->old('store_address.line_2'); ?>" class="form-control" type="text" />
        </div>       
    </div>
    <div class="row">
        <div class="col-md-12">
            <label>City</label>
            <input name="store_address[city]" placeholder="City" value="<?php echo $flash->old('store_address.city'); ?>" class="form-control" type="text" />
        </div>       
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-6">
            <label>Region</label>
            <select id="shipping-region" class="form-control region" name="store_address[region]">
            <?php foreach (\Shop\Models\Regions::byCountry( $flash->old('store_address.country') ) as $region) { ?>
                <option value="<?php echo $region->code; ?>" <?php if ($flash->old('store_address.region') == $region->code) { echo "selected"; } ?>><?php echo $region->name; ?></option>
            <?php } ?>
            </select>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-6">
            <label>Country</label>
            <select id="shipping-country" class="form-control country" name="store_address[country]">
            <?php foreach (\Shop\Models\Countries::find() as $country) { ?>
                <option value="<?php echo $country->isocode_2; ?>" <?php if ($flash->old('store_address.country') == $country->isocode_2) { echo "selected"; } ?>><?php echo $country->name; ?></option>
            <?php } ?>
            </select>
        </div>            
    </div>            
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-4">
            <label>Postal Code</label>
            <input type="text" class="form-control postal-code" name="store_address[postal_code]" value="<?php echo $flash->old('store_address.postal_code'); ?>" placeholder="Postal Code">
        </div>
        <div class="col-xs-12 col-sm-12 col-md-8">
            <label>Phone Number</label>
            <input type="text" class="form-control phone" name="store_address[phone_number]" value="<?php echo $flash->old('store_address.phone_number'); ?>" placeholder="Phone Number">
        </div>            
    </div>
</div>
<!-- /.form-group -->

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
});
</script>