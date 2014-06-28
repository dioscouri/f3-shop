<form class="searchForm" method="post" action="./admin/shop/products">

    <input type="hidden" name="list[order]" value="<?php echo $state->get('list.order'); ?>" />
    <input type="hidden" name="list[direction]" value="<?php echo $state->get('list.direction'); ?>" />
        
    <div class="row">
        <div class="col-xs-12 col-sm-5 col-md-5 col-lg-8">
            <ul class="list-filters list-unstyled list-inline">
                <li>
                    <a class="btn btn-link" href="javascript:void(0);" onclick="ShopToggleAdvancedFilters();">Advanced Filters</a>
                </li>
                <li>
                    <select name="filter[publication_status]" class="form-control" onchange="this.form.submit();">
                        <option value="">All Statuses</option>
                        <option value="published" <?php if ($state->get('filter.publication_status') == 'published') { echo "selected='selected'"; } ?>>Published</option>
                        <option value="unpublished" <?php if ($state->get('filter.publication_status') == 'unpublished') { echo "selected='selected'"; } ?>>Unpublished</option>
                        <option value="inactive" <?php if ($state->get('filter.publication_status') == 'inactive') { echo "selected='selected'"; } ?>>Inactive</option>
                    </select>
                </li>
                <li>
                    <select name="filter[inventory_status]" class="form-control" onchange="this.form.submit();">
                        <option value="">All Stock Levels</option>
                        <option value="in_stock" <?php if ($state->get('filter.inventory_status') == 'in_stock') { echo "selected='selected'"; } ?>>In Stock</option>
                        <option value="low_stock" <?php if ($state->get('filter.inventory_status') == 'low_stock') { echo "selected='selected'"; } ?>>Low Stock (<20)</option>
                        <option value="no_stock" <?php if ($state->get('filter.inventory_status') == 'no_stock') { echo "selected='selected'"; } ?>>Out of Stock</option>
                    </select>
                </li>
                <li>
                    <select name="filter[category][id]" class="form-control" onchange="this.form.submit();">
                        <option value="">All Categories</option>
                        <option value="__uncategorized" <?php if ($state->get('filter.category.id') == '__uncategorized') { echo "selected='selected'"; } ?>>Uncategorized</option>
                        <?php foreach (\Shop\Models\Categories::find() as $cat) { ?>
                        	<option value="<?php echo (string) $cat->id; ?>" <?php if ($state->get('filter.category.id') == (string) $cat->id) { echo "selected='selected'"; } ?>><?php echo @str_repeat( "&ndash;", substr_count( @$cat->path, "/" ) - 1 ) . " " . $cat->title; ?></option>
                        <?php } ?>                            
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
                                <option value="delete" data-action="./admin/shop/products/delete">Delete</option>
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
                    <div class="col-xs-10 col-md-11">
                        Sort by:
                        <a class="btn btn-link" data-sortable="title">Title</a>
                        <a class="btn btn-link" data-sortable="inventory_count">Inventory</a>
                        <a class="btn btn-link" data-sortable="prices.default">Price</a>
                        <a class="btn btn-link" data-sortable="publication.start_date">Publication Date</a>            
                    </div>
                </div>
            </div>        

            <?php foreach($paginated->items as $item) { ?>
            <div class="list-group-item">        
                <div class="row">
                    <div class="checkbox-column col-xs-1 col-sm-1 col-md-1">
                        <input type="checkbox" class="icheck-input icheck-id" name="ids[]" value="<?php echo $item->id; ?>">
                    </div>
                                                
                    <div class="col-xs-8 col-sm-9 col-md-9">
                        <div class="row">
                            <div class="hidden-xs col-sm-2 col-md-2">
                                <?php if ($item->{'featured_image.slug'}) { ?>
                                    <div class="thumbnail text-center">
                                    	<div class="thumbnail-view">
                                    		<a class="thumbnail-view-hover ui-lightbox" href="./asset/<?php echo $item->{'featured_image.slug'}; ?>">
                                    		</a>
                                            <img class="img-responsive" src="./asset/thumb/<?php echo $item->{'featured_image.slug'}; ?>" />
                				        </div>
                				    </div> <!-- /.thumbnail -->                    	
                                <?php } ?>
                            </div>
                                
                            <div class="col-xs-12 col-sm-6 col-md-6">
                                <h5>
                                    <a href="./admin/shop/product/edit/<?php echo $item->id; ?>">
                                    <?php echo $item->{'title'}; ?>
                                    </a>
                                </h5>
                                
                                <p>
                                    <a href="./shop/product/<?php echo $item->slug; ?>" target="_blank">/<?php echo $item->{'slug'}; ?></a>
                                </p>
                                
                                <?php if ($item->{'tracking.sku'}) { ?>
                                <p>
                                    <label>SKU:</label> <?php echo $item->{'tracking.sku'}; ?>
                                </p>
                                <?php } ?>
                                
                                <?php if ($item->{'categories'}) { ?>
                    			<p>			                        			
                    				<label>Categories:</label>
									<span class='label label-warning'><?php echo implode("</span> <span class='label label-warning'>", \Joomla\Utilities\ArrayHelper::getColumn( (array) $item->{'categories'}, 'title' ) ); ?></span>
                    			</p>                                
                                <?php } ?>
                                
                                <?php if ($item->{'tags'}) { ?>
                    			<p>
                    				<label>Tags:</label>
									<span class='label label-info'><?php echo implode("</span> <span class='label label-info'>", (array) $item->tags); ?></span>
                    			</p>                                
                                <?php } ?>
                                        
                            </div>
                            <div class="col-xs-12 col-sm-4 col-md-4">
                                <p>
                                    <span class="label <?php echo $item->publishableStatusLabel(); ?>">
                                    <?php echo $item->{'publication.status'}; ?>
                                    </span>
                                    <div><?php if ($item->{'publication.start_date'}) { echo "Up: " . $item->{'publication.start_date'}; } ?></div>
                                    <div><?php if ($item->{'publication.end_date'}) { echo "Down: " . $item->{'publication.end_date'}; } ?></div>
                                </p>
                                        
                                <div><label>Inventory:</label> <?php echo (int) $item->{'inventory_count'}; ?></div>
                                <div><label>Price:</label> <?php echo '$' . $item->price(); ?></div>
                                <div><label>Variants:</label> <?php echo (int) $item->{'variants_count'}; ?></div>
                                <div><label>Attributes:</label> <?php echo (int) $item->{'attributes_count'}; ?></div>
                        
                            </div>
                        </div>

                    </div>
                    
                                    
                    <div class="col-xs-3 col-sm-2 col-md-2">
    		            <p>
                            <a class="btn btn-xs btn-success" href="./admin/shop/product/edit/<?php echo $item->id; ?>">
                                <i class="fa fa-pencil"></i>
                                <small>Edit</small>
                            </a>
                        </p>
    		        	<?php if( $allow_preview ) { ?>
    		        	<p>
                            <a class="btn btn-xs btn-warning" target="_blank" href="./shop/product/<?php echo $item->slug; ?>?preview=1" title="Unpublished Preview">
                                <i class="fa fa-search"></i>
                                <small>Preview</small>
                            </a>
            	        </p>
    		            <?php } ?>
                        <p>
                            <a class="btn btn-xs btn-danger" data-bootbox="confirm" href="./admin/shop/product/delete/<?php echo $item->id; ?>">
                                <i class="fa fa-times"></i>
                                <small>Delete</small>
                            </a>
                        </p>
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