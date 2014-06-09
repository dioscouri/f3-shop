<div class="row">
	<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
		<h1 class="page-title txt-color-blueDark">
			<i class="fa fa-table fa-fw "></i> 
				<a href="./admin/shop/campaigns">Campaigns</a> 
			<span> > 
				<?php echo $item->title; ?>
			</span>
		</h1>
	</div>
	<div class="col-xs-12 col-sm-5 col-md-5 col-lg-8">
        <ul id="sparks" class="list-actions list-unstyled list-inline">
            <li>
                <a class="btn btn-info" href="./admin/shop/campaign/edit/<?php echo $item->id; ?>">Edit</a>
            </li>        
            <li>
                <a class="btn btn-default" href="./admin/shop/campaigns">Close</a>
            </li>
        </ul>
	</div>
</div>

<hr />

<div class="row">
    <div class="col-md-9">
    
        <div class="panel panel-default">
            <div class="panel-heading">[List of Customer Orders?]</div>
            <div class="panel-body">
                List of X recent orders with a link to complete orders list filtered for user
            </div>            
        </div>
        
        <div class="panel panel-default">
            <div class="panel-heading">[Admin-only notes about customer]</div>
            <div class="panel-body">
                Add notes to customer
            </div>            
        </div>        
        
        <div class="panel panel-default">
            <div class="panel-heading">[Tags]</div>
            <div class="panel-body">
                Add tags to customer
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="panel panel-default">
            <div class="panel-heading">Details</div>
            <div class="panel-body">
                <ul class="list-unstyled">
                    <li><label>Last Modified:</label> <?php echo date( 'Y-m-d', $item->{'metadata.last_modified.time'} ); ?></li>                    
                    <li><label>Created:</label> <?php echo date( 'Y-m-d', $item->{'metadata.created.time'} ); ?></li>
                </ul>
            </div>
        </div>
        
    </div>
</div>

<div class="row">
    <div class="col-md-12">
    
        [display tabbed data from plugin event]
    
    </div>
</div>