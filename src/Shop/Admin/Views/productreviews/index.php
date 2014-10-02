<div class="row">
	<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
		<h1 class="page-title txt-color-blueDark">
			<i class="fa fa-table fa-fw "></i> 
				Product Reviews 
			<span> > 
				List
			</span>
		</h1>
	</div>
	<div class="col-xs-12 col-sm-5 col-md-5 col-lg-8">

	</div>
</div>

<form class="searchForm" method="post" action="./admin/shop/productreviews">

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
                        <option value="draft" <?php if ($state->get('filter.publication_status') == 'draft') { echo "selected='selected'"; } ?>>Draft</option>
                        <option value="published" <?php if ($state->get('filter.publication_status') == 'published') { echo "selected='selected'"; } ?>>Published</option>
                        <option value="unpublished" <?php if ($state->get('filter.publication_status') == 'unpublished') { echo "selected='selected'"; } ?>>Unpublished</option>
                    </select>
                </li>
                <li>
                    <div style="min-width: 150px;">
                        <input id="filter_products" name="filter[product_ids]" value="<?php echo implode(",", (array) $state->get('filter.product_ids') ); ?>" type="text" class="form-control" onchange="this.form.submit();" />
                    </div>
                    <script>
                    jQuery(document).ready(function() {
                        
                        jQuery("#filter_products").select2({
                            allowClear: true, 
                            placeholder: "Product...",
                            multiple: true,
                            minimumInputLength: 3,
                            ajax: {
                                url: "./admin/shop/products/forSelection",
                                dataType: 'json',
                                data: function (term, page) {
                                    return {
                                        q: term
                                    };
                                },
                                results: function (data, page) {
                                    return {results: data.results};
                                }
                            }
                            <?php if ($state->get('filter.product_ids')) { ?>
                            , initSelection : function (element, callback) {
                                var data = <?php echo json_encode( \Shop\Models\Products::forSelection( array('_id'=>array('$in'=>array_map( function($input){ return new \MongoId($input); }, explode(",", $state->get('filter.product_ids') ) ) ) ) ) ); ?>;
                                callback(data);            
                            }
                            <?php } ?>
                        });
                    });
                    </script>                    
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
                                <option value="delete" data-action="./admin/shop/productreviews/delete">Delete</option>
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
                        <a class="btn btn-link" data-sortable="rating">Rating</a>
                        <a class="btn btn-link" data-sortable="metadata.created.time">Creation Date</a>            
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
                                <?php if (!empty($item->images[0])) { ?>
                                    <img class="img-responsive" src="./asset/thumb/<?php echo $item->images[0]; ?>" />
                                <?php } ?>
                            </div>
                                
                            <div class="col-xs-12 col-sm-6 col-md-6">
                                <div>
                                    <a href="./admin/shop/productreview/edit/<?php echo $item->id; ?>">
                                    <?php echo $item->{'title'}; ?>
                                    </a>
                                </div>
                                
                                <small>
                                    <input class="rating" data-size="xs" data-disabled="true" data-readonly="true" data-show-clear="false" data-show-caption="false" value="<?php echo (int) $item->rating; ?>" >
                                </small>
                                
                                <?php if ($item->{'user_id'}) { ?>
                                <div>
                                    <label><?php echo $item->{'user_name'}; ?>:</label> <?php echo $item->user()->email; ?>
                                </div>
                                <?php } ?>
                                
                                <?php if (!empty($item->product()->id)) { ?>
                                <div>
                                    <a href="./shop/product/<?php echo $item->product()->slug; ?>#reviews" target="_blank">
                                    <?php echo $item->product()->title; ?>: <?php echo $item->product()->{'tracking.sku'}; ?>
                                    </a>
                                </div>
                                <?php } ?>
                            </div>
                            <div class="col-xs-12 col-sm-4 col-md-4">
                                <p>
                                    <span class="label <?php echo $item->publishableStatusLabel(); ?>">
                                    <?php echo $item->{'publication.status'}; ?>
                                    </span>
                                    <div><small class="help-block"><?php echo date('M j, Y', $item->{'metadata.created.time'}); ?></small></div>
                                </p>
                            </div>
                        </div>

                    </div>
                    
                                    
                    <div class="col-xs-3 col-sm-2 col-md-2">
    		            <p>
                            <a class="btn btn-xs btn-success" href="./admin/shop/productreview/edit/<?php echo $item->id; ?>">
                                <i class="fa fa-pencil"></i>
                                <small>Edit</small>
                            </a>
                        </p>

                        <p>
                            <a class="btn btn-xs btn-danger" data-bootbox="confirm" href="./admin/shop/productreview/delete/<?php echo $item->id; ?>">
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