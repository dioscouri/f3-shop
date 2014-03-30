<div class="row">
    <div class="col-md-2">
        
        <h3>Shipping</h3>
        <p class="help-block">Some helpful text</p>
                
    </div>
    <!-- /.col-md-2 -->
                
    <div class="col-md-10">
        
        <div class="form-group">
            <div class="row clearfix">
                <div class="col-md-6">
                    <label>Requires Shipping</label>
                    <div class="form-group">
                    <label class="radio-inline">
                        <input type="radio" name="shipping[enabled]" value="1" <?php if ($flash->old('shipping.enabled')) { echo 'checked'; } ?>> Yes
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="shipping[enabled]" value="0" <?php if (!$flash->old('shipping.enabled')) { echo 'checked'; } ?>> No
                    </label>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <label>Weight</label>
                    <input type="text" name="shipping[weight]" placeholder="Weight" value="<?php echo $flash->old('shipping.weight'); ?>" class="form-control" />
                </div>
            </div>
        </div>
        <!-- /.form-group -->
        
        <div class="form-group">
            <label>Dimensions</label>
            <div class="row clearfix">
                <div class="col-md-4">
                    <input type="text" name="shipping[dimensions][length]" placeholder="Length" value="<?php echo $flash->old('shipping.dimensions.length'); ?>" class="form-control" />
                    <small class="help-block">Length</small>
                </div>
                <div class="col-md-4">
                    <input type="text" name="shipping[dimensions][width]" placeholder="Width" value="<?php echo $flash->old('shipping.dimensions.width'); ?>" class="form-control" />
                    <small class="help-block">Width</small>                    
                </div>
                <div class="col-md-4">
                    <input type="text" name="shipping[dimensions][height]" placeholder="Height" value="<?php echo $flash->old('shipping.dimensions.height'); ?>" class="form-control" />
                    <small class="help-block">Height</small>
                </div>
            </div>
            
        </div>
        <!-- /.form-group -->
        
        <div class="form-group">
            <div class="row clearfix">
                <div class="col-md-2">
                    <label>Shipping Surcharge</label>
                    <input type="text" name="shipping[surcharge]" placeholder="Surcharge" value="<?php echo $flash->old('shipping.surcharge'); ?>" class="form-control" />
                    <small class="help-block">A shipping/handling fee added regardless of shipping method.</small>                    
                </div>
                
                <div class="col-md-10">

                </div>
            </div>
        </div>
        <!-- /.form-group -->
    
    </div>
    <!-- /.col-md-10 -->
    
</div>
<!-- /.row -->