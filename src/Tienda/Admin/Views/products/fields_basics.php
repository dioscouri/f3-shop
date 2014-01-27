<div class="row">
    <div class="col-md-2">
        
        <h3>Product Details</h3>
        <p class="help-block">Some helpful text</p>
                
    </div>
    <!-- /.col-md-2 -->
                
    <div class="col-md-10">
    
        <div class="form-group">
            <label>Title</label>
            <input type="text" name="metadata[title]" placeholder="Title" value="<?php echo $flash->old('metadata.title'); ?>" class="form-control" />
        </div>
        <!-- /.form-group -->
        
        <div class="form-group">
            <label>Description</label>
            <textarea name="details[copy]" class="form-control wysiwyg"><?php echo $flash->old('details.copy'); ?></textarea>
        </div>
        <!-- /.form-group -->
        
    </div>
    <!-- /.col-md-10 -->
    
</div>
<!-- /.row -->

<hr />

<?php echo $this->renderLayout('Tienda/Admin/Views::products/fields_basics_manufacturer.php'); ?>

<hr />

<?php echo $this->renderLayout('Tienda/Admin/Views::products/fields_basics_categories.php'); ?>

<hr />

<?php echo $this->renderLayout('Tienda/Admin/Views::products/fields_basics_tags.php'); ?>

<hr />

<?php echo $this->renderLayout('Tienda/Admin/Views::products/fields_basics_publication.php'); ?>