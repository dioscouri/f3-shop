<div class="row">
    <div class="col-md-2">
        
        <h3>Category Image</h3>
        <p class="help-block">Some helpful text</p>
                
    </div>
    <!-- /.col-md-2 -->
                
    <div class="col-md-10">
    
        <div class="form-group">
            <label>Category Image</label>
            <?php if(is_array($flash->old('category_image.slug'))) : ?>
         <?php echo \Assets\Admin\Controllers\Assets::instance()->fetchElementImage('category_image','', array('field'=>'category_image[slug]') ); ?>
            
            <?php else :?>
            <?php echo \Assets\Admin\Controllers\Assets::instance()->fetchElementImage('category_image', $flash->old('category_image.slug'), array('field'=>'category_image[slug]') ); ?>
       <?php endif;?>
        </div>
        <!-- /.form-group -->
        
    </div>
    <!-- /.col-md-10 -->
    
</div>
<!-- /.row -->
<div class="row">
    <div class="col-md-2">
        
        <h3>Featured Image</h3>
        <p class="help-block">Some helpful text</p>
                
    </div>
    <!-- /.col-md-2 -->
                
    <div class="col-md-10">
    
        <div class="form-group">
            <label>Primary Image</label>
             <?php if(is_array($flash->old('featured_image.slug'))) :
            $flash->set('featured_image.slug', '');
            endif;?>
            <?php echo \Assets\Admin\Controllers\Assets::instance()->fetchElementImage('featured_image', $flash->old('featured_image.slug'), array('field'=>'featured_image[slug]') ); ?>
        </div>
        <!-- /.form-group -->
        
    </div>
    <!-- /.col-md-10 -->
    
</div>
<!-- /.row -->

<hr/>



<div class="row">
    <div class="col-md-2">
        
        <h3>Description</h3>
        <p class="help-block">Some helpful text</p>
                
    </div>
    <!-- /.col-md-2 -->
                
    <div class="col-md-10">
    
        <div class="form-group">
            <label>Description</label>
            <textarea name="description" class="form-control wysiwyg"><?php echo $flash->old('description'); ?></textarea>
        </div>
        <!-- /.form-group -->
        
    </div>
    <!-- /.col-md-10 -->
    
</div>
<!-- /.row -->

<hr/>