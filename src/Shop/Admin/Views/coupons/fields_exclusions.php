<div class="alert alert-info">
    <p><b>Note:</b> Exclusions only apply when the coupon discount is applied at the product level (Product Subtotal, Product Shipping Cost, or Product Price Override).</p>
</div>

<div class="row">
    <div class="col-md-2">
        
        <h3>Excluded Products</h3>
        <p class="help-block">This coupon will not be applied to any of these products.</p>
                
    </div>
    <!-- /.col-md-2 -->
                
    <div class="col-md-10">
    
        <div class="form-group">
            <label>Search</label>
            <div class="input-group">
                <input id="excluded_products" name="excluded_products" value="<?php echo implode(",", (array) $flash->old('excluded_products') ); ?>" type="text" class="form-control" /> 
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
        
        <h3>Excluded Collection</h3>
        <p class="help-block">This coupon will not be applied to any of the products from the selected collections.</p>
                
    </div>
    <!-- /.col-md-2 -->
                
    <div class="col-md-10">
    
        <div class="form-group">
            <label>Search</label>
            <div class="input-group">
                <input id="excluded_collections" name="excluded_collections" value="<?php echo implode(",", (array) $flash->old('excluded_collections') ); ?>" type="text" class="form-control" /> 
            </div>
            <!-- /.form-group -->        
            
        </div>
        <!-- /.form-group -->
        
    </div>
    <!-- /.col-md-10 -->
    
</div>
<!-- /.row -->

<script>
jQuery(document).ready(function() {
    
    jQuery("#excluded_products").select2({
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
        <?php if ($flash->old('excluded_products')) { ?>
        , initSelection : function (element, callback) {
            var data = <?php echo json_encode( \Shop\Models\Products::forSelection( array('_id'=>array('$in'=>array_map( function($input){ return new \MongoId($input); }, (array) $flash->old('excluded_products') ) ) ) ) ); ?>;
            callback(data);            
        }
        <?php } ?>
    });

    jQuery("#excluded_collections").select2({
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
        <?php if ($flash->old('excluded_collections')) { ?>
        , initSelection : function (element, callback) {
            var data = <?php echo json_encode( \Shop\Models\Collections::forSelection( array('_id'=>array('$in'=>array_map( function($input){ return new \MongoId($input); }, (array) $flash->old('excluded_collections') ) ) ) ) ); ?>;
            callback(data);            
        }
        <?php } ?>
    });

});
</script>