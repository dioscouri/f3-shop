<div class="row">
    <div class="col-md-2">
        
        <h3>Tracking</h3>
        <p class="help-block">Some helpful text</p>
                
    </div>
    <!-- /.col-md-2 -->
                
    <div class="col-md-10">
    
        <div class="form-group">
            <label>SKU</label>
            <input type="text" name="inventory[sku]" placeholder="SKU" value="<?php echo $flash->old('inventory.sku'); ?>" class="form-control" />
        </div>
        <!-- /.form-group -->
        
        <div class="form-group">
            <label>UPC <small>(Barcode)</small></label>
            <input type="text" name="inventory[upc]" placeholder="UPC" value="<?php echo $flash->old('inventory.upc'); ?>" class="form-control" />
        </div>
        <!-- /.form-group -->
        
    </div>
    <!-- /.col-md-10 -->
    
</div>
<!-- /.row -->

<hr />

<div class="row">
    <div class="col-md-2">
        
        <h3>Pricing</h3>
        <p class="help-block">Some helpful text</p>
                
    </div>
    <!-- /.col-md-2 -->
                
    <div class="col-md-10">
    
        <div class="form-group">
            <label>Price</label>
            <input type="text" name="prices[base]" placeholder="Price" value="<?php echo $flash->old('prices.base'); ?>" class="form-control" />
        </div>
        <!-- /.form-group -->
        
        <div class="form-group">
            <label>List Price <small>(Normally displayed with a <strike>strikethrough</strike>)</small></label>
            <input type="text" name="prices[list]" placeholder="List Price" value="<?php echo $flash->old('prices.list'); ?>" class="form-control" />
        </div>
        <!-- /.form-group -->
        
    </div>
    <!-- /.col-md-10 -->
    
</div>
<!-- /.row -->

<hr />

<?php echo $this->renderLayout('Tienda/Admin/Views::products/fields_pricinginventory_variants.php'); ?>

<hr />

<?php echo $this->renderLayout('Tienda/Admin/Views::products/fields_pricinginventory_taxes.php'); ?>

<hr />

<?php echo $this->renderLayout('Tienda/Admin/Views::products/fields_pricinginventory_shipping.php'); ?>

<hr />

<?php echo $this->renderLayout('Tienda/Admin/Views::products/fields_pricinginventory_policies.php'); ?>