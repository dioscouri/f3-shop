<div class="row">
    <div class="col-md-2">
        
        <h3>Categories</h3>
        
                
    </div>
    <!-- /.col-md-2 -->
                
    <div class="col-md-10">

        <div class="portlet">

            <div class="portlet-header">

                <h3>Categories</h3>

            </div>
            <!-- /.portlet-header -->

            <div class="">
                <div id="categories" class="list-group">
                    <input type="hidden" name="category_ids[]" />
                    
                    <div id="categories-checkboxes">
                    <?php echo $this->renderLayout('Shop/Admin/Views::categories/checkboxes.php'); ?>
                    </div>
            
                    <div class="list-group-item">
                        <script>
                        Dsc.refreshCategories = function(r) {

                            var form_data = new Array();
                        	jQuery.merge( form_data, jQuery('#categories-checkboxes').find(':input').serializeArray() );
                        	jQuery.merge( form_data, [{ name: "category_ids[]", value: r.result._id['$id'] }] );

                            var request = jQuery.ajax({
                                type: 'post', 
                                url: './admin/shop/categories/checkboxes',
                                data: form_data
                            }).done(function(data){
                                var lr = jQuery.parseJSON( JSON.stringify(data), false);
                                if (lr.result) {
                                    jQuery('#categories-checkboxes').html(lr.result);
                                    App.initICheck();
                                }
                            });
                        }
                        </script>
                                                
                        <div data-toggle="collapse" data-target="#addCategoryForm" class="btn btn-link">
                            Add New Category
                        </div>
                        <div id="addCategoryForm" class="collapse">
                            <div class="panel-body">
                                
                                <div id="quick-form" action="./admin/shop/category/create" data-callback="Dsc.refreshCategories" data-message_container="quick-form-response-container">
                                
                                <div id="quick-form-response-container"></div>
                                
                                <div class="form-group">
                                    <input type="text" name="new_category_title" placeholder="Title" class="form-control" />
                                </div>
                                <!-- /.form-group -->
                                
                                <div id="parents" class="form-group">
                                    <?php echo \Dsc\Request::internal( "\Shop\Admin\Controllers\Categories->selectList" ); ?>                    
                                </div>
                                <!-- /.form-group -->        
                
                                <hr />
                
                                <div class="form-actions">
                
                                    <div>
                                        <button type="button" class="btn btn-primary dsc-ajax-submit" data-target="quick-form">Create</button>
                                    </div>
                
                                </div>
                                <!-- /.form-group -->
                                </div>
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.portlet-content -->

        </div>
        <!-- /.portlet -->     
    
    </div>
    <!-- /.col-md-10 -->
    
</div>
<!-- /.row -->