<div class="row">
    <div class="col-md-2">
        
        <h3>Inventory Status</h3>
        <p class="help-block">Products with the selected inventory level will be included in the collection.</p>
                
    </div>
    <!-- /.col-md-2 -->
                
    <div class="col-md-10 padding-10">

        <div class="form-group">
        
            <div class="col-md-4 padding-10">
                <select name="inventory_status" class="form-control">
                    <option value="in_stock" <?php if ($flash->old('inventory_status') == 'in_stock') { echo "selected='selected'"; } ?>>In Stock</option>
                    <option value="low_stock" <?php if ($flash->old('inventory_status') == 'low_stock') { echo "selected='selected'"; } ?>>Low Stock (<20)</option>
                    <option value="no_stock" <?php if ($flash->old('inventory_status') == 'no_stock') { echo "selected='selected'"; } ?>>Out of Stock</option>
                </select> 
            </div>
            
        </div>
        <!-- /.form-group -->

    </div>
    <!-- /.col-md-10 -->
    
</div>
<!-- /.row -->