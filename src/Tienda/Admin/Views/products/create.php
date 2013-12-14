<?php // echo \Dsc\Debug::dump( $flash->get('old'), false ); ?>

<script src="./ckeditor/ckeditor.js"></script>
<script>
jQuery(document).ready(function(){
    CKEDITOR.replaceAll( 'wysiwyg' );    
});

</script>

<form id="detail-form" action="./admin/post" class="form" method="post">
    <div class="row">
        <div class="col-md-9">
            <div class="form-group">
                <input type="text" name="metadata[title]" placeholder="Title" value="<?php echo $flash->old('metadata.title'); ?>" class="form-control" />
            </div>
            <!-- /.form-group -->
            
            <div class="form-group">
                <textarea name="details[copy]" class="form-control wysiwyg"><?php echo $flash->old('details.copy'); ?></textarea>
            </div>
            <!-- /.form-group -->        
        </div>
        <div class="col-md-3">

            <div class="portlet">

                <div class="portlet-header">

                    <h3>Publication</h3>

                </div>
                <!-- /.portlet-header -->

                <div class="portlet-content">

                    <div class="form-group">
                        <label>Status:</label>

                        <select name="publication[status]" class="form-control">
                            <option value="draft" <?php if ($flash->old('publication.status') == 'draft') { echo "selected='selected'"; } ?>>Draft</option>
                            <option value="pending" <?php if ($flash->old('publication.status') == 'pending') { echo "selected='selected'"; } ?>>Pending Review</option>
                            <option value="published" <?php if ($flash->old('publication.status') == 'published') { echo "selected='selected'"; } ?>>Published</option>
                        </select>
                    
                    </div>
                    <div class="form-group">
                        <label>Start:</label>
                        <div class="row">
                            <div class="col-md-6">
                                <input name="publication[start_date]" value="<?php echo $flash->old('publication.start_date', date('Y-m-d') ); ?>" class="ui-datepicker form-control" type="text" data-date-format="yyyy-mm-dd" data-date-today-highlight="true" data-date-today-btn="true">
                            </div>
                            <div class="input-group col-md-6">
                                <input name="publication[start_time]" value="<?php echo $flash->old('publication.start_time' ); ?>" type="text" class="ui-timepicker form-control" data-show-meridian="false" data-show-inputs="false">
                                <span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Finish:</label>
                        <div class="row">
                            <div class="col-md-6">
                                <input name="publication[end_date]" value="<?php echo $flash->old('publication.end_date' ); ?>" class="ui-datepicker form-control" type="text" data-date-format="yyyy-mm-dd" data-date-today-highlight="true" data-date-today-btn="true">
                            </div>
                            <div class="input-group col-md-6">
                                <input name="publication[end_time]" value="<?php echo $flash->old('publication.end_time' ); ?>" type="text" class="ui-timepicker form-control" data-default-time="false" data-show-meridian="false" data-show-inputs="false">
                                <span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
                            </div>
                        </div>
                        <span class="help-text">Leave these blank to never unpublish.</span>
                    </div>
                                    
                    <hr/>
                    
                    <div class="form-actions">

                        <div>
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
                                        <a onclick="document.getElementById('primarySubmit').value='save_close'; document.getElementById('detail-form').submit();" href="javascript:void(0);">Save & Close</a>
                                    </li>
                                </ul>
                            </div>                          
                            &nbsp;
                            <a class="btn btn-default" href="./admin/posts">Cancel</a>
                        </div>

                    </div>
                    <!-- /.form-group -->

                </div>
                <!-- /.portlet-content -->

            </div>
            <!-- /.portlet -->
            
            <div class="portlet">

                <div class="portlet-header">

                    <h3>Categories</h3>

                </div>
                <!-- /.portlet-header -->

                <div class="">
                    <div id="categories" class="list-group">
                        <div id="categories-checkboxes">
                        <?php echo $this->renderLayout('categories/checkboxes.php'); ?>
                        </div>
                
                        <div class="list-group-item">
                            <script>
                            Dsc.refreshCategories = function(r) {

                                var form_data = new Array();
                            	jQuery.merge( form_data, jQuery('#categories-checkboxes').find(':input').serializeArray() );
                            	jQuery.merge( form_data, [{ name: "category_ids[]", value: r.result._id['$id'] }] );

                                var request = jQuery.ajax({
                                    type: 'post', 
                                    url: './admin/categories/checkboxes',
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
                                    
                                    <div id="quick-form" action="./admin/category" data-callback="Dsc.refreshCategories" data-message_container="quick-form-response-container">
                                    
                                    <div id="quick-form-response-container"></div>
                                    
                                    <div class="form-group">
                                        <input type="text" name="new_category_title" placeholder="Title" class="form-control" />
                                    </div>
                                    <!-- /.form-group -->
                                    
                                    <div id="parents" class="form-group">
                                        <?php echo $this->renderLayout('categories/list_parents.php'); ?>                    
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
            
            <div class="portlet">

                <div class="portlet-header">

                    <h3>Tags</h3>

                </div>
                <!-- /.portlet-header -->

                <div class="portlet-content">
                
                    <div class="input-group">
                        <input name="metadata[tags]" data-tags='<?php echo json_encode( $all_tags ); ?>' value="<?php echo implode(",", (array) $flash->old('metadata.tags') ); ?>" type="text" name="tags" class="form-control ui-select2-tags" /> 
                    </div>
                    <!-- /.form-group -->

                </div>
                <!-- /.portlet-content -->

            </div>
            <!-- /.portlet -->

            <div class="portlet">

                <div class="portlet-header">

                    <h3>Featured Image</h3>

                </div>
                <!-- /.portlet-header -->

                <div class="portlet-content">
                
                    <a href="javascript:;">Set Featured Image</a>
                
                </div>
                <!-- /.portlet-content -->

            </div>
            <!-- /.portlet -->
 
        </div>
    </div>
</form>