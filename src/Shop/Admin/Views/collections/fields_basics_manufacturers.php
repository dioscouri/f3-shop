<div class="row">
    <div class="col-md-2">
        
        <h3>Manufacturers</h3>
        <p class="help-block">All products from these manufacturers will be included in the collection.</p>
                
    </div>
    <!-- /.col-md-2 -->
                
    <div class="col-md-10">

        <div id="manufacturers-checkboxes" class="form-group">
            <?php if ($manufacturers = \Shop\Models\Manufacturers::find()) { ?>
            <div class="max-height-200 list-group-item">
                
                <?php $current = (array) $flash->old('manufacturers'); ?>
                <?php foreach ($manufacturers as $one) { ?>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="manufacturers[]" class="icheck-input" value="<?php echo $one->_id; ?>" <?php if (in_array($one->_id, $current)) { echo "checked='checked'"; } ?>>
                        <?php echo $one->title; ?>
                    </label>
                </div>
                <?php } ?> 
                
            </div>
            <?php } else {
                echo "Please create a manufacturer first";
            } ?>
            
            <input type="hidden" name="manufacturers[]" value="" />
        </div>

    </div>
    <!-- /.col-md-10 -->
    
</div>
<!-- /.row -->