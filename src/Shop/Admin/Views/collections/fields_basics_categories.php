<div class="row">
    <div class="col-md-2">
        
        <h3>Categories</h3>
        <p class="help-block">Any products in these categories will be included in the collection.</p>
                
    </div>
    <!-- /.col-md-2 -->
                
    <div class="col-md-10">

        <div id="categories-checkboxes" class="form-group">
            <?php if ($categories = \Shop\Models\Categories::find()) { ?>
            <div class="max-height-200 list-group-item">
                
                <?php $current = \DscArrayHelper::getColumn( (array) $flash->old('categories'), 'id' ); ?>
                <?php foreach ($categories as $one) { ?>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="categories[]" class="icheck-input" value="<?php echo $one->_id; ?>" <?php if (in_array($one->_id, $current)) { echo "checked='checked'"; } ?>>
                        <?php echo @str_repeat( "&ndash;", substr_count( @$one->path, "/" ) - 1 ) . " " . $one->title; ?>
                    </label>
                </div>
                <?php } ?> 
                
            </div>
            <?php } else {
            	echo "Please create a category first";
            } ?>
            
            <input type="hidden" name="categories[]" value="" />
        </div>
    
    </div>
    <!-- /.col-md-10 -->
    
</div>
<!-- /.row -->