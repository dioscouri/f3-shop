<div class="row">
    <div class="col-md-2">
        
        <h3>Prices</h3>
        <p class="help-block">Some helpful text</p>
                
    </div>
    <!-- /.col-md-2 -->
                
    <div class="col-md-10">

        <div class="form-group">
        
            <div class="row clearfix">
                <div class="col-md-6">        
                    <label>Default Price</label>
                    <input type="text" name="prices[default]" placeholder="Default Price" value="<?php echo $flash->old('prices.default'); ?>" class="form-control" />
                </div>
                
                <div class="col-md-6">
                    <label>List Price <small>(Optional - Normally displayed with a <strike>strikethrough</strike>)</small></label>
                    <input type="text" name="prices[list]" placeholder="List Price" value="<?php echo $flash->old('prices.list'); ?>" class="form-control" />
                </div>
            </div>        
        
        </div>
        <!-- /.form-group -->
        
    </div>
</div>    