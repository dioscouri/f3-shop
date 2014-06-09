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

<hr />