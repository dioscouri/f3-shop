<div class="row">
    <div class="col-md-2">
        
        <h3>Price Range</h3>
        <p class="help-block">Products whose price falls within the specified range will be included in the collection.</p>
                
    </div>
    <!-- /.col-md-2 -->
                
    <div class="col-md-10">

        <div class="row">
            
            <div class="col-md-3">
                <label>Minimum</label>
                <input name="price_minimum" value="<?php echo $flash->old('price_minimum'); ?>" type="text" class="form-control" />
            </div>
            
            <div class="col-md-3">
                <label>Maximum</label>
                <input name="price_maximum" value="<?php echo $flash->old('price_maximum'); ?>" type="text" class="form-control" />        
            </div>
            
            <div class="col-md-3">
                <?php /* TODO Enable when ready ?>
                <label>Price Type</label>
                <select name="price_type" class="form-control">
                    <option value="default" <?php if ($flash->old('price_type') == 'default') { echo "selected='selected'"; } ?>>Default</option>
                    <option value="list" <?php if ($flash->old('price_type') == 'list') { echo "selected='selected'"; } ?>>List</option>
                    <option value="wholesale" <?php if ($flash->old('price_type') == 'wholesale') { echo "selected='selected'"; } ?>>Wholesale</option>
                </select> 
                */ ?>
            </div>
            
            <div class="col-md-3">
                <?php /* TODO Enable when ready ?>
                <label>Currency</label>
                <select name="price_currency" class="form-control">
                    <option value="USD" <?php if ($flash->old('price_currency') == 'USD') { echo "selected='selected'"; } ?>>USD</option>
                </select>
                */ ?> 
            </div>
        </div>
        <!-- /.form-group -->

    </div>
    <!-- /.col-md-10 -->
    
</div>
<!-- /.row -->