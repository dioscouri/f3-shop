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
        <p class="help-block">Some helpful text</p>
                
    </div>
    <!-- /.col-md-2 -->
                
    <div class="col-md-10">
    
        <div class="form-group">
            <label>Value</label>
            <input type="text" name="discount_value" placeholder="10.00" value="<?php echo $flash->old('discount_value'); ?>" class="form-control" />
        </div>
        <!-- /.form-group -->
        
        <div class="form-group">
            <label>Type</label>
            <input type="text" name="discount_type" placeholder="Flat Rate or Percentage" value="<?php echo $flash->old('discount_type'); ?>" class="form-control" />
        </div>
        <!-- /.form-group -->
        
        <div class="form-group">
            <label>Applied</label>
            <input type="text" name="discount_applied" placeholder="Per Order or Per Product" value="<?php echo $flash->old('discount_applied'); ?>" class="form-control" />
            <p class="help-block">If you have selected 'Per Product', this discount will be applied to every product in the shopper's cart.  If you want this to only apply to certain product, select those products in the "Rules" tab.</p>
        </div>
        <!-- /.form-group -->
        
        <div class="form-group">
            <label>Target</label>
            <input type="text" name="discount_target" placeholder="Product costs or Shipping costs" value="<?php echo $flash->old('discount_target'); ?>" class="form-control" />
        </div>
        <!-- /.form-group -->
        
        <div class="form-group">
            <label>Currency?</label>
            <input type="text" name="discount_currency" placeholder="USD" value="<?php echo $flash->old('discount_currency'); ?>" class="form-control" />
        </div>
        <!-- /.form-group -->
        
        <div class="form-group">
            <label>Automatic?</label>
            <input type="text" name="discount_automatic" placeholder="No (normal, user-submitted) or Yes (automatically applied upon add-to-cart)" value="<?php echo $flash->old('discount_automatic'); ?>" class="form-control" />
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
            <label>Max Uses per Customer</label>
            <input type="text" name="usage_max_per_customer" placeholder="1 (number of times customer may use this coupon code)" value="<?php echo $flash->old('usage_max_per_customer'); ?>" class="form-control" />
        </div>
        <!-- /.form-group -->
        
        <div class="form-group">
            <label>Can be used with other discounts?</label>
            <input type="text" name="usage_exclusive" placeholder="Yes or No (default)" value="<?php echo $flash->old('usage_exclusive'); ?>" class="form-control" />
        </div>
        <!-- /.form-group -->
        
    </div>
    <!-- /.col-md-10 -->
    
</div>
<!-- /.row -->