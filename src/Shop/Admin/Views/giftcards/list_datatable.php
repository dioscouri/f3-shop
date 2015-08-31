<form class="searchForm" method="post" action="./admin/shop/giftcards">

    <div class="no-padding">
    
        <div class="row">
            <div class="col-xs-12 col-sm-5 col-md-5 col-lg-8">
                
                <ul class="list-filters list-unstyled list-inline">
                    <li>
                        <select name="filter[publication_status]" class="form-control" onchange="this.form.submit();">
                            <option value="">All Statuses</option>
                            <option value="published" <?php if ($state->get('filter.publication_status') == 'published') { echo "selected='selected'"; } ?>>Published</option>
                            <option value="unpublished" <?php if ($state->get('filter.publication_status') == 'unpublished') { echo "selected='selected'"; } ?>>Unpublished</option>
                            <option value="inactive" <?php if ($state->get('filter.publication_status') == 'inactive') { echo "selected='selected'"; } ?>>Inactive</option>
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
                            <button class="btn btn-danger" type="button" onclick="Dsc.resetFormFilters(this.form);">Reset</button>
                        </span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="widget-body-toolbar">    
    
            <div class="row">
                <div class="col-xs-12 col-sm-5 col-md-3 col-lg-3">
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
        
        <input type="hidden" name="list[order]" value="<?php echo $state->get('list.order'); ?>" />
        <input type="hidden" name="list[direction]" value="<?php echo $state->get('list.direction'); ?>" />
        
        <div class="table-responsive datatable dt-wrapper dataTables_wrapper">
        
        <table class="table table-striped table-bordered table-hover table-highlight table-checkable">
        	<thead>
        		<tr>
        		    <th class="checkbox-column"><input type="checkbox" class="icheck-input"></th>
        		    <th class="col-md-1"></th>
        			<th data-sortable="title">Title</th>
        			<th><div class="text-center">Inventory</div></th>
        			<th><div class="text-center">Price</div></th>
        			<th data-sortable="publication.start_date">Publication</th>
        			<th class="col-md-1"></th>
        		</tr>
        	</thead>
        	<tbody>    
        
            <?php if (!empty($paginated->items)) { ?>
            
                <?php foreach($paginated->items as $item) { ?>
                <tr>
                    <td class="checkbox-column">
                        <input type="checkbox" class="icheck-input" name="ids[]" value="<?php echo $item->id; ?>">
                    </td>
                    
                    <td class="">
                        <?php if ($item->{'featured_image.slug'}) { ?>
                            <div class="thumbnail text-center">
                            	<div class="thumbnail-view">
                            		<a class="thumbnail-view-hover ui-lightbox" href="./asset/<?php echo $item->{'featured_image.slug'}; ?>">
                            		</a>
                                    <img src="./asset/thumb/<?php echo $item->{'featured_image.slug'}; ?>" />
        				        </div>
        				    </div> <!-- /.thumbnail -->                    	
                        <?php } ?>
                    </td>
                                                
                    <td class="">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-6">
                                <h5>
                                    <a href="./admin/shop/giftcard/edit/<?php echo $item->id; ?>">
                                    <?php echo $item->{'title'}; ?>
                                    </a>
                                </h5>
                                
                                <div>
                                    <a href="./shop/product/<?php echo $item->slug; ?>" target="_blank">/<?php echo $item->{'slug'}; ?></a>
                                </div>
                                
                                <div>
                                    <label>SKU:</label> <?php echo $item->{'tracking.sku'}; ?>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-6">
                                <div class="text-right">
                                    <div><label>Variants:</label> <?php echo (int) $item->{'variants_count'}; ?></div>
                                    <div><label>Attributes:</label> <?php echo (int) $item->{'attributes_count'}; ?></div>
                                </div>
                            </div>
                        </div>
                        
                        <?php if ($item->{'categories'}) { ?>
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <label>Categories:</label> <?php echo implode(", ", \Dsc\ArrayHelper::getColumn( (array) $item->{'categories'}, 'title' ) ); ?>
                            </div>
                        </div>
                        <?php } ?>
                        
                        <?php if ($item->{'tags'}) { ?>
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <label>Tags:</label> <?php echo implode(", ", (array) $item->{'tags'} ); ?>
                            </div>
                        </div>
                        <?php } ?>
                    </td>
                    
                    <td class="">
                        <div class="text-center"><h3><?php echo (int) $item->{'inventory_count'}; ?></h3></div>
                    </td>
                    
                    <td class="">
                        <div class="text-center"><h3><?php echo '$' . $item->price(); ?></h3></div>
                    </td>
                    
                    <td class="">
                        <div><?php echo ucwords( $item->{'publication.status'} ); ?></div>
                        <div><?php if ($item->{'publication.start_date'}) { echo "Up: " . $item->{'publication.start_date'}; } ?></div>
                        <div><?php if ($item->{'publication.end_date'}) { echo "Down: " . $item->{'publication.end_date'}; } ?></div>
                    </td>
                                    
                    <td class="text-center">
                        <a class="btn btn-xs btn-secondary" href="./admin/shop/giftcard/edit/<?php echo $item->id; ?>">
                            <i class="fa fa-pencil"></i>
                        </a>
                        &nbsp;
                        <a class="btn btn-xs btn-danger" data-bootbox="confirm" href="./admin/shop/giftcard/delete/<?php echo $item->id; ?>">
                            <i class="fa fa-times"></i>
                        </a>
                    </td>
                </tr>
            <?php } ?>
            
            <?php } else { ?>
                <tr>
                <td colspan="100">
                    <div class="">No items found.</div>
                </td>
                </tr>
            <?php } ?>
        
            </tbody>
        </table>
        
        </div>
        <!-- /.table-responsive .datatable .dt-wrapper -->
        
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
    
</form>