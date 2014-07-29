<div class="row">
	<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
		<h1 class="page-title txt-color-blueDark">
			<i class="fa fa-table fa-fw "></i> 
            Add a Payment Method 
		</h1>
	</div>
	<div class="col-xs-12 col-sm-5 col-md-5 col-lg-8">
        <ul id="sparks" class="list-actions list-unstyled list-inline">
            <li>
                <a class="btn btn-danger" href="./admin/shop/payment-methods">Cancel</a>
            </li>
        </ul>            	
	</div>
</div>

<h3>Select a Payment Method to Add</h3>
<h4>These methods are currently available but disabled:</h4>

<div class="list-group">
<?php if (empty($items)) { ?><div class="list-group-item">None</div><?php } ?>

<?php foreach ($items as $item) { ?>
<div class="list-group-item">
    <a class="btn btn-link" href="./admin/shop/payment-method/edit/<?php echo $item->identifier; ?>">
        <h4>
        <?php echo $item->title; ?>
        </h4>
    </a>
</div>
<?php } ?>
</div>