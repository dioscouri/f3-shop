<script type="text/javascript">

	jQuery( function(){
		jQuery('a[data-task="generate"]').on('click', function(){
				jQuery( '#couponsForm' ).prop( 'action', './admin/shop/coupon/<?php echo $item->_id; ?>/codes/generate' );
				jQuery( '#couponsForm' ).submit();
			});
		});
</script>

<div class="row">
	<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
		<h1 class="page-title txt-color-blueDark">
			<i class="fa fa-table fa-fw "></i> 
				Coupon 
			<span> > 
				Codes
			</span>
		</h1>
	</div>
	<div class="col-xs-12 col-sm-5 col-md-5 col-lg-8">
		<div class="pull-right">
			<a class="btn btn-success" href="./admin/shop/coupon/<?php echo $item->_id; ?>/codes/download">Download Codes</a>
			&nbsp;
			<a class="btn btn-danger"  data-task="generate" href="javascript:void(0);">Generate Codes</a>
			&nbsp;
			<a class="btn btn-default" href="./admin/shop/coupon/edit/<?php echo $item->_id; ?>">Back to Coupon</a>
		</div>
	</div>
</div>

<form id="couponsForm" method="post" >

    <div class="no-padding">
        
        <div class="row">
		</div>
		        
        <div class="widget-body-toolbar">    
    
            <div class="row">
            	<div class="col-lg-4 col-mg-4 col-xs-6">
            		<label>Coupon Prefix</label>
            		<input type="text" class="form-control" name="prefix" value="<?php echo $item->{'codes.prefix'}?>" placeholder="Prefix for all coupons" />
            	</div>
            	<div class="col-lg-4 col-mg-4 col-xs-6">
            		<label>Code Length</label>
            		<input type="text" class="form-control" name="length" value="<?php echo $item->{'codes.length'}?>" placeholder="Number of characters in code suffix" />
            	</div>
            	<div class="col-lg-4 col-mg-4 col-xs-6">
            		<label>Number of Coupons</label>
            		<input type="text" class="form-control" name="count" value="50" placeholder="How many coupons you want to generate ..." />
            	</div>
            	<div class="col-lg-4 col-mg-12 col-xs-12">
            		<br />
            		<label>Generated codes</label> <span class="badge"><?php echo count((array) $item->{'codes.list'}); ?></span><br />
            		<label>Already used coupons</label> <span class="badge"><?php echo $item->countUsedCodes(); ?></span>
            	</div>
            </div>
            <div class="row">
                <div class="col-xs-12 col-sm-7 col-md-6 col-md-offset-3 col-lg-6 col-lg-offset-3">
                    <div class="row text-align-right">
                        <div class="col-xs-10 col-sm-10 col-md-10 col-lg-10">
                        <?php if (!empty($paginated->total_pages) && $paginated->total_pages > 1) { ?>
                            <?php echo $paginated->serve(); ?>
                        <?php } ?>
                        </div>
                        <?php if (!empty($codes)) { ?>
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
        
        <div class="table-responsive datatable dt-wrapper dataTables_wrapper">
            
            <table class="table table-striped table-bordered table-hover table-highlight table-checkable">
        	<thead>
        		<tr>
        			<th>Code</th>
        			<th class="col-md-1"></th>
        		</tr>
        	</thead>
        	<tbody>    
        
            <?php if (!empty($codes )) { ?>
            
            <?php foreach($codes as $code) { ?>
                <tr>
                    <td>
                    	<?php
                    		echo $code['code'];
                    		
                    		if( $code['used'] ) { ?>
                    		<span class="label label-danger"> Used </span>
                    		<?php } else { ?>
                    		<span class="label label-success"> Not used </span>                    		
							<?php } ?>
                    	
                    </td>
                                    
                    <td class="text-center col-lg-2 col-xs-4">
                        <a class="btn btn-xs btn-danger" data-bootbox="confirm" href="./admin/shop/coupon/<?php echo $item->_id; ?>/code/<?php echo $code['code']; ?>/delete">
                            <i class="fa fa-times"></i>
                        </a>
                    </td>
                </tr>
            <?php } ?>
            
            <?php } else { ?>
                <tr>
                <td colspan="100">
                    <div class="">No codes found.</div>
                </td>
                </tr>
            <?php } ?>
        
            </tbody>
            </table>
            
        </div>
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

</form>

