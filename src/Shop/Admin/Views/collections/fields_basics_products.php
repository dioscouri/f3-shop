<div class="row">
    <div class="col-md-2">
        
        <h3>Products</h3>
        <p class="help-block">You can manually pick the products to be included in this collection.  If you use this field, <b>only</b> these products will be part of the collection.</p>
                
    </div>
    <!-- /.col-md-2 -->
                
    <div class="col-md-10">
    
        <div class="form-group">
            <label>Search</label>
            <div class="input-group">
                <input id="products" name="products" value="<?php echo implode(",", (array) $flash->old('products') ); ?>" type="text" class="form-control" /> 
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
    
    jQuery("#products").select2({
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
        <?php if ($flash->old('products')) { ?>
        , initSelection : function (element, callback) {
            var data = <?php echo json_encode( \Shop\Models\Products::forSelection( array('_id'=>array('$in'=>array_map( function($input){ return new \MongoId($input); }, $flash->old('products') ) ) ) ) ); ?>;
            callback(data);            
        }
        <?php } ?>
    });

});
</script>