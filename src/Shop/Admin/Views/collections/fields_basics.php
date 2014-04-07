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
        <label>Slug</label>
             <input type="text" name="slug" placeholder="Slug" value="<?php echo $flash->old('slug'); ?>" class="form-control" />
        </div>
        <!-- /.form-group -->
        
    </div>
    <!-- /.col-md-10 -->
    
</div>
<!-- /.row -->

<hr />

<?php echo $this->renderLayout('Shop/Admin/Views::collections/fields_basics_publication.php'); ?>

<hr />

<?php echo $this->renderLayout('Shop/Admin/Views::collections/fields_basics_categories.php'); ?>

<hr />

<?php echo $this->renderLayout('Shop/Admin/Views::collections/fields_basics_tags.php'); ?>

<hr />

<?php echo $this->renderLayout('Shop/Admin/Views::collections/fields_basics_inventory.php'); ?>

<hr />

TODO: Add Rules on product age, price ranges, "sale" status, etc 