<form id="settings-form" role="form" method="post" class="clearfix">

    <div class="clearfix">
        <div class="pull-right">
            <button type="submit" class="btn btn-primary">Save Changes</button>
            &nbsp;
            <a class="btn btn-default" href="./admin/shop/payment-methods">Close</a>
        </div>        
    </div>

    <h2>CCAvenue Settings</h2>
    
    <div class="panel panel-default">
        <div class="panel-body">
        
            <div class="row">
                <div class="col-md-2">
                    
                    <h3>Enabled</h3>
                            
                </div>
                <!-- /.col-md-2 -->
                            
                <div class="col-md-10">
                
                    <div class="form-group">
                        <label>Enable this payment method?</label>
                        <select name="enabled" class="form-control">
                            <option value="0" <?php echo !$model->{'enabled'} ? 'selected' : null; ?>>No</option>
                            <option value="1" <?php echo $model->{'enabled'} ? 'selected' : null; ?>>Yes</option>
                        </select> 
                    </div>
                    <!-- /.form-group -->
        
                </div>
                <!-- /.col-md-10 -->
                
            </div>
            <!-- /.row -->        
            
            <hr />        
        
            <div class="row">
                <div class="col-md-2">
                    
                    <h3>Mode</h3>
                            
                </div>
                <!-- /.col-md-2 -->
                            
                <div class="col-md-10">
                
                    <div class="form-group">
                        <label>Live or Test?</label>
                        <select name="settings[mode]" class="form-control">
                            <option value="test" <?php echo ($model->{'settings.mode'} == 'test') ? 'selected' : null; ?>>Test</option>
                            <option value="live" <?php echo ($model->{'settings.mode'} == 'live') ? 'selected' : null; ?>>Live</option>
                        </select> 
                    </div>
                    <!-- /.form-group -->
        
                </div>
                <!-- /.col-md-10 -->
                
            </div>
            <!-- /.row -->        
            
            <hr />        
        
            <div class="row">
                <div class="col-md-2">
                    
                    <h3>Credentials</h3>
                            
                </div>
                <!-- /.col-md-2 -->
                            
                <div class="col-md-10">
                
                    <div class="form-group">
                        <label>Merchant ID</label>
                        <input name="settings[merchant_id]" value="<?php echo $model->{'settings.merchant_id'}; ?>" type="text" class="form-control" /> 
                    </div>
                    <!-- /.form-group -->
                    
                    <div class="form-group">
                        <label>Access Code</label>
                        <input name="settings[access_code]" value="<?php echo $model->{'settings.access_code'}; ?>" type="text" class="form-control" /> 
                    </div>
                    <!-- /.form-group -->
                    
                    <div class="form-group">
                        <label>Encryption Key</label>
                        <input name="settings[encryption_key]" value="<?php echo $model->{'settings.encryption_key'}; ?>" type="text" class="form-control" /> 
                    </div>
                    <!-- /.form-group -->
        
                </div>
                <!-- /.col-md-10 -->
                
            </div>
            <!-- /.row -->        
                    
        </div>
    </div>
        
</form>