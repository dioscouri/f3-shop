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
                                <a onclick="document.getElementById('primarySubmit').value='save_close'; document.getElementById('detail-form').submit();" href="javascript:void(0);">Save & Close</a>
                            </li>
                        </ul>
                    </div>
                        
                    &nbsp;
                    <a class="btn btn-default" href="./admin/shop/orders/giftcards">Cancel</a>
                </div>

            </div>
            <!-- /.form-group -->
            
            <hr />
            
            <ul class="nav nav-tabs">
                <li class="active">
                    <a href="#tab-basics" data-toggle="tab"> Basics </a>
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
                                    
                        </div>
                        <!-- /.col-md-2 -->
                                    
                        <div class="col-md-10">
                            
                            <div class="form-group">
                                <label>Initial Value</label>
                                <input type="text" name="initial_value" placeholder="e.g. 10.00" value="<?php echo $flash->old('initial_value'); ?>" class="form-control required" required data-required='required' />
                            </div>
                            <!-- /.form-group -->
                            
                        </div>
                        <!-- /.col-md-10 -->
                        
                    </div>
                    <!-- /.row -->
                    
                    <hr/>
                    
                    <div class="row">
                        <div class="col-md-2">
                            
                            <h3>Customer</h3>
                            <p class="help-block">Optional.  If you select a customer, they will be emailed the code after you issue it.</p>
                                    
                        </div>
                        <!-- /.col-md-2 -->
                                    
                        <div class="col-md-10">
                        
                            <div class="form-group">
                                <label>Customer</label>
                                <input id="customers" type="text" name="__customers" placeholder="Search..." class="form-control" />
                            </div>
                            <!-- /.form-group -->
                            
                        </div>
                        <!-- /.col-md-10 -->
                        
                    </div>
                    <!-- /.row -->
                    
                    <hr/>
                    
                    <div class="row">
                        <div class="col-md-2">
                            
                            <h3>Email Addresses</h3>
                            <p class="help-block">Optional.  Directly provide recipient email addresses.  Use when the email address is not associated with a customer.</p>
                                    
                        </div>
                        <!-- /.col-md-2 -->
                                    
                        <div class="col-md-10">
                        
                            <div class="form-group">
                                <label>Email Addresses</label>
                                <input id="emails" type="text" name="__emails" placeholder="Email address..." class="form-control ui-select2-tags" data-tags="[]" />
                            </div>
                            <!-- /.form-group -->
                            
                        </div>
                        <!-- /.col-md-10 -->
                        
                    </div>
                    <!-- /.row -->
                
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

<script>
jQuery(document).ready(function() {
    
    jQuery("#customers").select2({
        allowClear: true, 
        placeholder: "Search...",
        multiple: true,
        minimumInputLength: 3,
        ajax: {
            url: "./admin/shop/customers/forSelection?value=email",
            dataType: 'json',
            data: function (term, page) {
                return {
                    q: term
                };
            },
            results: function (data, page) {
                return {results: data.results};
            }
        }
    });

});
</script>