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
            <div class="panel-heading">[List of Rules]</div>
            <div class="panel-body">
                List of the campaign rules
            </div>            
        </div>
        
        <div class="panel panel-default">
            <div class="panel-heading">[List of Rewards]</div>
            <div class="panel-body">
                Rewards
            </div>            
        </div>        
        
        <div class="panel panel-default">
            <div class="panel-heading">[Expiration actions]</div>
            <div class="panel-body">
                Expiration actions
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
    
        <div class="panel panel-default">
            <div class="panel-heading">[Customers in this campaign]</div>
            <div class="panel-body">
                COUNT + 
                Link to a paginated list
            </div>
        </div>    
    
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

<?php /* ?>
<div class="row">
    <div class="col-md-12">
    
        [display tabbed data from plugin event]
    
    </div>
</div>
*/ ?>