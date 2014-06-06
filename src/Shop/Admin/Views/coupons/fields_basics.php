<div class="row">
    <div class="col-md-2">
        
        <h3>Basics</h3>
        <p class="help-block">Some helpful text</p>
                
    </div>
    <!-- /.col-md-2 -->
                
    <div class="col-md-10">
    
        <div class="form-group">
            <label>Title</label>
            <input type="text" name="title" placeholder="Title" value="<?php echo htmlspecialchars( $flash->old('title') ); ?>" class="form-control" />
        </div>
        <!-- /.form-group -->
        
        <div class="form-group">
            <label>Code</label>
            <input type="text" name="code" placeholder="Code" value="<?php echo $flash->old('code'); ?>" class="form-control" />
            <p class="help-block">This is the code that customers must submit to get the discount. It must be unique and it is not case sensitive.</p>
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
        <p class="help-block">For example, if want to offer free 2-day shipping while leaving all other shipping methods at regular price, add "2-day shipping" to your list of Target Shipping Methods, set 'Applied To' to "Order Shipping Costs", and set the discount value to 100%.</p>
        <p class="help-block">If you want to offer free sunglasses with the purchase of a t-shirt, add "sunglasses" to your Target Products, set 'Applied To' to "Product Subtotal", set the discount value to 100%, and add "t-shirt" to the list of Required Products under the "Rules" tab.</p>
                
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
                    <select name="discount_type" class="form-control">
                        <option value="flat-rate" <?php echo ($flash->old('discount_type') == 'flat-rate') ? "selected='selected'" : null; ?>>Flat Rate</option>
                        <option value="percentage" <?php echo ($flash->old('discount_type') == 'percentage') ? "selected='selected'" : null; ?>>Percentage</option>
                    </select>
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
            <label>Applied To</label>
            <select name="discount_applied" class="form-control">
                <option value="order_subtotal" <?php echo ($flash->old('discount_applied') == 'order_subtotal') ? "selected='selected'" : null; ?>>Order Subtotal</option>
                <option value="order_shipping" <?php echo ($flash->old('discount_applied') == 'order_shipping') ? "selected='selected'" : null; ?>>Order Shipping Costs</option>
                <option value="product_subtotal" <?php echo ($flash->old('discount_applied') == 'product_subtotal') ? "selected='selected'" : null; ?>>Product Subtotal</option>
                <option value="product_shipping" <?php echo ($flash->old('discount_applied') == 'product_shipping') ? "selected='selected'" : null; ?>>Product Shipping Costs</option>
                <option value="product_price_override" <?php echo ($flash->old('discount_applied') == 'product_price_override') ? "selected='selected'" : null; ?>>Override Product Price</option>
            </select>
                                
            <p class="help-block">If you have selected either of the Product options above, this discount will be applied to every product in the shopper's cart unless you select specific products below under "Target Products" or "Target Collections".</p>

        </div>
        <!-- /.form-group -->
        
        <div class="form-group">
            <label>Target Products</label>
            <input id="target_products" type="text" name="discount_target_products" placeholder="All products that receive this discount" value="<?php echo implode(",", (array) $flash->old('discount_target_products') ); ?>" class="form-control" />
        </div>
        <!-- /.form-group -->
        
        <div class="form-group">
            <label>Target Shipping Methods</label>
            <input id="target_shipping_methods" type="text" name="discount_target_shipping_methods" placeholder="All shipping methods that receive this discount" value="<?php echo implode(",", (array) $flash->old('discount_target_shipping_methods') ); ?>" class="form-control" />
        </div>
        <!-- /.form-group -->
        
        <div class="form-group">
            <label>Target Collections</label>
            <input id="target_collections" name="discount_target_collections" placeholder="All products in these collections will receive this discount" value="<?php echo implode(",", (array) $flash->old('discount_target_collections') ); ?>" type="text" class="form-control" />
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
            <select name="usage_with_others" class="form-control">
                <option value="0" <?php echo ($flash->old('usage_with_others') == '0') ? "selected='selected'" : null; ?>>No</option>
                <option value="1" <?php echo ($flash->old('usage_with_others') == '1') ? "selected='selected'" : null; ?>>Yes</option>
            </select>            
        </div>
        <!-- /.form-group -->
        
        <div class="form-group">
            <label>Automatic?</label>
            <select name="usage_automatic" class="form-control">
                <option value="0" <?php echo ($flash->old('usage_automatic') == '0') ? "selected='selected'" : null; ?>>No, normal user-submitted coupon</option>
                <option value="1" <?php echo ($flash->old('usage_automatic') == '1') ? "selected='selected'" : null; ?>>Yes, automatically applied when rules are satisfied</option>
            </select>            
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

<script>
jQuery(document).ready(function() {
    
    jQuery("#target_products").select2({
        allowClear: true, 
        placeholder: "Search...",
        multiple: true,
        minimumInputLength: 3,
        ajax: {
            url: "./admin/shop/products/forSelection",
            dataType: 'json',
            data: function (term, page) {
                return {
                    q: term
                };
            },
            results: function (data, page) {
                return {results: data.results};
            }
        }
        <?php if ($flash->old('discount_target_products')) { ?>
        , initSelection : function (element, callback) {
            var data = <?php echo json_encode( \Shop\Models\Products::forSelection( array('_id'=>array('$in'=>array_map( function($input){ return new \MongoId($input); }, $flash->old('discount_target_products') ) ) ) ) ); ?>;
            callback(data);            
        }
        <?php } ?>    
    });

    jQuery("#target_shipping_methods").select2({
        allowClear: true,
        placeholder: "Search...",
        multiple: true,
        data: <?php echo json_encode( \Shop\Models\ShippingMethods::forSelection() ); ?>,
    });

    jQuery("#target_collections").select2({
        allowClear: true, 
        placeholder: "Search...",
        multiple: true,
        minimumInputLength: 3,
        ajax: {
            url: "./admin/shop/collections/forSelection",
            dataType: 'json',
            data: function (term, page) {
                return {
                    q: term
                };
            },
            results: function (data, page) {
                return {results: data.results};
            }
        }
        <?php if ($flash->old('discount_target_collections')) { ?>
        , initSelection : function (element, callback) {
            var data = <?php echo json_encode( \Shop\Models\Collections::forSelection( array('_id'=>array('$in'=>array_map( function($input){ return new \MongoId($input); }, (array) $flash->old('discount_target_collections') ) ) ) ) ); ?>;
            callback(data);            
        }
        <?php } ?>
    });    
});
</script>