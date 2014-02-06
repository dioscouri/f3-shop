<div class="col-md-8">

    <div class="portlet">

        <div class="portlet-header">

            <h3>Primary Image</h3>

        </div>
        <!-- /.portlet-header -->

        <div class="portlet-content">

            <?php echo \Assets\Admin\Controllers\Assets::instance()->fetchElementImage('featured_image', $flash->old('details.featured_image.slug'), array('field'=>'details[featured_image][slug]') ); ?>
        
        </div>
        <!-- /.portlet-content -->

    </div>
    <!-- /.portlet -->    


    <?php foreach ((array) $flash->old('relatedimages') as $key=>$relatedimage) { ?>
        <fieldset class="template well clearfix">
            <a class="remove-relatedimage btn btn-xs btn-danger pull-right" onclick="ShopRemoveRelatedImage(this);" href="javascript:void(0);">
                <i class="fa fa-times"></i>
            </a>                        
            <label>Image</label>
            <div class="form-group clearfix">
                <div class="col-md-12">
                    <?php echo \Assets\Admin\Controllers\Assets::instance()->fetchElementImage('relatedimage_' . $key, $flash->old('relatedimages.'.$key.'.image'), array('field'=>'relatedimages['.$key.'][image]') ); ?>
                </div>
            </div>
        </fieldset>                        
    <?php } ?>
</div>
<!-- /.col-md-8 -->

<div class="col-md-4 col-sidebar-right">

    <template type="text/template" id="add-relatedimage-template">
        <fieldset class="template well clearfix">
            <a class="remove-relatedimage btn btn-xs btn-danger pull-right" onclick="ShopRemoveRelatedImage(this);" href="javascript:void(0);">
                <i class="fa fa-times"></i>
            </a>                        
            <label>New Image</label>
            <div class="form-group clearfix">
                <div class="col-md-12">
                    <?php echo \Assets\Admin\Controllers\Assets::instance()->fetchElementImage('image_{id}', null, array('field'=>'relatedimages[{id}][image]') ); ?>
                </div>
            </div>
        </fieldset>
    </template>
    
    <div class="form-group">
        <a class="btn btn-warning" id="add-relatedimage">Add New Image</a>
    </div>
    
    <div id="new-relatedimages" class="form-group"></div>
    
    <script>
    jQuery(document).ready(function(){
        window.new_relatedimages = <?php echo count( $flash->old('relatedimages') ); ?>;
        jQuery('#add-relatedimage').click(function(){
            var container = jQuery('#new-relatedimages');
            var template = jQuery('#add-relatedimage-template').html();
            template = template.replace( new RegExp("{id}", 'g'), window.new_relatedimages);
            container.append(template);
            window.new_relatedimages = window.new_relatedimages + 1;
            Dsc.setupColorbox();                            
        });

        ShopRemoveRelatedImage = function(el) {
            jQuery(el).parents('.template').remove();                            
        }

    });
    </script>

</div>
<!-- /.col-md-4 -->