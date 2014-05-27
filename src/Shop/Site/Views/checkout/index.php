<?php 
	$settings = \Shop\Models\Settings::fetch();

	if( $settings->enabledIntegration( 'kissmetrics' ) ) { ?>
<?php // track start checkout ?>
<script type="text/javascript">
	_kmq.push(['record', 'Started Purchase']);
</script>
<?php } ?>

<div class="container">
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-7">
            <?php echo $this->renderView('Shop/Site/Views::checkout/shipping_forms.php'); ?>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-5">
            <?php echo $this->renderView('Shop/Site/Views::checkout/cart.php'); ?>
        </div>            
    </div>    
</div>
