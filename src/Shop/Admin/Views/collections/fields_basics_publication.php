<div class="row">
    <div class="col-md-2">
        
        <h3>Publication Status</h3>
        <p class="help-block">Products with the selected publication status will be included in the collection.</p>
                
    </div>
    <!-- /.col-md-2 -->
                
    <div class="col-md-10">

        <div class="row">
        
            <div class="col-md-4">
                <select name="publication_status" class="form-control">
                    <option value="published" <?php if ($flash->old('publication_status') == 'published') { echo "selected='selected'"; } ?>>Published</option>
                    <option value="unpublished" <?php if ($flash->old('publication_status') == 'unpublished') { echo "selected='selected'"; } ?>>Unpublished</option>
                </select> 
            </div>
            
        </div>
        <!-- /.form-group -->

    </div>
    <!-- /.col-md-10 -->
    
</div>
<!-- /.row -->