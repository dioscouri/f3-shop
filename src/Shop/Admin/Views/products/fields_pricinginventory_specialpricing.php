<div class="row">
    <div class="col-md-2">
        
        <h3>Special Pricing</h3>
        
        <div class="form-group">
            <a class="btn btn-warning" id="add-price">Add Special Price</a>
        </div>        
                
    </div>
    <!-- /.col-md-2 -->
                
    <div class="col-md-10">
    
    <?php foreach ((array) $flash->old('prices.special') as $key=>$price) { ?>
        <fieldset class="template clearfix well well-sm">
            <div class="form-group clearfix">
                <label>Existing Price</label>
                <a class="remove-price btn btn-xs btn-danger pull-right" onclick="ShopRemovePrice(this);" href="javascript:void(0);">
                    <i class="fa fa-times"></i>
                </a>
            </div>
            
            <div class="form-group clearfix">
                <div class="col-md-2">
                    <input type="text" name="prices[special][<?php echo $key; ?>][price]" class="form-control input-sm" value="<?php echo $flash->old('prices.special.'.$key.'.price'); ?>" />
                </div>
                <div class="col-md-5">
                    <div class="input-group">
                        <input type="text" name="prices[special][<?php echo $key; ?>][start_date]" class="form-control input-datepicker input-sm" placeholder="Start" value="<?php echo $flash->old('prices.special.'.$key.'.start_date'); ?>" />
                        <span class="input-group-addon">at</span>
                        <span class="input-group bootstrap-timepicker">
                            <input type="text" name="prices[special][<?php echo $key; ?>][start_time]" class="input-timepicker form-control input-sm" value="<?php echo $flash->old('prices.special.'.$key.'.start_time'); ?>" data-show-meridian="false" data-show-inputs="true" data-modal-backdrop="true">
                            <span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
                        </span>
                    </div>                    
                </div>
                <div class="col-md-5">
                    <div class="input-group">
                        <input type="text" name="prices[special][<?php echo $key; ?>][end_date]" class="form-control input-datepicker input-sm" placeholder="End" value="<?php echo $flash->old('prices.special.'.$key.'.end_date'); ?>" />
                        <span class="input-group-addon">at</span>
                        <span class="input-group bootstrap-timepicker">
                            <input type="text" name="prices[special][<?php echo $key; ?>][end_time]" class="input-timepicker form-control input-sm" value="<?php echo $flash->old('prices.special.'.$key.'.end_time'); ?>" data-show-meridian="false" data-show-inputs="true" data-modal-backdrop="true">
                            <span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
                        </span>
                    </div>
                </div>
            </div>
            <div class="form-group clearfix">
                <div class="col-md-3">
                    <select class="form-control" name="prices[special][<?php echo $key; ?>][group_id]">
                        <option value="">All Groups</option>
                        <?php foreach ($groups = \Users\Models\Groups::find() as $group) { ?>
                        <option value="<?php echo (string) $group->id; ?>" <?php if ($flash->old('prices.special.'.$key.'.group_id') == (string) $group->id) { echo "selected='selected'"; } ?>><?php echo $group->title; ?></option>
                        <?php } ?>                        
                    </select>                    
                </div>                                    
                <div class="col-md-6">
                    <div class="input-group">
                        <input type="text" name="prices[special][<?php echo $key; ?>][quantity_min]" value="<?php echo $flash->old('prices.special.'.$key.'.quantity_min'); ?>" class="form-control input-sm" placeholder="Min Quantity" />
                        <span class="input-group-addon">to</span>
                        <input type="text" name="prices[special][<?php echo $key; ?>][quantity_max]" value="<?php echo $flash->old('prices.special.'.$key.'.quantity_max'); ?>" class="form-control input-sm" placeholder="Max Quantity" />                            
                    </div>                        
                </div>
                <div class="col-md-3">
                    <div class="row">
                        <div class="col-md-3">
                            <label>Priority:</label>
                        </div>
                        <div class="col-md-9">
                            <input type="text" name="prices[special][<?php echo $key; ?>][ordering]" class="form-control input-sm" value="<?php echo $flash->old('prices.special.'.$key.'.ordering'); ?>" placeholder="Sort Order">
                        </div>
                    </div>
                </div>
            </div>
            
        </fieldset>                        
    <?php } ?>    
    
        <template type="text/template" id="add-price-template">
            <fieldset class="template well well-sm clearfix">
                <div class="clearfix">
                    <a class="remove-price btn btn-xs btn-danger pull-right" onclick="ShopRemovePrice(this);" href="javascript:void(0);">
                        <i class="fa fa-times"></i>
                    </a>                        
                </div>
                <div class="form-group clearfix">
                    <div class="col-md-2">
                        <label>New Price</label>
                        <input type="text" name="prices[special][{id}][price]" class="form-control input-sm" placeholder="Price"/>
                    </div>
                    <div class="col-md-10">
                        <label>Date Range</label>
                        <div class="row">                            
                            <div class="col-md-6">
                                <div class="input-group">
                                    <input type="text" name="prices[special][{id}][start_date]" class="form-control input-datepicker input-sm" placeholder="Start Date" value="<?php echo date( "Y-m-d" ); ?>" />
                                    <span class="input-group-addon">at</span>
                                    <span class="input-group bootstrap-timepicker">
                                        <input type="text" name="prices[special][{id}][start_time]" class="input-timepicker form-control input-sm" data-show-meridian="false" data-show-inputs="true" data-modal-backdrop="true">
                                        <span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
                                    </span>
                                </div>              
                            </div>
                            <div class="col-md-6">
                                <div class="input-group">
                                    <input type="text" name="prices[special][{id}][end_date]" class="form-control input-datepicker input-sm" placeholder="End Date" />
                                    <span class="input-group-addon">at</span>
                                    <span class="input-group bootstrap-timepicker">
                                        <input type="text" name="prices[special][{id}][end_time]" class="input-timepicker form-control input-sm" data-show-meridian="false" data-show-inputs="true" data-default-time="false">
                                        <span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
                                    </span>
                                </div>
                            </div>                            
                        </div>                                
                    </div>
                </div>
                <div class="form-group clearfix">
                    <div class="col-md-3">
                        <label>Shopper Group</label>
                        <select class="form-control input-sm" name="prices[special][{id}][group_id]">
                            <option value="">All Groups</option>
                            <?php foreach ($groups = \Users\Models\Groups::find() as $group) { ?>
                            <option value="<?php echo (string) $group->id; ?>"><?php echo $group->title; ?></option>
                            <?php } ?>                                                    
                        </select>
                    </div>                
                    <div class="col-md-6">
                        <label>Quantity Range</label>                    
                        <div class="input-group">
                            <input type="text" name="prices[special][{id}][quantity_min]" class="form-control input-sm" placeholder="Minimum Quantity" />
                            <span class="input-group-addon">to</span>
                            <input type="text" name="prices[special][{id}][quantity_max]" class="form-control input-sm" placeholder="Maximum Quantity" />                            
                        </div>                                                                
                    </div>
                    <div class="col-md-3">
                        <div class="row">
                            <div class="col-md-3">
                                <label>Priority:</label>
                            </div>
                            <div class="col-md-9">
                                <input type="text" name="prices[special][{id}][ordering]" class="form-control input-sm" value="" placeholder="Sort Order">
                            </div>
                        </div>
                    </div>                    
                </div>
            </fieldset>
        </template>
        
        <div id="new-prices" class="form-group"></div>
        
        <script>
        jQuery(document).ready(function(){
            window.new_prices = <?php echo count( $flash->old('prices.special') ); ?>;
            jQuery('#add-price').click(function(){
                var container = jQuery('#new-prices');
                var template = jQuery('#add-price-template').html();
                template = template.replace( new RegExp("{id}", 'g'), window.new_prices);
                container.append(template);
                window.new_prices = window.new_prices + 1;
                ShopSetupDatepicker();                            
            });
    
            ShopRemovePrice = function(el) {
                jQuery(el).parents('.template').remove();                            
            }

            ShopSetupDatepicker = function() {
                jQuery('.input-datepicker').datepicker({
                        format: 'yyyy-mm-dd',
                        autoclose: true
                });
                jQuery('.input-timepicker').timepicker().on("focus", function() {
                    jQuery(this).timepicker("showWidget");
                });                
            }

            ShopSetupDatepicker();
        });
        </script>
        
    </div>
    <!-- /.col-md-10 -->
    
</div>
<!-- /.row -->
    