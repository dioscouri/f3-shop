<script src="./ckeditor/ckeditor.js"></script>
<script>
jQuery(document).ready(function(){
    CKEDITOR.replaceAll( 'wysiwyg' );    
});
</script>

<div class="well">

<form id="settings-form" role="form" method="post" class="form-horizontal clearfix">

    <div class="clearfix">
        <button type="submit" class="btn btn-primary pull-right">Save Changes</button>
    </div>
    
    <hr/>

    <div class="row">
        <div class="col-md-3 col-sm-4">
            <ul class="nav nav-pills nav-stacked">
                <li class="active">
                    <a href="#tab-general" data-toggle="tab"> General Settings </a>
                </li>            
                <li>
                    <a href="#tab-stripe" data-toggle="tab"> Stripe </a>
                </li>
                <li>
                    <a href="#tab-paypal" data-toggle="tab"> PayPal </a>
                </li>
                <li>
                    <a href="#tab-authorizenet" data-toggle="tab">Authorize.Net</a>
                </li>
                <li>
                    <a href="#tab-2checkout" data-toggle="tab"> 2Checkout </a>
                </li>                
                <li>
                    <a href="#tab-coinbase" data-toggle="tab"> Coinbase </a>
                </li>   
                                         
                <?php if (!empty($this->event)) { foreach ((array) $this->event->getArgument('tabs') as $key => $title ) { ?>
                <li>
                    <a href="#tab-<?php echo $key; ?>" data-toggle="tab"> <?php echo $title; ?> </a>
                </li>
                <?php } } ?>                
            </ul>
        </div>

        <div class="col-md-9 col-sm-8">

            <div class="tab-content stacked-content">
            
                <div class="tab-pane fade in active" id="tab-general">
                
                    <?php echo $this->renderLayout('Shop/Admin/Views::settings/payments/general.php'); ?>

                </div>
            
                <div class="tab-pane fade in" id="tab-stripe">
                
                    <?php echo $this->renderLayout('Shop/Admin/Views::settings/payments/stripe.php'); ?>

                </div>
                
                <div class="tab-pane fade in" id="tab-paypal">
                
                    <?php echo $this->renderLayout('Shop/Admin/Views::settings/payments/paypal.php'); ?>

                </div>
                
                <div class="tab-pane fade in" id="tab-authorizenet">
                
                    <?php echo $this->renderLayout('Shop/Admin/Views::settings/payments/authorizenet.php'); ?>

                </div>
                
                <div class="tab-pane fade in" id="tab-2checkout">
                
                    <?php echo $this->renderLayout('Shop/Admin/Views::settings/payments/2checkout.php'); ?>

                </div>
                <div class="tab-pane fade in" id="tab-coinbase">
                
                    <?php echo $this->renderLayout('Shop/Admin/Views::settings/payments/coinbase.php'); ?>

                </div>
                
               
                <?php if (!empty($this->event)) { foreach ((array) $this->event->getArgument('content') as $key => $content ) { ?>
                <div class="tab-pane fade in" id="tab-<?php echo $key; ?>">
                    <?php echo $content; ?>
                </div>
                <?php } } ?>

            </div>

        </div>
    </div>

</form>

</div>
