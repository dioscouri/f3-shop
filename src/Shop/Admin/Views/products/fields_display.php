<div class="row">
    <div class="col-md-2">
        
        <h3>Display</h3>
        <p class="help-block">Some helpful text</p>
                
    </div>
    <!-- /.col-md-2 -->
                
    <div class="col-md-10">
    
        <div class="portlet">

            <div class="portlet-header">

                <h3>Stickers</h3>
                <p class="help-block">Stickers display on product listing pages (collections/category landing pages) and can be used to indicate an item is "on sale", "editor's pick", or "limited availability", etc.</p>

            </div>
            <!-- /.portlet-header -->

            <div class="portlet-content">
            
                <div class="input-group">
                    <input name="display[stickers]" data-maximum="1" data-tags='<?php echo json_encode( (array) \Shop\Models\Products::distinctStickers() ); ?>' value="<?php echo implode(",", (array) $flash->old('display.stickers') ); ?>" type="text" class="form-control ui-select2-tags" /> 
                </div>
                <!-- /.form-group -->

            </div>
            <!-- /.portlet-content -->

        </div>
        <!-- /.portlet -->
        
    </div>
    <!-- /.col-md-10 -->
    
</div>
<!-- /.row -->

<hr />

<?php echo $this->renderLayout('Shop/Admin/Views::products/fields_display_related_products.php'); ?>
