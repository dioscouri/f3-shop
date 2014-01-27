<script src="./ckeditor/ckeditor.js"></script>
<script>
jQuery(document).ready(function(){
    CKEDITOR.replaceAll( 'wysiwyg' );    
});
</script>

<form id="detail-form" action="./admin/tienda/product/update/<?php $flash->old('id') ?>" class="form" method="post">
    <div class="row">
    
        <div class="col-md-12">
        
            <div class="form-actions clearfix">

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
                                <a onclick="document.getElementById('primarySubmit').value='save_close'; document.getElementById('detail-form').submit();" href="javascript:void(0);">Save & Close</a>
                            </li>
                        </ul>
                    </div>                          
                    &nbsp;
                    <a class="btn btn-default" href="./admin/tienda/products">Cancel</a>
                </div>

            </div>
            <!-- /.form-actions -->        
            
            <hr />    
    
            <ul class="nav nav-tabs">
                <li class="active">
                    <a href="#tab-basics" data-toggle="tab"> Basics </a>
                </li>
                <li>
                    <a href="#tab-pricinginventory" data-toggle="tab"> Pricing & Inventory </a>
                </li>                
                <li>
                    <a href="#tab-images" data-toggle="tab"> Images </a>
                </li>                
            </ul>
            
            <div class="tab-content">

                <div class="tab-pane active" id="tab-basics">
                
                    <?php echo $this->renderLayout('Tienda/Admin/Views::products/fields_basics.php'); ?>
                
                </div>
                <!-- /.tab-pane -->
                
                <div class="tab-pane" id="tab-pricinginventory">

                    <?php echo $this->renderLayout('Tienda/Admin/Views::products/fields_pricinginventory.php'); ?>
                                        
                </div>
                <!-- /.tab-pane -->
                
                <div class="tab-pane" id="tab-images">

                    <?php echo $this->renderLayout('Tienda/Admin/Views::products/fields_images.php'); ?>
                                        
                </div>
                <!-- /.tab-pane -->
                
            </div>
    

    </div>
</form>