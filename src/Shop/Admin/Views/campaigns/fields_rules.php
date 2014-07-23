<div class="row">
    <div class="col-md-2">
        
        <h3>Spend</h3>
        <p class="help-block">The amount the customer must spend during the Qualifying Period in order to earn the rewards.</p>
                
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

<div class="row">
    <div class="col-md-2">
        
        <h3>Shopper Groups</h3>
                        
    </div>
    <!-- /.col-md-2 -->
                
    <div class="col-md-10">
    
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <?php if ((array) $groups = \Users\Models\Groups::find() ) { ?>
                    <div class="max-height-200 list-group-item">
                        <?php foreach ($groups as $one) { ?>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="groups[]" class="icheck-input" value="<?php echo $one->_id; ?>" <?php if (in_array($one->_id, (array) $flash->old('groups'))) { echo "checked='checked'"; } ?>>
                                <?php echo $one->title;  ?>
                            </label>
                        </div>
                        <?php } ?> 
                        
                    </div>
                    <?php } ?>
                    <input type="hidden" name="groups[]" value="" />                        
                </div>
                <!-- /.form-group -->
            </div>
            <div class="col-md-6">
                <label>Matching Method</label>
                <select name="groups_method" class="form-control">
                    <option value="one" <?php if ($flash->old('groups_method') == "one") { echo "selected='selected'"; } ?>>At least one</option>
                    <option value="all" <?php if ($flash->old('groups_method') == "all") { echo "selected='selected'"; } ?>>Must be in all</option>
                    <option value="none" <?php if ($flash->old('groups_method') == "none") { echo "selected='selected'"; } ?>>Cannot be in any</option>
                </select>

            </div>            
        </div>
        
    </div>
    <!-- /.col-md-10 -->
    
</div>
<!-- /.row -->