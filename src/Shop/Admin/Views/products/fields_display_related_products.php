<div class="row">
    <div class="col-md-2">
        
        <h3>Relatated Products</h3>
        <p class="help-block">Manually associate products with this item.</p>
                
    </div>
    <!-- /.col-md-2 -->
                
    <div class="col-md-10">
    
        <div class="form-group">
            <label>Search for Products</label>
            <div class="input-group">
                <input id="related-products" name="__related_products" value="<?php echo implode(",", (array) $flash->old('related_products') ); ?>" type="text" class="form-control" /> 
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
    
    jQuery("#related-products").select2({
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
        <?php if ($flash->old('_id') && $flash->old('related_products')) { ?>
        , initSelection : function (element, callback) {
            var data = <?php echo json_encode( \Shop\Models\Products::forSelection( array('_id'=>array('$in'=>array_map( function($input){ return new \MongoId($input); }, (array) $flash->old('related_products') ) ) ) ) ); ?>;
            callback(data);
        }
        <?php } ?>
    });

});
</script>