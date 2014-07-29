<div class="row">
	<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
		<h1 class="page-title txt-color-blueDark">
			<i class="fa fa-table fa-fw "></i> 
            Enabled Payment Methods 
		</h1>
	</div>
	<div class="col-xs-12 col-sm-5 col-md-5 col-lg-8">
        <ul id="sparks" class="list-actions list-unstyled list-inline">
            <li>
                <a class="btn btn-success" href="./admin/shop/payment-method/select">Add a Payment Method</a>
            </li>
        </ul>            	
	</div>
</div>

<?php if (empty($items)) { ?><p>None</p><?php } ?>

<?php $n=0; $count = count($items); ?>
<?php foreach ($items as $position=>$item) { ?>

    <?php if ($n == 0 || ($n % 4 == 0)) { ?><div class="row"><?php } ?>
        
    <div class="col-xs-12 col-sm-6 col-md-4 text-center">
        
        <div class="panel panel-default">
            <div class="panel-body">
                <a class="btn btn-link" href="./admin/shop/payment-method/edit/<?php echo $item->identifier; ?>">
                <h4>
                    <?php if ($item->icon) { ?>
                    <i class="<?php echo $item->icon; ?>"></i>
                    <?php } ?>
                    <div class="">
                        <?php echo $item->title; ?>
                    </div>
                </h4>            
                </a>
            </div>
        </div>
    </div>
         
    <?php $n++; if (($n % 4 == 0) || $n==$count) { ?></div> <br/><?php } ?>         

<?php } ?>