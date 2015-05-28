<link href="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/css/bootstrap-editable.css" rel="stylesheet"/>
<script src="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/js/bootstrap-editable.min.js"></script>
<script src="./ckeditor/ckeditor.js"></script>
<script>
jQuery(document).ready(function(){
    CKEDITOR.replaceAll( 'wysiwyg' );    
});
</script>

<div class="well">

<form id="detail-form" class="form" method="post">
    <div class="row">
        <div class="col-md-12">
            
            <div class="clearfix">

                <div class="pull-right">
	        	<?php if( $allow_preview ) { ?>
	            <a class="btn btn-warning" href="./shop/category/<?php echo $flash->old('slug'); ?>?preview=1" title="Unpublished Preview" target="_blank">Preview</a>
	            &nbsp;
	            <?php } ?>
	                <div class="btn-group">
                        <button type="submit" class="btn btn-primary">Save</button>
                        <input id="primarySubmit" type="hidden" value="save_edit" name="submitType" />
                        <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                            <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu" role="menu">
                            <li>
                                <a onclick="document.getElementById('primarySubmit').value='save_new'; document.getElementById('detail-form').submit();" href="javascript:void(0);">Save & Create Another</a>
                            </li>
                            <li>
                                <a onclick="document.getElementById('primarySubmit').value='save_as'; document.getElementById('detail-form').submit();" href="javascript:void(0);">Save As</a>
                            </li>
                            <li>
                                <a onclick="document.getElementById('primarySubmit').value='save_close'; document.getElementById('detail-form').submit();" href="javascript:void(0);">Save & Close</a>
                            </li>
                             <li>
                        <a onclick="document.getElementById('primarySubmit').value='save_next'; document.getElementById('detail-form').submit();" href="javascript:void(0);">Save & Next</a>
                    </li>
                        </ul>
                    </div>
                     &nbsp;
                    <a class="btn btn-primary" onclick="document.getElementById('primarySubmit').value='save_next'; document.getElementById('detail-form').submit();" href="javascript:void(0);">Save & Next</a>
                        
                    &nbsp;
                    <a class="btn btn-default" href="./admin/shop/categories">Cancel</a>
                </div>

            </div>
            <!-- /.form-group -->
            
            <hr />
            
            <div class="alert alert-info">
                <p><b>URL:</b> <a href="./shop/category<?php echo $flash->old('path'); ?>" target="_blank">./shop/category<?php echo $flash->old('path'); ?></a></p>
            </div>
            
            <ul class="nav nav-tabs">
                <li class="active">
                    <a href="#tab-basics" data-toggle="tab"> Basics </a>
                </li>
                
                 <?php if($item->getDepth() == 1 ): ?>
                <li>
                    <a href="#tab-top-level-display" data-toggle="tab">Top Level Display</a>
                </li>  
                <?php else : ?>   
                <li>
                    <a href="#tab-display" data-toggle="tab">Display</a>
                </li> 
                <?php endif; ?> 
                
                <li>
                    <a href="#tab-product_specs" data-toggle="tab">Product Specs</a>
                </li>  
                
                 
               
                       
                <?php if (!empty($this->event)) { foreach ((array) $this->event->getArgument('tabs') as $key => $title ) { ?>
                <li>
                    <a href="#tab-<?php echo $key; ?>" data-toggle="tab"> <?php echo $title; ?> </a>
                </li>
                <?php } } ?>
            </ul>
            
            <div class="tab-content">

                <div class="tab-pane active" id="tab-basics">
                
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
                            
                           
                            <div class="form-group">
                                <?php  echo $this->renderLayout('Shop/Admin/Views::categories/list_parents.php'); ?>
                            </div>
                            <!-- /.form-group -->
                            
                            <div class="form-group">
                                <label>Display</label>
                               
                                <select name="display[view]" class="form-control">
                                
                                    <option value="" <?php if (!$flash->old('display.view')) { echo "selected"; } ?>>-- Default --</option>
                                    <?php $variants = \Dsc\System::instance()->get('theme')->variants( 'Shop/Site/Views::category/index.php' ); ?>
                                    <?php foreach ($variants as $group=>$views) { ?>
                                        <optgroup label="<?php echo $group; ?>">
                                            <?php foreach ($views as $view) { ?>
                                            <option value="<?php echo $view; ?>" <?php if ($flash->old('display.view') == $view) { echo "selected"; } ?>><?php echo $view; ?></option>
                                            <?php } ?>
                                        </optgroup>
                                    <?php } ?>
                                </select>
                            </div>
                            <!-- /.form-group -->
                                
                                 <div class="form-group">
                                <label>Disable YMM Support</label>
        <p class="help-block">Disable YMM filtering on this Category</p>
 <div class="checkbox">
  <label>
    <input type="checkbox" name="disableymm" value="1" <?php if($flash->old('disableymm') == 1) {echo 'checked="checked"';} ?>>
   If checked Ymm Filtering will be disabled
  </label>
</div>                            </div>
                           
    
  

<hr/>
                        </div>
                        <!-- /.col-md-10 -->
                        
                    </div>
                    <!-- /.row -->        
                    
                    <hr />
                    
                    <?php  echo $this->renderLayout('Shop/Admin/Views::categories/fields_seo.php'); ?>
                    
                    <hr/>
                    
                    <?php  echo $this->renderLayout('Shop/Admin/Views::categories/fields_basics_products.php'); ?>
                
                </div>
                <!-- /.tab-pane -->
                   <?php if($item->getDepth() == 1 ): ?>
                <div class="tab-pane" id="tab-top-level-display">
                    <?php  echo $this->renderLayout('Shop/Admin/Views::categories/fields_top_level_display.php'); ?>
                </div>
                  <?php else : ?>  
                <div class="tab-pane" id="tab-display">
                    <?php  echo $this->renderLayout('Shop/Admin/Views::categories/fields_display.php'); ?>
                </div>
             	 <?php endif; ?>   
                
                 <div class="tab-pane" id="tab-product_specs"  class="col-lg-12">
                 <?php  echo $this->renderLayout('Shop/Admin/Views::categories/product_specs.php'); ?>
                </div>
                <!-- /.tab-pane -->                  
                
                <?php if (!empty($this->event)) { foreach ((array) $this->event->getArgument('content') as $key => $content ) { ?>
                <div class="tab-pane" id="tab-<?php echo $key; ?>">
                    <?php echo $content; ?>
                </div>
                <?php } } ?>
            
            </div>

        </div>
        
    </div>
</form>
</div>
