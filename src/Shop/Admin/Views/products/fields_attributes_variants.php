<div class="row">
    <div class="col-md-2">
        
        <h3>Variants</h3>
        <p class="help-block">Some helpful text</p>
                
    </div>
    <!-- /.col-md-2 -->
                
    <div class="col-md-10">
        
        
            <?php  
            if ( $rebuilt_variants = $item->rebuildVariants() ) 
            {
                ?>
                <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-md-3">
                            Attribute Set
                        </div>
                        <div class="col-md-3">
                            
                        </div>
                        <div class="col-md-2">
                            
                        </div>
                        <div class="col-md-2">
                            
                        </div>
                        <div class="col-md-2">
                            
                        </div>
                    </div>                
                </div>
                
                <div class="list-group">
                    
                <?php
                foreach ($rebuilt_variants as $variant) 
                {
                    $key = $variant['key']; 
                    ?>
                    <div class="list-group-item" data-variant="<?php echo $key; ?>">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-3">
                                    <?php echo !empty($variant['titles']) ? implode("&nbsp;|&nbsp;", (array) $variant['titles']) : null; ?>
                                    <input type="hidden" name="variants[<?php echo $key; ?>][id]" value="<?php echo $flash->old('variants.'.$key.'.id'); ?>" />
                                    <input type="hidden" name="variants[<?php echo $key; ?>][key]" value="<?php echo implode("-", $flash->old('variants.'.$key.'.attributes')); ?>" />
                                    <input type="hidden" name="variants[<?php echo $key; ?>][title]" value="<?php echo implode("&nbsp;|&nbsp;", (array) $variant['titles']); ?>" />
                                    <input type="hidden" name="variants[<?php echo $key; ?>][attributes]" value="<?php echo htmlspecialchars( json_encode( $flash->old('variants.'.$key.'.attributes') ) ); ?>" />
                                </div>
                                <div class="col-md-9">
                                    <div class="row">
                                    
                                        <div class="col-md-7">
                                            <label><small>SKU</small></label>
                                            <input type="text" name="variants[<?php echo $key; ?>][sku]" class="form-control input-sm" value="<?php echo $flash->old('variants.'.$key.'.sku'); ?>" />
                                        </div>
                                        <div class="col-md-2">
                                            <label><small>Price</small></label>
                                            <input type="text" name="variants[<?php echo $key; ?>][price]" class="form-control input-sm" value="<?php echo $flash->old('variants.'.$key.'.price'); ?>" />
                                        </div>
                                        <div class="col-md-2">
                                            <label><small>Quantity</small></label>
                                            <input type="text" name="variants[<?php echo $key; ?>][quantity]" class="form-control input-sm" value="<?php echo $flash->old('variants.'.$key.'.quantity'); ?>" />
                                        </div>
                                        <div class="col-md-1">
                                            <p class="help-block"><small>&nbsp;</small></p>
                                            <a class="btn btn-link btn-sm" href="javascript:void(0);" onclick="ShopToggleVariantFields(this);">edit</a>
                                        </div>
                                                                            
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="variant-fields">
                            <div class="row">
                                <div class="col-md-3">
                                    <label><small>ID</small></label>
                                    <p><?php echo $flash->old('variants.'.$key.'.id'); ?></p>
                                </div>
                                <div class="col-md-9">

                                    <div class="row">
                                        <div class="col-md-3">
                                            <label><small>Model</small></label>
                                            <input type="text" name="variants[<?php echo $key; ?>][model_number]" class="form-control input-sm" value="<?php echo $flash->old('variants.'.$key.'.model_number'); ?>" />
                                        </div>
                                        <div class="col-md-2">
                                            <label><small>UPC</small></label>
                                            <input type="text" name="variants[<?php echo $key; ?>][upc]" class="form-control input-sm" value="<?php echo $flash->old('variants.'.$key.'.upc'); ?>" />
                                        </div>
                                        <div class="col-md-2">
                                            <label><small>Weight</small></label>
                                            <input type="text" name="variants[<?php echo $key; ?>][weight]" class="form-control input-sm" value="<?php echo $flash->old('variants.'.$key.'.weight'); ?>" />
                                        </div>
                                        <div class="col-md-5">
                                            <label><small>Image Slug</small></label>
                                            <input type="text" name="variants[<?php echo $key; ?>][image]" class="form-control input-sm" value="<?php echo $flash->old('variants.'.$key.'.image'); ?>" />
                                        </div>
                                    </div>
                                        
                                
                                </div>
                            </div>
                        </div>                        
                    </div>
                    <?php
                }
                ?>
                </div>
                </div>
                <?php
            } else { 
            ?>
                <div class="alert alert-warning">Variants will be created after you 1) add Attributes and 2) Save the product</div>
            <?php } ?>
        
    
    </div>
    <!-- /.col-md-10 -->
    
</div>
<!-- /.row -->

<script>
jQuery(document).ready(function(){
    jQuery('.variant-fields').slideUp();
});

ShopToggleVariantFields = function(el) {
    var fields = jQuery(el).parents('.list-group-item').find('.variant-fields');
    if (fields.is(':hidden')) {
        jQuery('.variant-fields').slideUp();
        fields.slideDown();        
    } else {
        jQuery('.variant-fields').slideUp();
    }
}
</script>