<div class="row">
	<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
		<h1 class="page-title txt-color-blueDark">
			<i class="fa fa-table fa-fw "></i> 
				Products 
			<span> > 
				Gift Cards
			</span>
		</h1>
	</div>
	<div class="col-xs-12 col-sm-5 col-md-5 col-lg-8">
        <ul id="sparks" class="list-actions list-unstyled list-inline">
            <li>
                <a class="btn btn-default" href="./admin/shop/giftcard/create">Add New</a>
            </li>
        </ul>            	
	</div>
</div>

<?php echo $this->renderView('Shop/Admin/Views::giftcards/list_datatable.php'); ?>
