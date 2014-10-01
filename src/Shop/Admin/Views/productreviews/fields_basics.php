<div class="row">
    <div class="col-md-2">
        
        <h3>Basics</h3>
                
    </div>
    <!-- /.col-md-2 -->
                
    <div class="col-md-10">
    
        <div class="form-group">
            <label>Title</label>
            <input type="text" name="title" placeholder="Title" value="<?php echo htmlspecialchars( $flash->old('title') ); ?>" class="form-control" />
        </div>
        <!-- /.form-group -->
        
        <div class="form-group">
            <label>Rating</label>
            <input name="rating" class="rating" data-size="xs" data-show-clear="false" data-show-caption="false" value="<?php echo $flash->old('rating'); ?>" data-step="1">
        </div>
        <!-- /.form-group -->
        
        <div class="form-group">
            <label>Description</label>
            <textarea name="description" class="form-control" rows="10"><?php echo $flash->old('description'); ?></textarea>
        </div>
        <!-- /.form-group -->
        
    </div>
    <!-- /.col-md-10 -->
    
</div>
<!-- /.row -->

<hr />

<?php echo $this->renderLayout('Shop/Admin/Views::productreviews/fields_basics_publication.php'); ?>

