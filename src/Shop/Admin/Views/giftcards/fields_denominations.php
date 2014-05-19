<div class="row">
    <div class="col-md-2">
        
        <h3>Denominations</h3>
        <p class="help-block">Configure the different values for this gift card</p>
        
        <div class="form-group">
            <a class="btn btn-warning" id="add-variant">Add Denomination</a>
        </div>
        
                
    </div>
    <!-- /.col-md-2 -->
                
    <div class="col-md-10">
        <input type="hidden" name="variants[-1]" value="" />
        <?php foreach ((array) $flash->old('attributes') as $attribute_key=>$attribute) { ?>
            <input type="hidden" name="attributes[<?php echo $attribute_key; ?>][title]" class="form-control input-sm" value="<?php echo $flash->old('attributes.'.$attribute_key.'.title'); ?>" />
            <input type="hidden" name="attributes[<?php echo $attribute_key; ?>][id]" value="<?php echo (string) $flash->old('attributes.'.$attribute_key.'.id'); ?>" />
            <input type="hidden" name="attributes[<?php echo $attribute_key; ?>][ordering]" class="form-control input-sm" value="<?php echo $flash->old('attributes.'.$attribute_key.'.ordering'); ?>" placeholder="Sort Order" />
                    
            <?php foreach ((array) $flash->old('attributes.'.$attribute_key.'.options') as $option_key => $option) { ?>
                    
                <?php if ($variant = $item->variantByKey($option['id'])) { ?>
                    <?php $key = $variant['key']; ?>
                    <fieldset class="template clearfix well well-sm" data-variant="<?php echo $variant['key']; ?>">
                        <input data-option='<?php echo (string) $flash->old('attributes.'.$attribute_key.'.options.'.$option_key.'.id'); ?>' type="hidden" name="attributes[<?php echo $attribute_key; ?>][options][<?php echo $option_key; ?>][value]" class="form-control input-sm option-value" value="<?php echo $flash->old('attributes.'.$attribute_key.'.options.'.$option_key.'.value'); ?>" />
                        <input type="hidden" name="attributes[<?php echo $attribute_key; ?>][options][<?php echo $option_key; ?>][id]" value="<?php echo (string) $flash->old('attributes.'.$attribute_key.'.options.'.$option_key.'.id'); ?>" />
                        <input data-option='<?php echo (string) $flash->old('attributes.'.$attribute_key.'.options.'.$option_key.'.id'); ?>' type="hidden" name="attributes[<?php echo $attribute_key; ?>][options][<?php echo $option_key; ?>][ordering]" class="form-control input-sm option-ordering" value="<?php echo $flash->old('attributes.'.$attribute_key.'.options.'.$option_key.'.ordering'); ?>" />
                            
                        <div class="clearfix">
                            <label></label>
                            <a class="remove-variant btn btn-xs btn-danger pull-right" onclick="ShopRemoveVariant(this);" href="javascript:void(0);">
                                <i class="fa fa-times"></i>
                            </a>
                        </div>
                        
                        <div class="form-group clearfix">
                            <div class="col-md-6">
                                <label><small>Title</small></label>
                                <input type="text" name="variants[<?php echo $key; ?>][attribute_title]" class="form-control input-sm" value="<?php echo $flash->old('variants.'.$key.'.attribute_title') ? $flash->old('variants.'.$key.'.attribute_title') : (string) $variant['attribute_title']; ?>" />
                                <input type="hidden" name="variants[<?php echo $key; ?>][id]" value="<?php echo (string) $flash->old('variants.'.$key.'.id') ? (string) $flash->old('variants.'.$key.'.id') : (string) $variant['id']; ?>" />
                                <input type="hidden" name="variants[<?php echo $key; ?>][key]" value="<?php echo $flash->old('variants.'.$key.'.attributes') ? implode("-", (array) $flash->old('variants.'.$key.'.attributes')) : $variant['key']; ?>" />
                            </div>
                            <div class="col-md-2">
                                <label><small>Enabled</small></label>
                                <div class="form-group">
                                    <label class="radio-inline">
                                        <input type="radio" name="variants[<?php echo $key; ?>][enabled]" value="1" <?php if ($flash->old('variants.'.$key.'.enabled')) { echo 'checked'; } ?>> Yes
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="variants[<?php echo $key; ?>][enabled]" value="0" <?php if (!$flash->old('variants.'.$key.'.enabled')) { echo 'checked'; } ?>> No
                                    </label>
                                </div>
                            </div>                            
                            <div class="col-md-2">
                                <label><small>Price</small></label>
                                <input data-option='<?php echo (string) $flash->old('attributes.'.$attribute_key.'.options.'.$option_key.'.id'); ?>' type="text" name="variants[<?php echo $key; ?>][price]" class="form-control input-sm variant-price" value="<?php echo $flash->old('variants.'.$key.'.price') ? $flash->old('variants.'.$key.'.price') : $variant['price']; ?>" placeholder="Price" />
                            </div>                
                            <div class="col-md-2">
                                <label><small>Sort Order</small></label>
                                <input data-option='<?php echo (string) $flash->old('attributes.'.$attribute_key.'.options.'.$option_key.'.id'); ?>' type="text" name="variants[<?php echo $key; ?>][ordering]" class="form-control input-sm variant-ordering" value="<?php echo $flash->old('variants.'.$key.'.ordering') ? $flash->old('variants.'.$key.'.ordering') : $variant['ordering']; ?>" placeholder="Sort Order" />
                            </div>                
                        </div>
            
                    </fieldset>
                <?php } ?>
            <?php } ?>
        <?php } ?>
        
        <div id="new-variants"></div>
    </div>
</div>

<template type="text/template" id="add-variant-template">
    <fieldset class="variant-template template clearfix well well-sm" data-variant="{variant_key}">
        <input data-option='{option_id}' type="hidden" name="attributes[0][options][{variant_key}][value]" class="form-control input-sm option-value" value="" />
        <input type="hidden" name="attributes[0][options][{variant_key}][id]" value="{option_id}" />
        <input data-option='{option_id}' type="hidden" name="attributes[0][options][{variant_key}][ordering]" class="form-control input-sm option-ordering" value="" />
            
        <div class="clearfix">
            <label></label>
            <a class="remove-variant btn btn-xs btn-danger pull-right" onclick="ShopRemoveVariant(this);" href="javascript:void(0);">
                <i class="fa fa-times"></i>
            </a>
        </div>
        
        <div class="form-group clearfix">
            <div class="col-md-6">
                <label><small>Title</small></label>
                <input type="text" name="variants[{variant_key}][attribute_title]" class="form-control input-sm" value="" />
                <input type="hidden" name="variants[{variant_key}][id]" value="" />
                <input type="hidden" name="variants[{variant_key}][key]" value="{option_id}" />
            </div>
            <div class="col-md-2">
                <label><small>Enabled</small></label>
                <div class="form-group">
                    <label class="radio-inline">
                        <input type="radio" name="variants[{variant_key}][enabled]" value="1"> Yes
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="variants[{variant_key}][enabled]" value="0"> No
                    </label>
                </div>
            </div>                            
            <div class="col-md-2">
                <label><small>Price</small></label>
                <input data-option='{option_id}' type="text" name="variants[{variant_key}][price]" class="form-control input-sm variant-price" value="" placeholder="Price" />
            </div>                
            <div class="col-md-2">
                <label><small>Sort Order</small></label>
                <input data-option='{option_id}' type="text" name="variants[{variant_key}][ordering]" class="form-control input-sm variant-ordering" value="" placeholder="Sort Order" />
            </div>                
        </div>

    </fieldset>    
</template>

<script>
jQuery(document).ready(function(){
    window.new_variants = <?php echo count( $flash->old('variants') ); ?>;
    jQuery('#add-variant').click(function(){
        var container = jQuery('#new-variants');
        var template = jQuery('#add-variant-template').html();
        template = template.replace( new RegExp("{variant_key}", 'g'), window.new_variants);

        var uniqueid = null;
        var request = jQuery.get('./admin/shop/uniqueid', function (data) {
        	uniqueid = data;
        	template = template.replace( new RegExp("{option_id}", 'g'), uniqueid);
        	container.append(template);
        	window.new_variants = window.new_variants + 1;
        	ShopChangeVariant();	
        });
    });

    ShopRemoveVariant = function(el) {
        jQuery(el).parents('.template').remove();                            
    }

    ShopChangeVariant = function() {
        jQuery('.variant-price').on('change', function(){
            // find coresponding option-value and update its val
            var val = jQuery(this).val();
            var option_value = jQuery(this).attr('data-option'); 
            jQuery('.option-value[data-option="'+option_value+'"]').val(val);
        });
        jQuery('.variant-ordering').on('change', function(){
            // find coresponding option-ordering and update its val
            var val = jQuery(this).val();
            var option_value = jQuery(this).attr('data-option'); 
            jQuery('.option-ordering[data-option="'+option_value+'"]').val(val);
        });        
    }

    ShopChangeVariant();
});
</script>