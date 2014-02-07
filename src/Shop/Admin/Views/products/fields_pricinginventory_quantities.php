<div class="row">
    <div class="col-md-2">
        
        <h3>Quantity</h3>
        <p class="help-block">Some helpful text</p>
                
    </div>
    <!-- /.col-md-2 -->
                
    <div class="col-md-10">

        <div class="form-group">
        
            <div class="row clearfix">
                <div class="col-md-2">        
                    <label>Quantity</label>
                    <input type="text" name="quantities[manual]" placeholder="Quantity" value="<?php echo $flash->old('quantities.manual'); ?>" class="form-control" />
                </div>
                
                <div class="col-md-10">
                    <div class="alert alert-warning">Note: If your product has Variants and inventory is tracked (setting below), this value will be automatically calculated using Variant quantities.</div>
                </div>
            </div>        
        
        </div>
        <!-- /.form-group -->
        
    </div>
</div>    