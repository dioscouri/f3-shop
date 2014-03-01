<script>
Dsc.refreshParents = function() {
    var request = jQuery.ajax({
        type: 'get', 
        url: './admin/shop/manufacturers/all'
    }).done(function(data){
        var lr = jQuery.parseJSON( JSON.stringify(data), false);
        if (lr.result) {
            jQuery('#parents').html(lr.result);
        }
    });
}
</script>

<div class="row">
	<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
		<h1 class="page-title txt-color-blueDark">
			<i class="fa fa-table fa-fw "></i> 
				Manufacturers 
			<span> > 
				List
			</span>
		</h1>
	</div>
	<div class="col-xs-12 col-sm-5 col-md-5 col-lg-8">
	   <?php /* ?>
        <ul id="sparks" class="list-actions list-unstyled list-inline">
            <li>
                
            </li>
        </ul>   
        */ ?>         	
	</div>
</div>

<div class="row">
    <div class="col-md-9">
        <div class="well">
            <form id="manufacturers" class="searchForm" action="./admin/shop/manufacturers" method="post">
                <?php echo $this->renderLayout('Shop/Admin/Views::manufacturers/list_datatable.php'); ?>
            </form>
        </div>
    </div>
    <div class="col-md-3">
    
    	<?php echo \Dsc\Request::internal( "\Shop\Admin\Controllers\Manufacturer->quickadd" ); ?>
		
    </div>
</div>