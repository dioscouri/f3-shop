<div class="row">
    <div class="col-md-2">
        
        <h3>Tags</h3>
        <p class="help-block">Any products tagged with these tags will be included in the collection.</p>
                
    </div>
    <!-- /.col-md-2 -->
                
    <div class="col-md-10 padding-10">

        <div class="form-group">
            <input name="tags" data-tags='<?php echo json_encode( \Shop\Models\Manufacturers::getTags() ); ?>' value="<?php echo implode(",", (array) $flash->old('tags') ); ?>" type="text" class="form-control ui-select2-tags" /> 
        </div>
        <!-- /.form-group -->

    </div>
    <!-- /.col-md-10 -->
    
</div>
<!-- /.row -->