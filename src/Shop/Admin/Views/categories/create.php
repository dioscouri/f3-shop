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
                        </ul>
                    </div>
                        
                    &nbsp;
                    <a class="btn btn-default" href="./admin/shop/categories">Cancel</a>
                </div>

            </div>
            <!-- /.form-group -->
            
            <hr />
            
            <ul class="nav nav-tabs">
                <li class="active">
                    <a href="#tab-basics" data-toggle="tab"> Basics </a>
                </li>
                <li>
                    <a href="#tab-display" data-toggle="tab">Display</a>
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
                                <?php echo $this->renderLayout('Shop/Admin/Views::categories/list_parents.php'); ?>
                            </div>
                            <!-- /.form-group -->
                                
                        </div>
                        <!-- /.col-md-10 -->
                        
                    </div>
                    <!-- /.row -->               
                    
                    <hr/>
                    
                    <?php echo $this->renderLayout('Shop/Admin/Views::categories/fields_basics_products.php'); ?>
                
                </div>
                <!-- /.tab-pane -->
                
                <div class="tab-pane" id="tab-display">
                
                    <?php echo $this->renderLayout('Shop/Admin/Views::categories/fields_display.php'); ?>
                
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