<div class="row">
    <div class="col-md-2">
    
        <h3>SEO</h3>
        <p class="help-block">Define how the item should appear to search engines.</p>
                
    </div>
    <!-- /.col-md-2 -->
                
    <div class="col-md-10">

        <div class="form-group">
            <label>Page Title</label>
            <input type="text" name="seo[page_title]" value="<?php echo $flash->old('seo.page_title'); ?>" class="form-control" />
            <p class="help-block">The item's title will be used if you leave this empty.</p>
        </div>
        <!-- /.form-group -->
        
        <div class="form-group">
            <label>Meta Description</label>
            <input type="text" name="seo[meta_description]" value="<?php echo $flash->old('seo.meta_description'); ?>" class="form-control" />
            <p class="help-block">Limit to 160 characters.  No HTML.  The item's descripion/copy will be used if you leave this empty.</p>
        </div>
        <!-- /.form-group -->
    
    </div>
    <!-- /.col-md-10 -->
</div>
<!-- /.row -->