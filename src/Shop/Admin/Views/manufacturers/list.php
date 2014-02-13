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
    <div class="col-md-9">
        <form id="manufacturers" class="searchForm" action="./admin/shop/manufacturers" method="post">
        
            <?php echo $this->renderLayout('Shop/Admin/Views::manufacturers/list_datatable.php'); ?>
        
        </form>
    </div>
    <div class="col-md-3">
    
    	<?php echo \Dsc\Request::internal( "\Shop\Admin\Controllers\Manufacturer->quickadd" ); ?>
		
    </div>
</div>