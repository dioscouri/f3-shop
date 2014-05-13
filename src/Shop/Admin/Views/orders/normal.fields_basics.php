<div class="row">
    <div class="col-md-2">
        
        <h3>Basics</h3>
        <p class="help-block">Some helpful text</p>
                
    </div>
    <!-- /.col-md-2 -->
                
    <div class="col-md-10">
    
        <div class="form-group">
            <label>Name</label>
            <input type="text" name="name" placeholder="Name" value="<?php echo $flash->old('name'); ?>" class="form-control" />
        </div>
        <!-- /.form-group -->
        
        <div class="form-group">
        <label>Code</label>
             <input type="text" name="code" placeholder="Code" value="<?php echo $flash->old('code'); ?>" class="form-control" />
        </div>
        <!-- /.form-group -->
        
        <div class="form-group">
        <label>Country ISO Code 2</label>
             <input type="text" name="country_isocode_2" placeholder="Country ISO Code 2" value="<?php echo $flash->old('country_isocode_2'); ?>" class="form-control" />
        </div>
        <!-- /.form-group -->
        
    </div>
    <!-- /.col-md-10 -->
    
</div>
<!-- /.row --> 