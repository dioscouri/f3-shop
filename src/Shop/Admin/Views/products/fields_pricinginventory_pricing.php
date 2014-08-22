<div class="row">
    <div class="col-md-2">
        
        <h3>Prices</h3>
        
                
    </div>
    <!-- /.col-md-2 -->
                
    <div class="col-md-10">

        <div class="form-group">
        
            <div class="row clearfix">
                <div class="col-md-6">        
                    <label>Default Price</label>
                    <input type="text" name="prices[default]" placeholder="Default Price" value="<?php echo $flash->old('prices.default'); ?>" class="form-control" />
                </div>
                
                <div class="col-md-6">
                    <label>List Price <small>(Optional - Normally displayed with a <strike>strikethrough</strike>)</small></label>
                    <input type="text" name="prices[list]" placeholder="List Price" value="<?php echo $flash->old('prices.list'); ?>" class="form-control" />
                </div>
            </div>        
        
        </div>
        <!-- /.form-group -->
        
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">        
                    <label>Use Variant Pricing</label>
                    <div class="input-group">
                        <label class="radio-inline">
                            <input type="radio" name="policies[variant_pricing][enabled]" value="1" <?php if ($flash->old('policies.variant_pricing.enabled')) { echo 'checked'; } ?>> Yes
                        </label>
                        <label class="radio-inline">
                            <input type="radio" name="policies[variant_pricing][enabled]" value="0" <?php if (!$flash->old('policies.variant_pricing.enabled')) { echo 'checked'; } ?>> No
                        </label>
                    </div>
                    <p class="help-block">If enabled, the prices you specify for each variant will be given priority over the prices above.  However, Special Pricing (below) will be given final priority.</p>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="form-group">        
                    <label>Hide Price?</label>
                    <div class="input-group">
                        <label class="radio-inline">
                            <input type="radio" name="policies[hide_price]" value="1" <?php if ($flash->old('policies.hide_price')) { echo 'checked'; } ?>> Yes
                        </label>
                        <label class="radio-inline">
                            <input type="radio" name="policies[hide_price]" value="0" <?php if (!$flash->old('policies.hide_price')) { echo 'checked'; } ?>> No
                        </label>
                    </div>
                    <p class="help-block">If the price is hidden, "Call for price" is displayed instead.  Customers will not be able to add the item to their cart.</p>
                </div>
            </div>
        </div>
        
    </div>
</div>    