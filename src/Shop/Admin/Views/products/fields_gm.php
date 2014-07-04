<div class="row">
    <div class="col-md-2">
        
        <h3>Google Product Categorization</h3>
                
    </div>
    <!-- /.col-md-2 -->
                
    <div class="col-md-10">
    
        <div class="form-group">
            <label>Product Category</label>
            <input id="gm_product_category" name="gm_product_category" value="<?php echo htmlspecialchars( $flash->old('gm_product_category') ); ?>" type="text" class="form-control" />
            <p class="help-block">You should not select one of the top-level categories.  Only 1 product category is allowed.</p>            
        </div>
        <!-- /.form-group -->
        
    </div>
    <!-- /.col-md-10 -->
    
</div>
<!-- /.row -->

<script>
jQuery(document).ready(function() {
    
    jQuery("#gm_product_category").select2({
        allowClear: true, 
        placeholder: "Search...",
        multiple: false,
        minimumInputLength: 3,
        ajax: {
            url: "./admin/shop/categories/google-merchant/forSelection",
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
        <?php if ($flash->old('gm_product_category')) { ?>
        , initSelection : function (element, callback) {
            var data = <?php echo json_encode( \Shop\Models\GoogleMerchantTaxonomy::forSelection( $flash->old('gm_product_category') ) ); ?>;
            callback(data[0]);            
        }
        <?php } ?>    
    });

});
</script>