<div class="row">
    <div class="col-md-2">
        
        <h3>Basics</h3>
        <p class="help-block">Some helpful text</p>
                
    </div>
    <!-- /.col-md-2 -->
                
    <div class="col-md-10">
    
        <div class="form-group">
            <label>Title</label>
            <input type="text" name="title" placeholder="Title" value="<?php echo $flash->old('title'); ?>" class="form-control" />
        </div>
        <!-- /.form-group -->
        
        <div class="form-group">
            <label>Code</label>
            <input type="text" name="code" placeholder="Code" value="<?php echo $flash->old('code'); ?>" class="form-control" />
            <p class="help-block">This is the code that customers must submit to get the discount.</p>
        </div>
        <!-- /.form-group -->
        
    </div>
    <!-- /.col-md-10 -->
    
</div>
<!-- /.row -->

<hr />

<?php echo $this->renderLayout('Shop/Admin/Views::coupons/fields_basics_publication.php'); ?>

<hr />

<div class="row">
    <div class="col-md-2">
        
        <h3>Discount</h3>
        <p class="help-block">Here you define the coupon's discount, as well as where the discount is applied.</p>
        <p class="help-block">For example, if want to offer free 2-day shipping while leaving all other shipping methods at regular price, add "2-day shipping" to your list of Target Shipping Methods, set Target Cost to "Shipping costs", and set the discount value to 100%.</p>
        <p class="help-block">If you want to offer free sunglasses with the purchase of a t-shirt, add "sunglasses" to your Target Products, set the discount value to 100%, and add "t-shirt" to the list of Products under the "Rules" tab.</p>
                
    </div>
    <!-- /.col-md-2 -->
                
    <div class="col-md-10">
    
        <div class="form-group">
            <div class="row">
                <div class="col-md-4">
                    <label>Value</label>
                    <input type="text" name="discount_value" placeholder="10.00" value="<?php echo $flash->old('discount_value'); ?>" class="form-control" />
                </div>
                <div class="col-md-4">
                    <label>Type</label>
                    <input type="text" name="discount_type" placeholder="Flat Rate or Percentage" value="<?php echo $flash->old('discount_type'); ?>" class="form-control" />
                </div>                
                <div class="col-md-4">
                    <label>Currency</label>
                    <input type="text" name="discount_currency" placeholder="USD" value="<?php echo $flash->old('discount_currency'); ?>" class="form-control" />
                    <p class="help-block">Optional.  Only necessary when this is a flat-rate discount.</p>
                </div>                
            </div>            
        </div>
        <!-- /.form-group -->
        
        <div class="form-group">
            <label>Applied</label>
            <input type="text" name="discount_applied" placeholder="Per Order or Per Product" value="<?php echo $flash->old('discount_applied'); ?>" class="form-control" />
            <p class="help-block">If you have selected 'Per Product', this discount will be applied to every product in the shopper's cart.  If you want this discount to only apply to certain products, select those products below under "Target Products".</p>
            <p class="help-block">If you have selected 'Per Order', this discount will be applied to the order's subtotal.</p> 
        </div>
        <!-- /.form-group -->
        
        <div class="form-group">
            <label>Target Cost</label>
            <input type="text" name="discount_target" placeholder="Product costs or Shipping costs" value="<?php echo $flash->old('discount_target'); ?>" class="form-control" />
        </div>
        <!-- /.form-group -->
        
        <div class="form-group">
            <label>Target Products</label>
            <input type="text" name="discount_target_products[]" placeholder="All products that receive this discount" value="<?php echo $flash->old('discount_target_products'); ?>" class="form-control" />
        </div>
        <!-- /.form-group -->
        
        <div class="form-group">
            <label>Target Shipping Methods</label>
            <input type="text" name="discount_target_shipping_methods[]" placeholder="All shipping methods that receive this discount" value="<?php echo $flash->old('discount_target_shipping_methods'); ?>" class="form-control" />
        </div>
        <!-- /.form-group -->
        
    </div>
    <!-- /.col-md-10 -->
    
</div>
<!-- /.row -->

<hr />

<div class="row">
    <div class="col-md-2">
        
        <h3>Usage</h3>
        <p class="help-block">Some helpful text</p>
                
    </div>
    <!-- /.col-md-2 -->
                
    <div class="col-md-10">
    
        <div class="form-group">
            <label>Max Uses</label>
            <input type="text" name="usage_max" placeholder="Number of times total that this coupon code may be used" value="<?php echo $flash->old('usage_max'); ?>" class="form-control" />
        </div>
        <!-- /.form-group -->
    
        <div class="form-group">
            <label>Max Uses per Customer</label>
            <input type="text" name="usage_max_per_customer" placeholder="1 (number of times each customer may use this coupon code)" value="<?php echo $flash->old('usage_max_per_customer'); ?>" class="form-control" />
        </div>
        <!-- /.form-group -->
        
        <div class="form-group">
            <label>Can be used with other discounts?</label>
            <input type="text" name="usage_exclusive" placeholder="Yes or No (default)" value="<?php echo $flash->old('usage_exclusive'); ?>" class="form-control" />
        </div>
        <!-- /.form-group -->
        
        <div class="form-group">
            <label>Automatic?</label>
            <input type="text" name="usage_automatic" placeholder="No (normal, user-submitted) or Yes (automatically applied when rules are satisfied)" value="<?php echo $flash->old('usage_automatic'); ?>" class="form-control" />
        </div>
        <!-- /.form-group -->
        
    </div>
    <!-- /.col-md-10 -->
    
</div>
<!-- /.row -->

<hr />

<div class="row">
    <div class="col-md-2">
        
        <h3>Maximums</h3>
                
    </div>
    <!-- /.col-md-2 -->
                
    <div class="col-md-10">
    
        <div class="form-group">
            <div class="row">
                <div class="col-md-6">
                    <label>Value</label>
                    <input type="text" name="max_value" placeholder="10.00" value="<?php echo $flash->old('max_value'); ?>" class="form-control" />
                    <p class="help-block">The coupon value cannot exceed this amount.</p> 
                </div>
                <div class="col-md-6">
                    <label>Currency</label>
                    <input type="text" name="max_value_currency" placeholder="USD" value="<?php echo $flash->old('max_value_currency'); ?>" class="form-control" />
                </div>                
            </div>            
        </div>
        <!-- /.form-group -->
    
        
    </div>
    <!-- /.col-md-10 -->
    
</div>
<!-- /.row -->