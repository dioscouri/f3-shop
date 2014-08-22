<div class="row">
    <div class="col-md-2">
        
        <h3>Policies</h3>
        
                
    </div>
    <!-- /.col-md-2 -->
                
    <div class="col-md-10">
    
        <div class="form-group">
            <label>Track Inventory</label>
            <div class="form-group">
            <label class="radio-inline">
                <input type="radio" name="policies[track_inventory]" value="1" <?php if ($flash->old('policies.track_inventory')) { echo 'checked'; } ?>> Yes
            </label>
            <label class="radio-inline">
                <input type="radio" name="policies[track_inventory]" value="0" <?php if (!$flash->old('policies.track_inventory')) { echo 'checked'; } ?>> No
            </label>
            </div>
        </div>
        <!-- /.form-group -->
    
        <div class="form-group">
            <div class="row clearfix">
                <div class="col-md-4">        
                    <label>Enable Quantity Input <small>on detail page</small></label>
                    <div class="form-group">
                    <label class="radio-inline">
                        <input type="radio" name="policies[quantity_input][product_detail]" value="1" <?php if ($flash->old('policies.quantity_input.product_detail')) { echo 'checked'; } ?>> Yes
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="policies[quantity_input][product_detail]" value="0" <?php if (!$flash->old('policies.quantity_input.product_detail')) { echo 'checked'; } ?>> No
                    </label>
                    </div>
                </div>
                
                <div class="col-md-4">        
                    <label>Enable Quantity Update <small>in cart</small></label>
                    <div class="form-group">
                    <label class="radio-inline">
                        <input type="radio" name="policies[quantity_input][cart]" value="1" <?php if ($flash->old('policies.quantity_input.cart')) { echo 'checked'; } ?>> Yes
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="policies[quantity_input][cart]" value="0" <?php if (!$flash->old('policies.quantity_input.cart')) { echo 'checked'; } ?>> No
                    </label>
                    </div>
                </div>
                
                <div class="col-md-4">        
                    <label>Default Quantity <small>when adding to cart</small></label>
                    <input type="text" name="policies[quantity_input][default]" placeholder="Default Quantity" value="<?php echo $flash->old('policies.quantity_input.default'); ?>" class="form-control" />
                </div>                
            </div>
        </div>
        <!-- /.form-group -->    
            
        <div class="form-group">
            <label>Enable Quantity Restrictions</label>
            <div class="form-group">
            <label class="radio-inline">
                <input type="radio" name="policies[quantity_restrictions][enabled]" value="1" <?php if ($flash->old('policies.quantity_restrictions.enabled')) { echo 'checked'; } ?>> Yes
            </label>
            <label class="radio-inline">
                <input type="radio" name="policies[quantity_restrictions][enabled]" value="0" <?php if (!$flash->old('policies.quantity_restrictions.enabled')) { echo 'checked'; } ?>> No
            </label>
            </div>            
        </div>
        <!-- /.form-group -->
        
        <div id="quantity_restrictions" class="form-group">
            <label>Quantity Restrictions</label>
            <div class="row clearfix">
                <div class="col-md-4">
                    <input type="text" name="policies[quantity_restrictions][min]" placeholder="Minimum" value="<?php echo $flash->old('policies.quantity_restrictions.min'); ?>" class="form-control" />
                    <small class="help-block">Minimum</small>
                </div>
                <div class="col-md-4">
                    <input type="text" name="policies[quantity_restrictions][max]" placeholder="Maximum" value="<?php echo $flash->old('policies.quantity_restrictions.max'); ?>" class="form-control" />
                    <small class="help-block">Maximum</small>                    
                </div>
                <div class="col-md-4">
                    <input type="text" name="policies[quantity_restrictions][increment]" placeholder="Forced Increment" value="<?php echo $flash->old('policies.quantity_restrictions.increment'); ?>" class="form-control" />
                    <small class="help-block">Increment</small>
                </div>
            </div>
            
        </div>
        <!-- /.form-group -->
        
    
    </div>
    <!-- /.col-md-10 -->
    
</div>
<!-- /.row -->