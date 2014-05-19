<div class="row">
    <div class="col-md-2">
        
        <h3>Denominations</h3>
        <p class="help-block">Configure the different values for this gift card</p>
        
        <div class="form-group">
            <a class="btn btn-warning" id="add-denomination">Add Denomination</a>
        </div>
        
                
    </div>
    <!-- /.col-md-2 -->
                
    <div class="col-md-10">
        <input type="hidden" name="variants[-1]" value="" />
        <?php  
        if ( $rebuilt_variants = $item->rebuildVariants() ) 
        {
            foreach ($rebuilt_variants as $variant) 
            {
                $key = $variant['key']; 
                ?>
                    
                <fieldset class="template clearfix well well-sm" data-variant="<?php echo $key; ?>">
                    <div class="clearfix">
                        <label></label>
                        <a class="remove-attribute btn btn-xs btn-danger pull-right" onclick="ShopRemoveVariant(this);" href="javascript:void(0);">
                            <i class="fa fa-times"></i>
                        </a>
                    </div>
                    
                    <div class="form-group clearfix">
                        <div class="col-md-6">
                            <label><small>Title</small></label>
                            <input type="text" name="variants[<?php echo $key; ?>][attribute_title]" class="form-control input-sm" value="<?php echo $flash->old('variants.'.$key.'.attribute_title'); ?>" />
                            <input type="hidden" name="variants[<?php echo $key; ?>][id]" value="<?php echo (string) $flash->old('variants.'.$key.'.id') ? (string) $flash->old('variants.'.$key.'.id') : (string) $variant['id']; ?>" />
                            <input type="hidden" name="variants[<?php echo $key; ?>][key]" value="<?php echo $flash->old('variants.'.$key.'.attributes') ? implode("-", (array) $flash->old('variants.'.$key.'.attributes')) : $variant['key']; ?>" />
                        </div>
                        <div class="col-md-2">
                            <label><small>Price</small></label>
                            <input type="text" name="variants[<?php echo $key; ?>][price]" class="form-control input-sm" value="<?php echo $flash->old('variants.'.$key.'.price'); ?>" placeholder="Price" />
                        </div>                
                        <div class="col-md-2">
                            <label><small>Sort Order</small></label>
                            <input type="text" name="variants[<?php echo $key; ?>][ordering]" class="form-control input-sm" value="<?php echo $flash->old('variants.'.$key.'.ordering'); ?>" placeholder="Sort Order" />
                        </div>                
                    </div>
        
                </fieldset>        
                <?php
            } 
        }
        ?>
    </div>
</div>

<?php // echo \Dsc\Debug::dump( $flash->old('variants') ); ?>
<?php // echo \Dsc\Debug::dump( $rebuilt_variants ); ?>

<?php foreach ((array) $flash->old('attributes') as $key=>$attribute) { ?>
    <input type="hidden" name="attributes[<?php echo $key; ?>][title]" class="form-control input-sm" value="<?php echo $flash->old('attributes.'.$key.'.title'); ?>" />
    <input type="hidden" name="attributes[<?php echo $key; ?>][id]" value="<?php echo (string) $flash->old('attributes.'.$key.'.id'); ?>" />
    <input type="hidden" name="attributes[<?php echo $key; ?>][ordering]" class="form-control input-sm" value="<?php echo $flash->old('attributes.'.$key.'.ordering'); ?>" placeholder="Sort Order" />

    <?php foreach ((array) $flash->old('attributes.'.$key.'.options') as $option_key => $option) { ?>
        <input type="hidden" name="attributes[<?php echo $key; ?>][options][<?php echo $option_key; ?>][value]" class="form-control input-sm" value="<?php echo $flash->old('attributes.'.$key.'.options.'.$option_key.'.value'); ?>" />
        <input type="hidden" name="attributes[<?php echo $key; ?>][options][<?php echo $option_key; ?>][id]" value="<?php echo (string) $flash->old('attributes.'.$key.'.options.'.$option_key.'.id'); ?>" />
        <input type="hidden" name="attributes[<?php echo $key; ?>][options][<?php echo $option_key; ?>][ordering]" class="form-control input-sm" value="<?php echo $flash->old('attributes.'.$key.'.options.'.$option_key.'.ordering'); ?>" />
    <?php } ?>
<?php } ?>

<template type="text/template" id="add-option-template">
    <div class="option-template template panel panel-default" data-option="{option_id}">
        <div class="panel-body">
            <div class="clearfix">
                <a class="remove-option btn btn-xs btn-secondary pull-right" onclick="ShopRemoveOption(this);" href="javascript:void(0);">
                    <i class="fa fa-times"></i>
                </a>
            </div>
    
            <div class="form-group clearfix">
                <div class="col-md-6">
                    <label><small>Value</small></label>
                    <input type="text" name="attributes[{id}][options][{option_id}][value]" class="form-control input-sm" />
                </div>
                <div class="col-md-2">
                    <label><small>Sort Order</small></label>
                    <input type="text" name="attributes[{id}][options][{option_id}][ordering]" class="form-control input-sm" />
                </div>
            </div>                            
        </div>
    </div>
</template>

<script>
jQuery(document).ready(function(){
    window.new_attributes = <?php echo count( $flash->old('attributes') ); ?>;
    jQuery('#add-attribute').click(function(){
        var container = jQuery('#new-attributes');
        var template = jQuery('#add-attribute-template').html();
        template = template.replace( new RegExp("{id}", 'g'), window.new_attributes);
        container.append(template);
        window.new_attributes = window.new_attributes + 1;
        ShopSetupAddOptionButtons();
    });

    ShopSetupAddOptionButtons = function() {
        jQuery('.add-option').off('click.addOption').on('click.addOption', function(){
            var el = jQuery(this);
            var attribute = el.attr('data-attribute');
            if (!window.options[attribute]) {
                window.options[attribute] = 0;
            }
            var container = jQuery('.new-options[data-attribute="'+attribute+'"]');
            var template = jQuery('#add-option-template').html();
            template = template.replace( new RegExp("{id}", 'g'), attribute);
            template = template.replace( new RegExp("{option_id}", 'g'), window.options[attribute]);
            container.append(template);
            window.options[attribute] = window.options[attribute] + 1;
        });                
    }

    ShopSetupAddOptionButtons();

    ShopRemoveVariant = function(el) {
        jQuery(el).parents('.template').remove();                            
    }

    ShopRemoveOption = function(el) {
        jQuery(el).parents('.option-template').remove();                            
    }

});
</script>