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
            
            <div class="alert alert-info">
                <p><b>URL:</b> <a href="./shop/category<?php echo $flash->old('path'); ?>" target="_blank">./shop/category<?php echo $flash->old('path'); ?></a></p>
            </div>
            
            <ul class="nav nav-tabs">
                <li class="active">
                    <a href="#tab-basics" data-toggle="tab"> Basics </a>
                </li>
                <?php foreach ((array) $this->event->getArgument('tabs') as $key => $title ) { ?>
                <li>
                    <a href="#tab-<?php echo $key; ?>" data-toggle="tab"> <?php echo $title; ?> </a>
                </li>
                <?php } ?>                
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
                                <?php echo $this->renderLayout('Shop/Admin/Views::categories/list_parents.php'); ?>
                            </div>
                            <!-- /.form-group -->
                                
                        </div>
                        <!-- /.col-md-10 -->
                        
                    </div>
                    <!-- /.row -->                
                    
                    <hr/>
                    
                    <div class="row">
                        <div class="col-md-2">
                            
                            <h3>Featured Image</h3>
                            <p class="help-block">Some helpful text</p>
                                    
                        </div>
                        <!-- /.col-md-2 -->
                                    
                        <div class="col-md-10">
                        
                            <div class="form-group">
                                <label>Primary Image</label>
                                <?php echo \Assets\Admin\Controllers\Assets::instance()->fetchElementImage('featured_image', $flash->old('featured_image.slug'), array('field'=>'featured_image[slug]') ); ?>
                            </div>
                            <!-- /.form-group -->
                            
                        </div>
                        <!-- /.col-md-10 -->
                        
                    </div>
                    <!-- /.row -->
                
                </div>
                <!-- /.tab-pane -->
            
            </div>

        </div>
        
    </div>
</form>

</div>