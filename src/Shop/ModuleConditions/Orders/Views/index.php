<div id="ruleset-shop_orders" class="ruleset row">
    
    <div class="col-md-2">
        
        <h3>Shop Orders</h3>
        <p class="help-block">Show/Hide this module based on converstion history.</p>
                            
    </div>
    <!-- /.col-md-2 -->
                
    <div class="col-md-10">
        <div class="row">
            <div class="col-md-3">
                <select name="assignment[shop_orders][method]" class="form-control ruleset-switcher">
                    <option value="ignore" <?php if ($flash->old('assignment.shop_orders.method') == "ignore") { echo "selected='selected'"; } ?>>Ignore</option>
                    <option value="include" <?php if ($flash->old('assignment.shop_orders.method') == "include") { echo "selected='selected'"; } ?>>Enabled</option>
                    <?php /* ?><option value="exclude" <?php if ($flash->old('assignment.shop_orders.method') == "exclude") { echo "selected='selected'"; } ?>>Exclude</option> */ ?>
                </select>                
            </div>
            <div class="col-md-9">
            
                <div class="ruleset-options">                
                    <div class="ruleset-enabled <?php if (!in_array($flash->old('assignment.shop_orders.method'), array( "include", "exclude" ) ) ) { echo "hidden"; } ?>">
                    
                        <p class="alert alert-danger">By definition, this condition requires the user to be logged in.  If this condition is enabled, this module will only display when the user is logged in and the selected rule below evaluates to true.</p>
                        
                        <select name="assignment[shop_orders][has_converted]" class="form-control">
                            <option value="0" <?php if ($flash->old('assignment.shop_orders.has_converted') == "0") { echo "selected='selected'"; } ?>>User has never made an order</option>
                            <option value="1" <?php if ($flash->old('assignment.shop_orders.has_converted') == "1") { echo "selected='selected'"; } ?>>User has made an order</option>
                        </select>
                    </div>                        
                    <div class="text-muted ruleset-disabled <?php if (in_array($flash->old('assignment.shop_orders.method'), array( "include", "exclude" ) ) ) { echo "hidden"; } ?>">
                        This ruleset is ignored.
                    </div>                                  
                </div>              
                  
            </div>    
        </div>
    </div>
    <!-- /.col-md-10 -->
    
</div>
<!-- /.row -->