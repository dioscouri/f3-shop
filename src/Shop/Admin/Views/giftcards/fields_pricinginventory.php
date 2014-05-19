<div class="row">
    <div class="col-md-2">
        
        <h3>Tracking</h3>
        <p class="help-block">Some helpful text</p>
                
    </div>
    <!-- /.col-md-2 -->
                
    <div class="col-md-10">
    
        <div class="form-group">
            <label>Model Number</label>
            <input type="text" name="tracking[model_number]" placeholder="Model Number" value="<?php echo $flash->old('tracking.model_number'); ?>" class="form-control" />
        </div>
        <!-- /.form-group -->
            
        <div class="form-group">
            <label>SKU</label>
            <input type="text" name="tracking[sku]" placeholder="SKU" value="<?php echo $flash->old('tracking.sku'); ?>" class="form-control" />
        </div>
        <!-- /.form-group -->
        
        <div class="form-group">
            <label>UPC <small>(Barcode)</small></label>
            <input type="text" name="tracking[upc]" placeholder="UPC" value="<?php echo $flash->old('tracking.upc'); ?>" class="form-control" />
        </div>
        <!-- /.form-group -->
        
    </div>
    <!-- /.col-md-10 -->
    
</div>
<!-- /.row -->

<hr />

<?php echo $this->renderLayout('Shop/Admin/Views::products/fields_pricinginventory_pricing.php'); ?>

<hr />

<?php echo $this->renderLayout('Shop/Admin/Views::products/fields_pricinginventory_policies.php'); ?>