<div class="row">
	<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
		<h1 class="page-title txt-color-blueDark">
			<i class="fa fa-table fa-fw "></i> 
				Campaigns 
			<span> > 
				List
			</span>
		</h1>
	</div>
	<div class="col-xs-12 col-sm-5 col-md-5 col-lg-8">
        <ul id="sparks" class="list-actions list-unstyled list-inline">
            <li>
                <a class="btn btn-default" href="./admin/shop/campaign/create">Add New</a>
            </li>
        </ul>
	</div>
</div>

<form class="searchForm" method="post" action="./admin/shop/campaigns">

    <input type="hidden" name="list[order]" value="<?php echo $state->get('list.order'); ?>" />
    <input type="hidden" name="list[direction]" value="<?php echo $state->get('list.direction'); ?>" />
        
    <div class="row">
        <div class="col-xs-12 col-sm-5 col-md-5 col-lg-8">
            <ul class="list-filters list-unstyled list-inline">
                <li>
                    <a class="btn btn-link" href="javascript:void(0);" onclick="ShopToggleAdvancedFilters();">Advanced Filters</a>
                </li>
                <li>
                </li>
            </ul>        
        </div>
        <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
            <div class="form-group">
                <div class="input-group">
                    <input class="form-control" type="text" name="filter[keyword]" placeholder="Search..." maxlength="200" value="<?php echo $state->get('filter.keyword'); ?>"> 
                    <span class="input-group-btn">
                        <input class="btn btn-primary" type="submit" onclick="this.form.submit();" value="Search" />
                        <button class="btn btn-danger" type="button" onclick="Dsc.resetFormFilters(this.form);">Reset Filters</button>
                    </span>
                </div>
            </div>
        </div>
    </div>
        
    <div id="advanced-filters" class="panel panel-default" 
    <?php 
    if (!$state->get('filter.last_modified_after')
        && !$state->get('filter.last_modified_before')
        && !$state->get('filter.created_after')
        && !$state->get('filter.created_before')            
    ) { ?>
        style="display: none;"
    <?php } ?>
    >
        <div class="panel-body">
            <div class="row">
                <div class="col-md-10">
                    <div class="row">
                        <div class="col-md-2">
                            <h4>Last Modified</h4>
                        </div>
                        <div class="col-md-10">
                            <div class="form-group">
                                <div class="input-daterange input-group" id="datepicker">
                                    <input type="text" name="filter[last_modified_after]" value="<?php echo $state->get('filter.last_modified_after'); ?>" class="input-sm ui-datepicker form-control" data-date-format="yyyy-mm-dd" data-date-today-highlight="true" data-date-today-btn="true" />
                                    <span class="input-group-addon">to</span>
                                    <input type="text" name="filter[last_modified_before]" value="<?php echo $state->get('filter.last_modified_before'); ?>" class="input-sm ui-datepicker form-control" data-date-format="yyyy-mm-dd" data-date-today-highlight="true" data-date-today-btn="true" />
                                </div>
                            </div>
                        </div>                
                    </div>
                    
                    <div class="row">
                        <div class="col-md-2">
                            <h4>Created</h4>
                        </div>
                        <div class="col-md-10">
                            <div class="form-group">
                                <div class="input-daterange input-group" id="datepicker">
                                    <input type="text" name="filter[created_after]" value="<?php echo $state->get('filter.created_after'); ?>" class="input-sm ui-datepicker form-control" data-date-format="yyyy-mm-dd" data-date-today-highlight="true" data-date-today-btn="true" />
                                    <span class="input-group-addon">to</span>
                                    <input type="text" name="filter[created_before]" value="<?php echo $state->get('filter.created_before'); ?>" class="input-sm ui-datepicker form-control" data-date-format="yyyy-mm-dd" data-date-today-highlight="true" data-date-today-btn="true" />
                                </div>
                            </div>
                        </div>                
                    </div>                    
                    
                </div>
                <div class="col-md-2">
                    <button class="btn btn-primary pull-right">Go</button>
                </div>
            </div>   
        </div> 
    </div>
    
    <script>
    ShopToggleAdvancedFilters = function(el) {
        var filters = jQuery('#advanced-filters');
        if (filters.is(':hidden')) {
            filters.slideDown();        
        } else {
        	filters.slideUp();
        }
    }
    </script>           
            
    <?php if (!empty($paginated->items)) { ?>
    <div class="panel panel-default">
        <div class="panel-heading">
        
            <div class="row">
                <div class="col-xs-12 col-sm-4 col-md-4 col-lg-3">
                    <span class="pagination">
                        <div class="input-group">
                            <select id="bulk-actions" name="bulk_action" class="form-control">
                                <option value="null">-Bulk Actions-</option>
                                <option value="delete" data-action="./admin/shop/campaigns/delete">Delete</option>
                            </select>
                            <span class="input-group-btn">
                                <button class="btn btn-default bulk-actions" type="button" data-target="bulk-actions">Apply</button>
                            </span>
                        </div>
                    </span>
                </div>
                  
                <div class="col-xs-8 col-sm-5 col-md-5 col-lg-6">
                    <?php if (!empty($paginated->total_pages) && $paginated->total_pages > 1) { ?>
                        <?php echo $paginated->serve(); ?>
                    <?php } ?>            
                </div>
                
                <?php if (!empty($paginated->items)) { ?>
                <div class="col-xs-4 col-sm-3 col-md-3 col-lg-3 text-align-right">
                    <span class="pagination">
                        <span class="hidden-xs hidden-sm">
                            <?php echo $paginated->getResultsCounter(); ?>
                        </span>
                    </span>
                    <span class="pagination">
                        <?php echo $paginated->getLimitBox( $state->get('list.limit') ); ?>
                    </span>                                        
                </div>
                <?php } ?>        
                
            </div>            
            
        </div>
        <div class="panel-body">
            <div class="list-group-item">
                <div class="row">
                    <div class="col-xs-2 col-md-1">
                        <input type="checkbox" class="icheck-toggle icheck-input" data-target="icheck-id">
                    </div>
                    <div class="col-xs-10 col-md-3" data-sortable="title">
                        <b>Title</b>
                    </div>
                    <div class="col-md-2 text-center" data-sortable="publication.start.time">
                        <b>Starts</b>
                    </div>                    
                    <div class="col-md-2 text-center" data-sortable="publication.end.time">
                        <b>Ends</b>
                    </div>
                    <div class="col-md-2 text-center" data-sortable="publication.status">
                        <b>Status</b>
                    </div>
                    <div class="hidden-xs hidden-sm col-md-2">
                        
                    </div>
                </div>
            </div>            
        
            <?php foreach($paginated->items as $item) { ?>
            <div class="list-group-item">
                
                <div class="row">
                    <div class="col-xs-2 col-md-1">
                        <input type="checkbox" class="icheck-input icheck-id" name="ids[]" value="<?php echo $item->id; ?>">
                    </div>
                    <div class="col-xs-10 col-md-3">
                        <?php if ($item->groups) { ?>
                        <div class="pull-right">
                            <span class='label label-default'><?php echo implode("</span> <span class='label label-default'>", \Dsc\ArrayHelper::getColumn( (array) $item->groups, 'title' ) ); ?></span>
                        </div>
                        <?php } ?>                        
                        
                        <h4>
                            <a href="./admin/shop/campaign/read/<?php echo $item->id; ?>">
                                <?php echo $item->ancestorsIndentedTitle(); ?>
                            </a>
                        </h4>
                    </div>
                    <div class="col-md-2 text-center">
                        <?php echo $item->publishStarts(); ?>
                    </div>
                    <div class="col-md-2 text-center">
                        <?php echo $item->publishEnds(); ?>                        
                    </div>
                    <div class="col-md-2 text-center">
                        <span class="label <?php echo $item->publishableStatusLabel(); ?>">
                        <?php echo $item->{'publication.status'}; ?>
                        </span>
                    </div>
                    <div class="hidden-xs hidden-sm col-md-2">
                        <span class="pull-right">
                            <a class="btn btn-xs btn-warning" href="./admin/shop/campaign/edit/<?php echo $item->id; ?>">
                                <i class="fa fa-pencil"></i>
                            </a>
                            &nbsp;                        
    	                    <a class="btn btn-xs btn-danger" data-bootbox="confirm" href="./admin/shop/campaign/delete/<?php echo $item->id; ?>">
    	                        <i class="fa fa-times"></i>
    	                    </a>
	                    </span>
                    </div>
                </div>
                
            </div>
            <?php } ?>
            
        </div>
            
        <div class="panel-footer">
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
            
    <?php } else { ?>
        <div class="list-group-item">
            No items found.
        </div>
    <?php } ?>

    </div>

</form>

