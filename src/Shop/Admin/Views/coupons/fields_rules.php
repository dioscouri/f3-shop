<div class="alert alert-info">
    <p><b>Note:</b> Coupons will only be applied if <u>all</u> of the conditions below are fulfilled.  For example, if you have check the "wholesale" user group, add "t-shirt" to the list of Products, and set a $90 order minimum, the coupon will only be applied if the user is a wholesaler <i>AND</i> their order is above $90 <i>AND</i> they have a t-shirt in their cart.</p>
</div>

<div class="row">
    <div class="col-md-2">
        
        <h3>Products</h3>
        <p class="help-block">This coupon will only be applied if the shopper has one of these products in their cart.</p>
                
    </div>
    <!-- /.col-md-2 -->
                
    <div class="col-md-10">
    
        <div class="form-group">
            <label>Search</label>
            // TODO Enable select2 selector for products, storing IDs embedded in coupon document            
            <div class="input-group">
                <input name="products[]" value="<?php echo implode(",", (array) $flash->old('products') ); ?>" type="text" class="form-control" /> <?php // ui-select2 ?> 
            </div>
            <!-- /.form-group -->        
            
        </div>
        <!-- /.form-group -->
        
    </div>
    <!-- /.col-md-10 -->
    
</div>
<!-- /.row -->

<hr />

<div class="row">
    <div class="col-md-2">
        
        <h3>Minimums</h3>
        <p class="help-block">The coupon will only be applied if these minimums are met.</p>
                
    </div>
    <!-- /.col-md-2 -->
                
    <div class="col-md-10">
    
        <div class="form-group">
            <label>Order Amount</label>
            <div class="row">
                <div class="col-md-6">
                    <input type="text" name="min_order_amount" placeholder="0.00" value="<?php echo $flash->old('min_order_amount'); ?>" class="form-control" />
                </div>
                <div class="col-md-6">
                    <input type="text" name="min_order_amount_currency" placeholder="USD" value="<?php echo $flash->old('min_order_amount_currency'); ?>" class="form-control" />
                </div>                
            </div>
        </div>
        <!-- /.form-group -->
        
    </div>
    <!-- /.col-md-10 -->
    
</div>
<!-- /.row -->

<hr />

<div class="row">
    <div class="col-md-2">
        
        <h3>Shopper Groups</h3>
        <p class="help-block">Shopper must be in one of these groups to get discount.</p>
                        
    </div>
    <!-- /.col-md-2 -->
                
    <div class="col-md-10">
    
        <div class="form-group">
            <?php if ((array) $groups = \Users\Models\Groups::find() ) { ?>
            <div class="max-height-200 list-group-item">
            	<?php $current = \Joomla\Utilities\ArrayHelper::getColumn( (array) $flash->old('groups'), 'id' ); ?>
                <?php foreach ($groups as $one) { ?>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="groups[]" class="icheck-input" value="<?php echo $one->_id; ?>" <?php if (in_array($one->_id, $current)) { echo "checked='checked'"; } ?>>
                        <?php echo $one->name;  ?>
                    </label>
                </div>
                <?php } ?> 
                
            </div>
            <?php } ?>                        
        </div>
        <!-- /.form-group -->
        
    </div>
    <!-- /.col-md-10 -->
    
</div>
<!-- /.row -->

<hr />

<div class="row">
    <div class="col-md-2">
        
        <h3>Geolocation</h3>
        <p class="help-block">The coupon will only be applied if the specified order address is in the selected regions.</p>
                
    </div>
    <!-- /.col-md-2 -->
                
    <div class="col-md-10">
        <div class="form-group">
            <label>Address Type</label>
            <input type="text" name="geo_address_type" placeholder="Shipping or Billing" value="<?php echo $flash->old('geo_address_type'); ?>" class="form-control" />
        </div>
        <!-- /.form-group -->    
    
        <div class="form-group">
            <label>Countries</label>
            <input type="text" name="geo_countries[]" placeholder="US" value="<?php echo $flash->old('geo_countries'); ?>" class="form-control" />
        </div>
        <!-- /.form-group -->
        
        <div class="form-group">
            <label>Regions</label>
            <input type="text" name="geo_regions[]" placeholder="NY" value="<?php echo $flash->old('geo_regions'); ?>" class="form-control" />
        </div>
        <!-- /.form-group -->
        
    </div>
    <!-- /.col-md-10 -->
    
</div>
<!-- /.row -->