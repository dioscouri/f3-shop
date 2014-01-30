<div class="row">
    <div class="col-md-2">
        
        <h3>Attributes</h3>
        <p class="help-block">Some helpful text</p>
                
        <div class="form-group">
            <a class="btn btn-warning" id="add-attribute">Add Attribute</a>
        </div>
                
    </div>
    <!-- /.col-md-2 -->
                
    <div class="col-md-10">
        <?php foreach ((array) $flash->old('attributes') as $key=>$attribute) { ?>
        <fieldset class="template clearfix well well-sm">
            <div class="form-group clearfix">
                <label>Existing Attribute</label>
                <a class="remove-price btn btn-xs btn-danger pull-right" onclick="TiendaRemoveAttribute(this);" href="javascript:void(0);">
                    <i class="fa fa-times"></i>
                </a>
            </div>
            
            <div class="form-group clearfix">
                <div class="col-md-12">
                    <input type="text" name="attributes[<?php echo $key; ?>][title]" class="form-control input-sm" value="<?php echo $flash->old('attributes.'.$key.'.title'); ?>" />
                </div>
            </div>            
        </fieldset>        
        <?php } ?>
        
        <div id="new-attributes" class="form-group"></div>
        
        <template type="text/template" id="add-attribute-template">
            <fieldset class="template well well-sm clearfix">
                <div class="clearfix">
                    <a class="remove-attribute btn btn-xs btn-danger pull-right" onclick="TiendaRemoveAttribute(this);" href="javascript:void(0);">
                        <i class="fa fa-times"></i>
                    </a>                        
                </div>
                <div class="form-group clearfix">
                    <div class="col-md-12">
                        <label>New Attribute Name</label>
                        <input type="text" name="attributes[{id}][title]" class="form-control input-sm" placeholder="Attribute Name" />
                    </div>
                </div>
            </fieldset>
        </template>
        
        <script>
        jQuery(document).ready(function(){
            window.new_attributes = <?php echo count( $flash->old('attributes') ); ?>;
            jQuery('#add-attribute').click(function(){
                var container = jQuery('#new-attributes');
                var template = jQuery('#add-attribute-template').html();
                template = template.replace( new RegExp("{id}", 'g'), window.new_attributes);
                container.append(template);
                window.new_attributes = window.new_attributes + 1;
            });
    
            TiendaRemoveAttribute = function(el) {
                jQuery(el).parents('.template').remove();                            
            }
    
        });
        </script>
                
    </div>
    <!-- /.col-md-10 -->
    
</div>
<!-- /.row -->

<hr />

<?php echo $this->renderLayout('Tienda/Admin/Views::products/fields_attributes_variants.php'); ?>