<div class="row">
    <div class="col-md-2">
        
        <h3>Attributes</h3>
        <p><span class="help-block">Some helpful text</span></p>
                
        <div class="form-group">
            <a class="btn btn-warning" id="add-attribute">Add Attribute</a>
        </div>
                
    </div>
    <!-- /.col-md-2 -->
                
    <div class="col-md-10">
        <?php foreach ((array) $flash->old('attributes') as $key=>$attribute) { ?>
        <fieldset class="template clearfix well well-sm">
            <div class="clearfix">
                <label>Existing Attribute</label>
                <a class="remove-attribute btn btn-xs btn-danger pull-right" onclick="TiendaRemoveAttribute(this);" href="javascript:void(0);">
                    <i class="fa fa-times"></i>
                </a>
            </div>
            
            <div class="form-group clearfix">
                <div class="col-md-6">
                    <label><small>Title</small></label>
                    <input type="text" name="attributes[<?php echo $key; ?>][title]" class="form-control input-sm" value="<?php echo $flash->old('attributes.'.$key.'.title'); ?>" />
                </div>
                <div class="col-md-2">
                    <label><small>Sort Order</small></label>
                    <input type="text" name="attributes[<?php echo $key; ?>][ordering]" class="form-control input-sm" value="<?php echo $flash->old('attributes.'.$key.'.ordering'); ?>" placeholder="Sort Order" />
                </div>                
            </div>
            
            <div class="form-group clearfix">
                
                <div class="col-md-2">
                    <label>Options</label>
                    <div class="form-group">
                        <a class="btn btn-sm btn-default add-option" data-key="<?php echo $key; ?>">Add Option</a>
                    </div>                    
                </div>
                <div class="col-md-10">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div class="clearfix">
                                <a class="remove-option btn btn-xs btn-secondary pull-right" onclick="TiendaRemoveOption(this);" href="javascript:void(0);">
                                    <i class="fa fa-times"></i>
                                </a>
                            </div>
                    
                            <div class="form-group clearfix">
                                <div class="col-md-6">
                                    <label><small>Value</small></label>
                                    <input type="text" name="attributes[<?php echo $key; ?>][options][0][value]" class="form-control input-sm" value="<?php echo $flash->old('attributes.'.$key.'.options.0.value'); ?>" />
                                </div>
                                <div class="col-md-2">
                                    <label><small>Sort Order</small></label>
                                    <input type="text" name="attributes[<?php echo $key; ?>][options][0][ordering]" class="form-control input-sm" value="<?php echo $flash->old('attributes.'.$key.'.options.0.ordering'); ?>" />
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <div class="col-md-3">
                                    <label><small>Impact on Price</small></label>
                                    <select name="attributes[<?php echo $key; ?>][options][0][price_impact]" class="form-control input-sm">
                                        <option value="null" <?php if (!$flash->old('attributes.'.$key.'.options.0.price_impact')) { echo "selected='selected'"; } ?>>No Impact</option>
                                        <option value="replace" <?php if ('replace' == $flash->old('attributes.'.$key.'.options.0.price_impact')) { echo "selected='selected'"; } ?>>Replace</option>
                                        <option value="inc" <?php if ('inc' == $flash->old('attributes.'.$key.'.options.0.price_impact')) { echo "selected='selected'"; } ?>>Increase</option>
                                        <option value="dec" <?php if ('dec' == $flash->old('attributes.'.$key.'.options.0.price_impact')) { echo "selected='selected'"; } ?>>Decrease</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label><small>Price Impact Amount</small></label>
                                    <input type="text" name="attributes[<?php echo $key; ?>][options][0][price_impact_amount]" class="form-control input-sm" value="<?php echo $flash->old('attributes.'.$key.'.options.0.price_impact_amount'); ?>" />
                                </div>
                                <div class="col-md-3">
                                    <label><small>Impact on Weight</small></label>
                                    <select name="attributes[<?php echo $key; ?>][options][0][weight_impact]" class="form-control input-sm">
                                        <option value="null" <?php if (!$flash->old('attributes.'.$key.'.options.0.weight_impact')) { echo "selected='selected'"; } ?>>No Impact</option>
                                        <option value="replace" <?php if ('replace' == $flash->old('attributes.'.$key.'.options.0.weight_impact')) { echo "selected='selected'"; } ?>>Replace</option>
                                        <option value="inc" <?php if ('inc' == $flash->old('attributes.'.$key.'.options.0.weight_impact')) { echo "selected='selected'"; } ?>>Increase</option>
                                        <option value="dec" <?php if ('dec' == $flash->old('attributes.'.$key.'.options.0.weight_impact')) { echo "selected='selected'"; } ?>>Decrease</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label><small>Weight Impact Amount</small></label>
                                    <input type="text" name="attributes[<?php echo $key; ?>][options][0][weight_impact_amount]" class="form-control input-sm" value="<?php echo $flash->old('attributes.'.$key.'.options.0.weight_impact_amount'); ?>" />
                                </div>
                            </div>                            
                        </div>
                    </div>                
                </div>                
            </div>

        </fieldset>        
        <?php } ?>
        
        <div id="new-attributes" class="form-group"></div>
        
        <template type="text/template" id="add-attribute-template">
            <fieldset class="template well well-sm clearfix">
                <div class="clearfix">
                    <label>New Attribute</label>
                    <a class="remove-attribute btn btn-xs btn-danger pull-right" onclick="TiendaRemoveAttribute(this);" href="javascript:void(0);">
                        <i class="fa fa-times"></i>
                    </a>                        
                </div>
                <div class="form-group clearfix">
                    <div class="col-md-6">
                        <input type="text" name="attributes[{id}][title]" class="form-control input-sm" placeholder="Attribute Name" />
                    </div>
                    <div class="col-md-6">
                        <a class="btn btn-sm btn-default add-option">Add Option</a>
                    </div>                    
                </div>
            </fieldset>
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
            });
    
            TiendaRemoveAttribute = function(el) {
                jQuery(el).parents('.template').remove();                            
            }
    
        });
        </script>
                
    </div>
    <!-- /.col-md-10 -->
    
</div>
<!-- /.row -->

<hr />

<?php echo $this->renderLayout('Tienda/Admin/Views::products/fields_attributes_variants.php'); ?>