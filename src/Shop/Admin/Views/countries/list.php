<div class="row">
	<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
		<h1 class="page-title txt-color-blueDark">
			<i class="fa fa-table fa-fw "></i> 
				Countries 
			<span> > 
				List
			</span>
		</h1>
	</div>
	<div class="col-xs-12 col-sm-5 col-md-5 col-lg-8">
        <ul id="sparks" class="list-actions list-unstyled list-inline">
            <li>
                <a class="btn btn-default" href="./admin/shop/country/create">Add New</a>
            </li>
        </ul>
	</div>
</div>

<form class="searchForm" method="post" action="./admin/shop/countries">

    <div class="no-padding">
        
        <div class="row">
           <div class="col-xs-12 col-sm-5 col-md-5 col-lg-8">

                <ul class="list-filters list-unstyled list-inline">
                    <li>
                        <select id="enabled_filter" name="filter[enabled]" class="form-control" onchange="this.form.submit();">
                            <option value="">All Countries</option>
                            <option value="1" <?php if ($state->get('filter.enabled') == '1') { echo "selected='selected'"; } ?>>Enabled only</option>
                            <option value="0" <?php if ($state->get('filter.enabled') == '0') { echo "selected='selected'"; } ?>>Disabled only</option>
                        </select>
                    </li>                
				</ul>    

            </div>
            <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
                <div class="form-group">
                    <div class="input-group">
                        <input class="form-control" type="text" name="filter[keyword]" placeholder="Search..." maxlength="200" value="<?php echo $state->get('filter.keyword'); ?>"> 
                        <span class="input-group-btn">
                            <input class="btn btn-primary" type="submit" onclick="this.form.submit();" value="Search" />
                            <button class="btn btn-danger" type="button" onclick="Dsc.resetFormFilters(this.form);">Reset</button>
                        </span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-xs-12 col-sm-6">
                <ul class="list-filters list-unstyled list-inline">
                
                </ul>            
            </div>
            
            <div class="col-xs-12 col-sm-6">
                <div class="text-align-right">
                <ul class="list-filters list-unstyled list-inline">
                    <li>
                        <?php if (!empty($paginated->items)) { ?>
                        <?php echo $paginated->getLimitBox( $state->get('list.limit') ); ?>
                        <?php } ?>
                    </li>                
                </ul>    
                </div>
            </div>
        </div>
        
        <div class="widget-body-toolbar">    
    
            <div class="row">
                <div class="col-xs-12 col-sm-6 col-lg-3">
                    <span class="pagination">
                    <div class="input-group">
                        <select id="bulk-actions" name="bulk_action" class="form-control">
                            <option value="null">-Bulk Actions-</option>
                            <option value="delete" data-action="./admin/shop/countries/delete">Delete</option>
                        </select>
                        <span class="input-group-btn">
                            <button class="btn btn-default bulk-actions" type="button" data-target="bulk-actions">Apply</button>
                        </span>
                    </div>
                    </span>
                </div>    
                <div class="col-xs-12 col-sm-6 col-lg-6 col-lg-offset-3">
                    <div class="text-align-right">
                        <?php if (!empty($paginated->total_pages) && $paginated->total_pages > 1) { ?>
                            <?php echo $paginated->serve(); ?>
                        <?php } ?>
                    </div>            
                </div>
            </div>
        
        </div>
        <!-- /.widget-body-toolbar -->
                
        <input type="hidden" name="list[order]" value="<?php echo $state->get('list.order'); ?>" />
        <input type="hidden" name="list[direction]" value="<?php echo $state->get('list.direction'); ?>" />
   
        <?php if (!empty($paginated->items)) { ?>
            <div class="list-group-item">
                <div>
                    Sort by:
                    <a class="btn btn-link" data-sortable="name">Name</a>
                    <a class="btn btn-link" data-sortable="ordering">Manual Ordering</a>
                    <a class="btn btn-link" data-sortable="enabled">Enabled Status</a>
                </div>
            </div>
        
            <?php foreach($paginated->items as $country) { ?>
            <div class="list-group-item">
                
                    <div class="row">
                        <div class="checkbox-column col-xs-1 col-sm-1 col-md-1">
							<input type="checkbox" class="icheck-input" name="ids[]" value="<?php echo $country->id; ?>">
                        </div>
                    
                        <div class="col-xs-11 col-sm-11 col-md-11">
                        	<div class="row">
                                <div class="col-xs-6 col-sm-7 col-md-7">
	                            	<legend>
	                            	  <a href="./admin/shop/country/edit/<?php echo $country->id; ?>"><?php echo $country->name; ?></a>
                                    </legend>
                                    
	                            </div>
		                    	<div class="col-xs-2 col-sm-2 col-md-2">
		                        		<a class="btn btn-xs" href="./admin/shop/countries/moveUp/<?php echo $country->id; ?>" title="Move Up">
		                        			<i class="fa fa-chevron-up"></i>
		                        		</a>
		                        		&nbsp;
		                        		<a class="btn btn-xs" href="./admin/shop/countries/moveDown/<?php echo $country->id; ?>" title="Move Down">
		                        			<i class="fa fa-chevron-down"></i>
		                        		</a>
		                    	</div>	                            
		                        <div class="col-xs-2 col-sm-2 col-md-2">
										<?php if( $country->enabled ) { ?>
		                        		<a class="btn btn-success" href="./admin/shop/countries/disable/<?php echo $country->id; ?>" title="Disable">
		                        			<i class="fa fa-check-square"></i> Enabled
		                        		</a>
		                        		<?php } else { ?>
		                        		<a class="btn btn-warning" href="./admin/shop/countries/enable/<?php echo $country->id; ?>" title="Enable">
		                        			<i class="fa fa-square"></i> Disabled
		                        		</a>
		                        		<?php } ?>
		                    	</div>		                    	
		                    	<div class="col-xs-2 col-sm-1 col-md-1">
			                        <a class="btn btn-danger" data-bootbox="confirm" href="./admin/shop/country/delete/<?php echo $country->id; ?>">
			                            <i class="fa fa-times"></i> Delete
			                        </a>
		                    	</div>
                        	</div>
                        	<div class="row">
                        		<div class="col-xs-4 col-sm-4 col-md-3">
                            		<?php echo $country->isocode_2; ?> | <?php echo $country->isocode_3; ?>
                        		</div>
                        		<div class="col-xs-4 col-sm-4 col-md-3">
                                    <?php $label_class = ($country->requires_postal_code) ? 'label-success' : 'label-default'; ?>
                                    <span class="label <?php echo $label_class; ?>">
                                        <?php echo ($country->requires_postal_code) ? 'Requires Postal Code' : 'No Postal Code Necessary'; ?>
                                    </span>
                        		</div>
                        	</div>
                        </div>
                    </div>
                
            </div>
            <?php } ?>
            
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

</form>

