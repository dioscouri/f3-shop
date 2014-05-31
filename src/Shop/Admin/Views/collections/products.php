<form id="sort-products" method="post" action="./admin/shop/collection/<?php echo $collection->id; ?>/products">

<div class="row">
	<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
		<h1 class="page-title txt-color-blueDark">
			<i class="fa fa-table fa-fw "></i> 
				"<?php echo $collection->title; ?>" Collection  
			<span> > 
				Products
			</span>
		</h1>
	</div>
	<div class="col-xs-12 col-sm-5 col-md-5 col-lg-8">
        <ul id="sparks" class="list-actions list-unstyled list-inline">
            <li>
                <a class="btn btn-primary" onclick="document.getElementById('sort-products').action='./admin/shop/collection/<?php echo $collection->id; ?>/products/order'; document.getElementById('sort-products').submit();" href="javascript:void(0);">Save Product Ordering</a>
            </li>        
            <li>
                <a class="btn btn-default" href="./admin/shop/collection/edit/<?php echo $collection->id; ?>">Return to Collection</a>
            </li>        
        </ul>            	
	</div>
</div>

<?php if (empty($paginated->items)) { ?>
    <p>No products match this collection's filters</p>
<?php } else { ?>

<?php $this->session->set('collections.products.current_page', $paginated->getCurrent()); ?>
<script>
window.ordering_start = <?php echo $start = $paginated->prev_page * $paginated->items_per_page; ?>;
jQuery(document).ready(function() {
	jQuery('[data-toggle="tooltip"]').tooltip();
	
	var group = jQuery('.sortable').sortable({
    	onDrop: function (item, container, _super) {
        	// TODO Display "you have unsaved work!"
    	    var data = group.sortable("serialize").get();
    	    jQuery.each(data[0], function(index, value){
        	    var new_ordering = window.ordering_start + index;
        	    jQuery('#ordering-'+value.id).val(new_ordering);
    	    	
        	});
    	    console.log(data[0]);
    	    _super(item, container)
    	}
    });
});
</script>

    <div class="no-padding">
        
        <div class="widget-body-toolbar">    
    
            <div class="row">
                <div class="col-xs-12 col-sm-5 col-md-3 col-lg-3">
                </div>    
                <div class="col-xs-12 col-sm-7 col-md-6 col-md-offset-3 col-lg-6 col-lg-offset-3">
                    <div class="row text-align-right">
                        <div class="col-xs-10 col-sm-10 col-md-10 col-lg-10">
                            <?php if (!empty($paginated->total_pages) && $paginated->total_pages > 1) { ?>
                                <?php echo $paginated->serve(); ?>
                            <?php } ?>
                        </div>
                        <?php if (!empty($paginated->items)) { ?>
                        <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
                            <span class="pagination">
                            <?php echo $paginated->getLimitBox( $state->get('list.limit') ); ?>
                            </span>
                        </div>
                        <?php } ?>
                    </div>            
                </div>
            </div>
        
        </div>
        <!-- /.widget-body-toolbar -->

            <?php if (!empty($paginated->items)) { ?>
                    <div class="list-group-item">
                        <div class="row">
                            <div class="col-sm-11">
                                <div class="alert alert-info">
                                Drag & Drop using the <i class="fa fa-list"></i> handle ro reorder the products.  <b>Click save</b> to commit your changes.
                                </div>
                            </div>
                            <div class="col-sm-1">
                                <b>
                                New Position
                                </b>
                            </div>
                        </div>
                    </div>
            
                <ul class="list-group sortable">
                <?php foreach($paginated->items as $key=>$item) { 
                    $position = $start + $key;
                    ?>
                    <li class="list-group-item" data-id="<?php echo $item->id; ?>">
                        <div class="row">
                            <div class="col-sm-1">
                                <h5>
                                    <i class="fa fa-list"></i>
                                </h5>
                            </div>
                            
                            <div class="col-sm-1">
                                <?php if ($item->{'featured_image.slug'}) { ?>
                                    <div class="text-center">
                                    	<img class="img-responsive" src="./asset/thumb/<?php echo $item->{'featured_image.slug'}; ?>" />
                				    </div>
                                <?php } ?>
                            </div>
                            <div class="col-sm-9">
                                <h5>
                                    <?php echo $item->{'title'}; ?>
                                </h5>
                                <p class="help-block">Current position: <?php echo $item->exists('collections.' . $collection->id . '.ordering') ? $item->{'collections.' . $collection->id . '.ordering'} : 'undefined'; ?></p>
                            </div>
                            <div class="col-sm-1">
                                <input id="ordering-<?php echo $item->id; ?>" name="ordering[<?php echo $item->id; ?>]" value="<?php echo $position; ?>" class="form-control" data-toggle="tooltip" data-placement="top" title="Original position: <?php echo $position; ?>" />
                            </div>
                            <?php /* ?>
                            <div class="col-sm-1">
                                <a class="btn btn-xs btn-tertiary" href="./admin/shop/collection/<?php echo $collection->id; ?>/products/moveup/<?php echo $item->id; ?>">
                                    <i class="fa fa-chevron-up"></i>
                                </a>
                                &nbsp;
                                <a class="btn btn-xs btn-tertiary" href="./admin/shop/collection/<?php echo $collection->id; ?>/products/movedown/<?php echo $item->id; ?>">
                                    <i class="fa fa-chevron-down"></i>
                                </a>                            
                            </div>   
                            */ ?>                     
                        </div>
                    </li>
                <?php } ?>
                </ul>
            
            <?php } else { ?>
                <div class="">No items found.</div>
            <?php } ?>
        
        
        
        <div class="dt-row dt-bottom-row">
            <div class="row">
                <div class="col-sm-10">
                    <?php if (!empty($paginated->total_pages) && $paginated->total_pages > 1) { ?>
                        <?php echo $paginated->serve(); ?>
                    <?php } ?>
                </div>
                <div class="col-sm-2">
                    <div class="datatable-results-count pull-right">
                        <span class="pagination">
                            <?php echo (!empty($paginated->total_pages)) ? $paginated->getResultsCounter() : null; ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    
    </div>
    <!-- /.no-padding -->

<?php } ?>

</form>