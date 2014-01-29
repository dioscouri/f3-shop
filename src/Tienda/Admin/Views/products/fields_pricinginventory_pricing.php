<div class="row">
    <div class="col-md-2">
        
        <h3>Prices</h3>
        <p class="help-block">Some helpful text</p>
                
    </div>
    <!-- /.col-md-2 -->
                
    <div class="col-md-10">

        <div class="form-group">
            <label>Default Price</label>
            <input type="text" name="prices[default]" placeholder="Default Price" value="<?php echo $flash->old('prices.default'); ?>" class="form-control" />
        </div>
        <!-- /.form-group -->
        
        <div class="form-group">
            <label>List Price <small>(Optional - Normally displayed with a <strike>strikethrough</strike>)</small></label>
            <input type="text" name="prices[list]" placeholder="List Price" value="<?php echo $flash->old('prices.list'); ?>" class="form-control" />
        </div>
        <!-- /.form-group -->

    </div>
</div>    

<hr />

<div class="row">
    <div class="col-md-2">
        
        <h3>Special Pricing</h3>
        <p class="help-block">Some helpful text</p>
        
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
                <a class="remove-price btn btn-xs btn-danger pull-right" onclick="TiendaRemovePrice(this);" href="javascript:void(0);">
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
                <div class="col-md-4">
                    <select class="form-control" name="prices[special][<?php echo $key; ?>][shoppergroup_id]">
                        <option value="0">Default</option>
                    </select>                    
                </div>                                    
                <div class="col-md-8">
                    <div class="input-group">
                        <input type="text" name="prices[special][<?php echo $key; ?>][quantity_min]" value="<?php echo $flash->old('prices.special.'.$key.'.quantity_min'); ?>" class="form-control input-sm" placeholder="Min Quantity" />
                        <span class="input-group-addon">to</span>
                        <input type="text" name="prices[special][<?php echo $key; ?>][quantity_max]" value="<?php echo $flash->old('prices.special.'.$key.'.quantity_max'); ?>" class="form-control input-sm" placeholder="Max Quantity" />                            
                    </div>                        
                </div>
                
            </div>
            
        </fieldset>                        
    <?php } ?>    
    
        <template type="text/template" id="add-price-template">
            <fieldset class="template well well-sm clearfix">
                <div class="clearfix">
                    <a class="remove-price btn btn-xs btn-danger pull-right" onclick="TiendaRemovePrice(this);" href="javascript:void(0);">
                        <i class="fa fa-times"></i>
                    </a>                        
                </div>
                <div class="form-group clearfix">
                    <div class="col-md-2">
                        <label>New Price</label>
                        <input type="text" name="prices[special][{id}][price]" class="form-control" placeholder="Price"/>
                    </div>
                    <div class="col-md-10">
                        <label>Date Range</label>
                        <div class="row">                            
                            <div class="col-md-6">
                                <div class="input-group">
                                    <input type="text" name="prices[special][{id}][start_date]" class="form-control input-datepicker" placeholder="Start Date" value="<?php echo date( "Y-m-d" ); ?>" />
                                    <span class="input-group-addon">at</span>
                                    <span class="input-group bootstrap-timepicker">
                                        <input type="text" name="prices[special][{id}][start_time]" class="input-timepicker form-control" data-show-meridian="false" data-show-inputs="true" data-modal-backdrop="true">
                                        <span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
                                    </span>
                                </div>              
                            </div>
                            <div class="col-md-6">
                                <div class="input-group">
                                    <input type="text" name="prices[special][{id}][end_date]" class="form-control input-datepicker" placeholder="End Date" />
                                    <span class="input-group-addon">at</span>
                                    <span class="input-group bootstrap-timepicker">
                                        <input type="text" name="prices[special][{id}][end_time]" class="input-timepicker form-control" data-show-meridian="false" data-show-inputs="true" data-default-time="false">
                                        <span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
                                    </span>
                                </div>
                            </div>                            
                        </div>                                
                    </div>
                </div>
                <div class="form-group clearfix">
                    <div class="col-md-4">
                        <label>Shopper Group</label>
                        <select class="form-control" name="prices[special][{id}][shoppergroup_id]">
                            <option value="0">Default</option>
                        </select>
                    </div>                
                    <div class="col-md-8">
                        <label>Quantity Range</label>                    
                        <div class="input-group">
                            <input type="text" name="prices[special][{id}][quantity_min]" class="form-control" placeholder="Minimum Quantity" />
                            <span class="input-group-addon">to</span>
                            <input type="text" name="prices[special][{id}][quantity_max]" class="form-control" placeholder="Maximum Quantity" />                            
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
                TiendaSetupDatepicker();                            
            });
    
            TiendaRemovePrice = function(el) {
                jQuery(el).parents('.template').remove();                            
            }

            TiendaSetupDatepicker = function() {
                jQuery('.input-datepicker').datepicker({
                        format: 'yyyy-mm-dd',
                        autoclose: true
                });
                jQuery('.input-timepicker').timepicker().on("focus", function() {
                    jQuery(this).timepicker("showWidget");
                });                
            }
    
        });
        </script>
        
    </div>
    <!-- /.col-md-10 -->
    
</div>
<!-- /.row -->
    