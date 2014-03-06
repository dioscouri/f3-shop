<div class="row">
    <div class="col-md-2">
        
        <h3>Basics</h3>
        <p class="help-block">Some helpful text</p>
                
    </div>
    <!-- /.col-md-2 -->
                
    <div class="col-md-10">
    
        <div class="form-group">
            <label>Title</label>
            <input type="text" name="title" placeholder="Title" value="<?php echo $flash->old('title'); ?>" class="form-control" />
        </div>
        <!-- /.form-group -->
        
        <div class="form-group">
            <label>Short Description - Used in Grid and List layouts</label>
            <textarea name="description" class="form-control"><?php echo $flash->old('description'); ?></textarea>
        </div>
        <!-- /.form-group -->
        
        <div class="form-group">
            <label>Full Description - Used only on the product detail page</label>
            <textarea name="copy" class="form-control wysiwyg"><?php echo $flash->old('copy'); ?></textarea>
        </div>
        <!-- /.form-group -->
        
    </div>
    <!-- /.col-md-10 -->
    
</div>
<!-- /.row -->

<hr />

<?php echo $this->renderLayout('Shop/Admin/Views::products/fields_basics_manufacturer.php'); ?>

<hr />

<?php echo $this->renderLayout('Shop/Admin/Views::products/fields_basics_categories.php'); ?>

<hr />

<?php echo $this->renderLayout('Shop/Admin/Views::products/fields_basics_tags.php'); ?>

<hr />

<?php echo $this->renderLayout('Shop/Admin/Views::products/fields_basics_publication.php'); ?>