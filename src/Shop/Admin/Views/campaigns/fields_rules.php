<div class="row">
    <div class="col-md-2">
        
        <h3>Spend</h3>
        <p class="help-block">The amount the customer must spend during the Publication period in order to qualify.</p>
                
    </div>
    <!-- /.col-md-2 -->
                
    <div class="col-md-10">

        <div class="form-group">
            <div class="row">
                <div class="col-md-4">
                    <label>Total spent during period</label>
                    <input type="text" name="rule_min_spent" placeholder="0.00" value="<?php echo $flash->old('rule_min_spent'); ?>" class="form-control" />            
                </div>
            </div>
        </div>
        <!-- /.form-group -->
        
    </div>
    <!-- /.col-md-10 -->
    
</div>
<!-- /.row -->

<hr />