<?php echo $this->renderLayout('Shop/Admin/Views::products/fields_attributes_variants.php'); ?>

<hr />

<?php $options = array(); ?>
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
        <?php foreach ((array) $flash->old('attributes') as $key=>$attribute) { $options[$key] = count( $flash->old('attributes.'.$key.'.options') ); ?>
        <fieldset class="template clearfix well well-sm" data-attribute="<?php echo $key; ?>">
            <div class="clearfix">
                <label>Existing Attribute</label>
                <a class="remove-attribute btn btn-xs btn-danger pull-right" onclick="ShopRemoveAttribute(this);" href="javascript:void(0);">
                    <i class="fa fa-times"></i>
                </a>
            </div>
            
            <div class="form-group clearfix">
                <div class="col-md-6">
                    <label><small>Title</small></label>
                    <input type="text" name="attributes[<?php echo $key; ?>][title]" class="form-control input-sm" value="<?php echo $flash->old('attributes.'.$key.'.title'); ?>" />
                    <input type="hidden" name="attributes[<?php echo $key; ?>][id]" value="<?php echo (string) $flash->old('attributes.'.$key.'.id'); ?>" />
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
                        <a class="btn btn-sm btn-default add-option" data-attribute="<?php echo $key; ?>">Add Option</a>
                    </div>                    
                </div>
                <div class="col-md-10">
                    
                    <?php foreach ((array) $flash->old('attributes.'.$key.'.options') as $option_key => $option) { ?>
                    <div class="option-template template panel panel-default" data-option="<?php echo $option_key; ?>">
                        <div class="panel-body">
                            <div class="clearfix">
                                <a class="remove-option btn btn-xs btn-secondary pull-right" onclick="ShopRemoveOption(this);" href="javascript:void(0);">
                                    <i class="fa fa-times"></i>
                                </a>
                            </div>
                    
                            <div class="form-group clearfix">
                                <div class="col-md-6">
                                    <label><small>Value</small></label>
                                    <input type="text" name="attributes[<?php echo $key; ?>][options][<?php echo $option_key; ?>][value]" class="form-control input-sm" value="<?php echo $flash->old('attributes.'.$key.'.options.'.$option_key.'.value'); ?>" />
                                    <input type="hidden" name="attributes[<?php echo $key; ?>][options][<?php echo $option_key; ?>][id]" value="<?php echo (string) $flash->old('attributes.'.$key.'.options.'.$option_key.'.id'); ?>" />
                                </div>
                                <div class="col-md-2">
                                    <label><small>Sort Order</small></label>
                                    <input type="text" name="attributes[<?php echo $key; ?>][options][<?php echo $option_key; ?>][ordering]" class="form-control input-sm" value="<?php echo $flash->old('attributes.'.$key.'.options.'.$option_key.'.ordering'); ?>" />
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <div class="col-md-3">
                                    <label><small>Impact on Price</small></label>
                                    <select name="attributes[<?php echo $key; ?>][options][<?php echo $option_key; ?>][price_impact]" class="form-control input-sm">
                                        <option value="null" <?php if (!$flash->old('attributes.'.$key.'.options.'.$option_key.'.price_impact')) { echo "selected='selected'"; } ?>>No Impact</option>
                                        <option value="replace" <?php if ('replace' == $flash->old('attributes.'.$key.'.options.'.$option_key.'.price_impact')) { echo "selected='selected'"; } ?>>Replace</option>
                                        <option value="inc" <?php if ('inc' == $flash->old('attributes.'.$key.'.options.'.$option_key.'.price_impact')) { echo "selected='selected'"; } ?>>Increase</option>
                                        <option value="dec" <?php if ('dec' == $flash->old('attributes.'.$key.'.options.'.$option_key.'.price_impact')) { echo "selected='selected'"; } ?>>Decrease</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label><small>Price Impact Amount</small></label>
                                    <input type="text" name="attributes[<?php echo $key; ?>][options][<?php echo $option_key; ?>][price_impact_amount]" class="form-control input-sm" value="<?php echo $flash->old('attributes.'.$key.'.options.'.$option_key.'.price_impact_amount'); ?>" />
                                </div>
                                <div class="col-md-3">
                                    <label><small>Impact on Weight</small></label>
                                    <select name="attributes[<?php echo $key; ?>][options][<?php echo $option_key; ?>][weight_impact]" class="form-control input-sm">
                                        <option value="null" <?php if (!$flash->old('attributes.'.$key.'.options.'.$option_key.'.weight_impact')) { echo "selected='selected'"; } ?>>No Impact</option>
                                        <option value="replace" <?php if ('replace' == $flash->old('attributes.'.$key.'.options.'.$option_key.'.weight_impact')) { echo "selected='selected'"; } ?>>Replace</option>
                                        <option value="inc" <?php if ('inc' == $flash->old('attributes.'.$key.'.options.'.$option_key.'.weight_impact')) { echo "selected='selected'"; } ?>>Increase</option>
                                        <option value="dec" <?php if ('dec' == $flash->old('attributes.'.$key.'.options.'.$option_key.'.weight_impact')) { echo "selected='selected'"; } ?>>Decrease</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label><small>Weight Impact Amount</small></label>
                                    <input type="text" name="attributes[<?php echo $key; ?>][options][<?php echo $option_key; ?>][weight_impact_amount]" class="form-control input-sm" value="<?php echo $flash->old('attributes.'.$key.'.options.'.$option_key.'.weight_impact_amount'); ?>" />
                                </div>
                            </div>                            
                        </div>
                    </div>
                    <?php } ?>
                    
                    <div class="form-group new-options" data-attribute="<?php echo $key; ?>"></div>
                                    
                </div>                
            </div>

        </fieldset>        
        <?php } ?>
        
        <div id="new-attributes" class="form-group"></div>
        
        <template type="text/template" id="add-attribute-template">
            <fieldset class="template well well-sm clearfix" data-attribute="{id}">
                <div class="clearfix">
                    <label>New Attribute</label>
                    <a class="remove-attribute btn btn-xs btn-danger pull-right" onclick="ShopRemoveAttribute(this);" href="javascript:void(0);">
                        <i class="fa fa-times"></i>
                    </a>                        
                </div>
                
                <div class="form-group clearfix">
                    <div class="col-md-6">
                        <label><small>Title</small></label>
                        <input type="text" name="attributes[{id}][title]" class="form-control input-sm" placeholder="Title" />
                    </div>
                    <div class="col-md-2">
                        <label><small>Sort Order</small></label>
                        <input type="text" name="attributes[{id}][ordering]" class="form-control input-sm" placeholder="Sort Order" />
                    </div>                
                </div>
                
                <div class="form-group clearfix">
                    
                    <div class="col-md-2">
                        <label>Options</label>
                        <div class="form-group">
                            <a class="btn btn-sm btn-default add-option" data-attribute="{id}">Add Option</a>
                        </div>                    
                    </div>
                    <div class="col-md-10">
                        <div class="form-group new-options" data-attribute="{id}"></div>
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
                ShopSetupAddOptionButtons();
            });

            window.options = <?php echo json_encode( $options ); ?>;
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
    
            ShopRemoveAttribute = function(el) {
                jQuery(el).parents('.template').remove();                            
            }

            ShopRemoveOption = function(el) {
                jQuery(el).parents('.option-template').remove();                            
            }
    
        });
        </script>
        
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
                    
                    <div class="form-group">
                        <div class="col-md-3">
                            <label><small>Impact on Price</small></label>
                            <select name="attributes[{id}][options][{option_id}][price_impact]" class="form-control input-sm">
                                <option value="null">No Impact</option>
                                <option value="replace">Replace</option>
                                <option value="inc">Increase</option>
                                <option value="dec">Decrease</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label><small>Price Impact Amount</small></label>
                            <input type="text" name="attributes[{id}][options][{option_id}][price_impact_amount]" class="form-control input-sm" />
                        </div>
                        <div class="col-md-3">
                            <label><small>Impact on Weight</small></label>
                            <select name="attributes[{id}][options][{option_id}][weight_impact]" class="form-control input-sm">
                                <option value="null">No Impact</option>
                                <option value="replace">Replace</option>
                                <option value="inc">Increase</option>
                                <option value="dec">Decrease</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label><small>Weight Impact Amount</small></label>
                            <input type="text" name="attributes[{id}][options][{option_id}][weight_impact_amount]" class="form-control input-sm" />
                        </div>
                    </div>                            
                </div>
            </div>
        </template>        
                
    </div>
    <!-- /.col-md-10 -->
    
</div>
<!-- /.row -->

<?php /* ?>
<hr />

<div class="row">
    <div class="col-md-2">
        
        <h3>Rebuild</h3>
        <p class="help-block">Some helpful WARNING text</p>
                
    </div>
    <!-- /.col-md-2 -->
                
    <div class="col-md-10">

        <?php if (!$flash->old('_id')) { ?>
            <div class="form-group clearfix">
                <div class="col-md-4">        
                    <label>Automatically build Variants</label>
                    <div class="form-group">
                    <label class="radio-inline">
                        <input type="radio" name="variants[build]" value="1" checked> Yes
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="variants[build]" value="0"> No
                    </label>
                    </div>
                </div>
                
                <div class="col-md-8">
                    <div class="alert alert-warning"><b>Warning!</b> Something could happen!</div>
                </div>        
            </div>
            <!-- /.form-group -->
        <?php } else { ?>
            <div class="form-group clearfix">
                <div class="col-md-4">        
                    <label>Rebuild Variants</label>
                    <div class="form-group">
                    <label class="radio-inline">
                        <input type="radio" name="variants[build]" value="1"> Yes
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="variants[build]" value="0" checked> No
                    </label>
                    </div>
                </div>
                
                <div class="col-md-8">
                    <div class="alert alert-warning"><b>Warning!</b> Something could happen!</div>
                </div>        
            </div>    
            <!-- /.form-group -->    
        <?php } ?>
        
    </div>
    
</div>
*/ ?>