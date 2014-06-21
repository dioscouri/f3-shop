<h3 class="">Shipping Methods</h3>
<hr />

<div class="">
	<div class="panel-group" id="accordion">
  <div class="panel panel-default">
    <div class="panel-heading">
      <h4 class="panel-title">
        <a data-toggle="collapse" data-parent="#accordion" href="#ups">
          UPS<span class="badge pull-right"><?php echo $flash->old('shipping.ups.mode');?></span>
        </a>
      </h4>
    </div>
    <div id="ups" class="panel-collapse collapse in">
      <div class="panel-body">
	      <div class="container">
	          <?php echo $this->renderLayout('Shop/Admin/Views::settings/shipping/ups.php'); ?>
	      </div>
     </div>
    </div>
  </div>
  <div class="panel panel-default">
    <div class="panel-heading">
      <h4 class="panel-title">
        <a data-toggle="collapse" data-parent="#accordion" href="#usps">
          USPS<span class="badge pull-right"><?php echo $flash->old('shipping.usps.mode');?></span>
        </a>
      </h4>
    </div>
    <div id="usps" class="panel-collapse collapse">
      <div class="panel-body">
	 <div class="container">
		          <?php echo $this->renderLayout('Shop/Admin/Views::settings/shipping/usps.php'); ?>
		      </div>
		   </div>
    </div>
  </div>
  <div class="panel panel-default">
    <div class="panel-heading">
      <h4 class="panel-title">
        <a data-toggle="collapse" data-parent="#accordion" href="#fedex">
          FEDEX<span class="badge pull-right"><?php echo $flash->old('shipping.fedex.mode');?></span>
        </a>
      </h4>
    </div>
    <div id="fedex" class="panel-collapse collapse">
      <div class="panel-body">
		<div class="container">
		          <?php echo $this->renderLayout('Shop/Admin/Views::settings/shipping/fedex.php'); ?>
		</div>
		      
      </div>
    </div>
  </div>
</div>



</div>
<!-- /.form-group -->

