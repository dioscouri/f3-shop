<div class="row">
    <div class="col-md-2">
        
        <h3>Basics</h3>
        <p class="help-block">Some helpful text</p>
                
    </div>
    <!-- /.col-md-2 -->
                
    <div class="col-md-10">
    
    	<div class="row">
	        <div class="form-group col-md-6">
	            <label>Name</label>
	            <input type="text" name="name" placeholder="Name" value="<?php echo $flash->old('name'); ?>" class="form-control" />
	        </div>
	        <!-- /.form-group -->
    	
	        <div class="form-group col-md-6">        
	            <label>Enabled</label>
	            <div class="form-group">
	                <label class="radio-inline">
	                    <input type="radio" name="enabled" value="1" <?php if ($flash->old('enabled')) { echo 'checked'; } ?>> Yes
	                </label>
	                <label class="radio-inline">
	                    <input type="radio" name="enabled" value="0" <?php if (!$flash->old('enabled')) { echo 'checked'; } ?>> No
	                </label>
	            </div>
	        </div>
	    </div>
        
    	<div class="row">
		    <div class="form-group col-md-4">
	        <label>ISO Code 2</label>
	             <input type="text" name="isocode_2" placeholder="ISO Code 2" value="<?php echo $flash->old('isocode_2'); ?>" class="form-control" />
	        </div>
	        <!-- /.form-group -->
	        
	        <div class="form-group col-md-4">
	        <label>ISO Code 3</label>
	             <input type="text" name="isocode_3" placeholder="ISO Code 3" value="<?php echo $flash->old('isocode_3'); ?>" class="form-control" />
	        </div>
	        <!-- /.form-group -->
	        
	        <div class="form-group col-md-4">
	        <label>Ordering</label>
	             <input type="text" name="ordering" placeholder="Ordering" value="<?php echo $flash->old('ordering'); ?>" class="form-control" />
	        </div>
	        <!-- /.form-group -->
	        </div>
    </div>
    <!-- /.col-md-10 -->
    
</div>
<!-- /.row --> 