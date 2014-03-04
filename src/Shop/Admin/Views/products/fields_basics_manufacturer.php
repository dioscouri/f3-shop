<div class="row">
    <div class="col-md-2">
        
        <h3>Manufacturer</h3>
        <p class="help-block">Some helpful text</p>
                
    </div>
    <!-- /.col-md-2 -->
                
    <div class="col-md-10">

        <div class="form-group">

            <select name="manufacturer[id]" class="form-control">
                <option value="">None</option>
                <?php foreach (\Shop\Models\Manufacturers::find() as $one) { ?>
                    <option value="<?php echo $one->id; ?>" <?php if ($one->id == $flash->old('manufacturer.id')) { echo "selected='selected'"; } ?>><?php echo $one->title; ?></option>                    
                <?php } ?> 
            </select>
                    

        </div>
        <!-- /.form-group -->
    
    </div>
    <!-- /.col-md-10 -->
    
</div>
<!-- /.row -->