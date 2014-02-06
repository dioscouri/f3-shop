<div class="row">
    <div class="col-md-2">
        
        <h3>Variants</h3>
        <p class="help-block">Some helpful text</p>
                
    </div>
    <!-- /.col-md-2 -->
                
    <div class="col-md-10">
        
        <div class="panel panel-default">
            <?php 
            if ($variants = \Tienda\Admin\Models\Products::instance()->getVariants( $flash->get('old') )) 
            {
                ?>
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
                foreach ($variants as $key=>$variant) 
                {
                    ?>
                    <div class="list-group-item" data-variant="<?php echo $key; ?>">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-3">
                                    <?php echo !empty($variant['titles']) ? implode("&nbsp;|&nbsp;", (array) $variant['titles']) : null; ?>
                                    <input type="hidden" name="variants[<?php echo $key; ?>][id]" value="<?php echo $key; ?>" />
                                    <input type="hidden" name="variants[<?php echo $key; ?>][attributes]" value="<?php echo htmlspecialchars( json_encode( $variant['attributes'] ) ); ?>" />
                                </div>
                                <div class="col-md-9">
                                    <div class="row">
                                    
                                        <div class="col-md-3">
                                            <label><small>SKU</small></label>
                                            <input type="text" name="variants[<?php echo $key; ?>][sku]" class="form-control input-sm" value="<?php echo $flash->old('variants.'.$key.'.sku'); ?>" />
                                        </div>
                                        <div class="col-md-3">
                                            <label><small>Price</small></label>
                                            <input type="text" name="variants[<?php echo $key; ?>][price]" class="form-control input-sm" value="<?php echo $flash->old('variants.'.$key.'.price'); ?>" />
                                        </div>
                                        <div class="col-md-3">
                                            <label><small>Quantity</small></label>
                                            <input type="text" name="variants[<?php echo $key; ?>][quantity]" class="form-control input-sm" value="<?php echo $flash->old('variants.'.$key.'.quantity'); ?>" />
                                        </div>
                                        <div class="col-md-3">
                                            <p class="help-block"><small>&nbsp;</small></p>
                                            <a class="btn btn-link btn-sm" href="javascript:void(0);" onclick="TiendaToggleVariantFields(this);">edit</a>
                                        </div>
                                                                            
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="variant-fields">
                            <div class="row">
                                <div class="col-md-3">
                                    <label><small>ID</small></label>
                                    <p><?php echo $key; ?></p>
                                </div>
                                <div class="col-md-9">

                                    <div class="row">
                                        <div class="col-md-3">
                                            <label><small>Model</small></label>
                                            <input type="text" name="variants[<?php echo $key; ?>][model_number]" class="form-control input-sm" value="<?php echo $flash->old('variants.'.$key.'.model_number'); ?>" />
                                        </div>
                                        <div class="col-md-3">
                                            <label><small>UPC</small></label>
                                            <input type="text" name="variants[<?php echo $key; ?>][upc]" class="form-control input-sm" value="<?php echo $flash->old('variants.'.$key.'.upc'); ?>" />
                                        </div>
                                        <div class="col-md-3">
                                            <label><small>Weight</small></label>
                                            <input type="text" name="variants[<?php echo $key; ?>][weight]" class="form-control input-sm" value="<?php echo $flash->old('variants.'.$key.'.weight'); ?>" />
                                        </div>
                                        <div class="col-md-3">
                                            <label><small>Image URL</small></label>
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
                
                <?php
            } 
            ?>
        </div>
    
    </div>
    <!-- /.col-md-10 -->
    
</div>
<!-- /.row -->

<script>
jQuery(document).ready(function(){
    jQuery('.variant-fields').slideUp();
});

TiendaToggleVariantFields = function(el) {
    var fields = jQuery(el).parents('.list-group-item').find('.variant-fields');
    if (fields.is(':hidden')) {
        jQuery('.variant-fields').slideUp();
        fields.slideDown();        
    } else {
        jQuery('.variant-fields').slideUp();
    }
}
</script>