<div class="row">
    <div class="col-md-2">
        
        <h3>Basics</h3>
                
    </div>
    <!-- /.col-md-2 -->
                
    <div class="col-md-10">
    
        <div class="form-group">
            <label>Title</label>
            <input type="text" name="title" placeholder="Title" value="<?php echo $flash->old('title'); ?>" class="form-control" />
        </div>
        <!-- /.form-group -->
        
        <div class="form-group">
            <label>Slug</label>
            <input type="text" name="slug" placeholder="Slug" value="<?php echo $flash->old('slug'); ?>" class="form-control" />
        </div>
        <!-- /.form-group -->
        
        <div class="form-group">
            <label>Type</label>
            <select name="campaign_type" class="form-control">
                <option value="lto" <?php if ($flash->old('campaign_type') == 'lto') { echo "selected='selected'"; } ?>>Limited Time Offer</option>
                <option value="tiered" <?php if ($flash->old('campaign_type') == 'tiered') { echo "selected='selected'"; } ?>>Tiered</option>
            </select>            
        </div>
        <!-- /.form-group -->
        
        <div class="form-group">
            <label>Parent</label> 
            <select name="parent" class="form-control">
                <option value="null">None</option>
                <?php foreach (\Shop\Models\Campaigns::find() as $one) { ?>
                    <?php if ($one->id == $flash->old('_id')) { continue; } ?>
                    <option value="<?php echo $one->id; ?>" <?php if ($one->id == $flash->old('parent')) { echo "selected='selected'"; } ?>><?php echo $one->ancestorsIndentedTitle(); ?></option>                    
                <?php } ?> 
            </select>
        </div>
        <!-- /.form-group -->
        
    </div>
    <!-- /.col-md-10 -->
    
</div>
<!-- /.row -->

<hr />

<?php echo $this->renderLayout('Shop/Admin/Views::campaigns/fields_basics_publication.php'); ?>

<hr/>

<div class="row">
    <div class="col-md-2">
    
        <h3>Qualifying Period</h3>
        <p class="help-block">Define the period during which the customer must satisfy the rules in order to be eligible.</p>
                
    </div>
    <!-- /.col-md-2 -->
                
    <div class="col-md-10">

        <div class="form-group">
            <label>Period Type</label>
            <select name="period_type" class="form-control">
                <option value="variable" <?php echo ($flash->old('period_type') == 'variable') ? "selected='selected'" : null; ?>>Variable</option>
                <option value="fixed" <?php echo ($flash->old('period_type') == 'fixed') ? "selected='selected'" : null; ?>>Fixed</option>                
            </select>
        </div>
        <!-- /.form-group -->
        
        <div class="form-group">
            <div class="row">
                <div class="col-md-6">
                    <label>Variable Period</label>
                    <div class="input-group">
                        <input name="variable_period_days" value="<?php echo $flash->old('variable_period_days'); ?>" placeholder="7" class="form-control" type="text" />
                        <span class="input-group-addon">previous days</span>
                    </div>
                    <p class="help-block">Customer becomes eligible if they satisfy the rules anytime during the previous X days.</p>
                </div>
                <div class="col-md-6">
                    <label>Fixed Date Range</label>
                    <div class="form-group">
                        <div class="input-daterange input-group" id="datepicker">
                            <input type="text" name="fixed_period_start" value="<?php echo $flash->old('fixed_period_start'); ?>" class="input-sm ui-datepicker form-control" data-date-format="yyyy-mm-dd" data-date-today-highlight="true" data-date-today-btn="true" />
                            <span class="input-group-addon">to</span>
                            <input type="text" name="fixed_period_end" value="<?php echo $flash->old('fixed_period_end'); ?>" class="input-sm ui-datepicker form-control" data-date-format="yyyy-mm-dd" data-date-today-highlight="true" data-date-today-btn="true" />
                        </div>
                    </div>
                    <p class="help-block">Customer becomes eligible if they satisfy the rules anytime during the specified date range.</p>                    
                </div>
            </div>        
        </div>
        <!-- /.form-group -->
            
    </div>
    <!-- /.col-md-10 -->
</div>
<!-- /.row -->

<hr/>

<div class="row">
    <div class="col-md-2">
    
        <h3>Duration</h3>
        <p class="help-block">Define how long the customer keeps the benefits from the Rewards.</p>
                
    </div>
    <!-- /.col-md-2 -->
                
    <div class="col-md-10">
        
        <div class="form-group">
            <div class="row">
                <div class="col-md-6">
                    <div class="row">                        
                        <div class="col-md-3">
                            <label>Period</label>
                            <input name="duration_period_variable" value="<?php echo $flash->old('duration_period_variable'); ?>" placeholder="7" class="form-control" type="text" />
                        </div>
                        <div class="col-md-9">
                            <label>Period Type</label>
                            <select name="duration_period_type" class="form-control">
                                <option value="days" <?php echo ($flash->old('duration_period_type') == 'days') ? "selected='selected'" : null; ?>>Days</option>
                                <option value="weeks" <?php echo ($flash->old('duration_period_type') == 'weeks') ? "selected='selected'" : null; ?>>Weeks</option>
                                <option value="months" <?php echo ($flash->old('duration_period_type') == 'months') ? "selected='selected'" : null; ?>>Months</option>
                                <option value="years" <?php echo ($flash->old('duration_period_type') == 'years') ? "selected='selected'" : null; ?>>Years</option>
                                <?php /* ?><option value="orders" <?php echo ($flash->old('duration_period_type') == 'orders') ? "selected='selected'" : null; ?>>Completed Orders</option> */ ?>
                                <option value="forever" <?php echo ($flash->old('duration_period_type') == 'forever') ? "selected='selected'" : null; ?>>Forever</option>                
                            </select>                        
                        </div>
                    </div>
                    
                    <p class="help-block">If you want the customer to keep the benefits for 30 days, enter 30 for the 'Period' and select 'days' for the Period Type.</p>
                    <p class="help-block">The Customer's benefits period begins on the day they qualify.</p>
                </div>
            </div>        
        </div>
        <!-- /.form-group -->
            
    </div>
    <!-- /.col-md-10 -->
</div>
<!-- /.row -->

<hr/>