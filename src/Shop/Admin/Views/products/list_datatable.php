<form class="searchForm" method="post">

    <div class="no-padding">
    
        <div class="row">
            <div class="col-xs-12 col-sm-5 col-md-5 col-lg-8">
                <?php /* ?>
                <ul class="list-filters list-unstyled list-inline">
                    <li>
                        <a class="btn btn-link">Advanced Filtering</a>
                    </li>                
                    <li>
                        <a class="btn btn-link">Quicklink Filter</a>
                    </li>
                    <li>
                        <a class="btn btn-link">Quicklink Filter</a>
                    </li>                    
                </ul>    
                */ ?>        
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
                            <?php if (!empty($list['count']) && $list['count'] > 1 && !empty($pagination)) { ?>
                                <?php echo $pagination->serve(); ?>
                            <?php } ?>
                        </div>
                        <?php if (!empty($list['subset'])) { ?>
                        <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
                            <span class="pagination">
                            <?php echo $pagination->getLimitBox( $state->get('list.limit') ); ?>
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
        			<th data-sortable="metadata.title">Title</th>
        			<th>Categories</th>
        			<th>Tags</th>
        			<th data-sortable="publication.start_date">Publication</th>
        			<th class="col-md-1"></th>
        		</tr>
        	</thead>
        	<tbody>    
        
            <?php if (!empty($list['subset'])) { ?>
        
            <?php foreach ($list['subset'] as $item) { ?>
                <tr>
                    <td class="checkbox-column">
                        <input type="checkbox" class="icheck-input" name="ids[]" value="<?php echo $item->id; ?>">
                    </td>
                    
                    <td class="">
                        <?php if ($item->{'details.featured_image.slug'}) { ?>
                            <div class="thumbnail text-center">
                            	<div class="thumbnail-view">
                            		<a class="thumbnail-view-hover ui-lightbox" href="./asset/<?php echo $item->{'details.featured_image.slug'}; ?>">
                            		</a>
                                    <img src="./asset/thumb/<?php echo $item->{'details.featured_image.slug'}; ?>" />
        				        </div>
        				    </div> <!-- /.thumbnail -->                    	
                        <?php } ?>
                    </td>
                                                
                    <td class="">
                        <h5>
                        <a href="./admin/shop/product/edit/<?php echo $item->id; ?>">
                        <?php echo $item->{'metadata.title'}; ?>
                        </a>
                        </h5>
                        
                        <p class="help-block">
                        /<?php echo $item->{'metadata.slug'}; ?>
                        </p>                    
                    </td>
                    
                    <td class="">
                    <?php echo implode(", ", \Joomla\Utilities\ArrayHelper::getColumn( (array) $item->{'metadata.categories'}, 'title' ) ); ?>
                    </td>
                    
                    <td class="">
                    <?php echo implode(", ", (array) $item->{'metadata.tags'} ); ?>
                    </td>
                    
                    <td class="">
                        <div><?php echo ucwords( $item->{'publication.status'} ); ?></div>
                        <div><?php if ($item->{'publication.start_date'}) { echo "Up: " . $item->{'publication.start_date'}; } ?></div>
                        <div><?php if ($item->{'publication.end_date'}) { echo "Down: " . $item->{'publication.end_date'}; } ?></div>
                    </td>
                                    
                    <td class="text-center">
                        <a class="btn btn-xs btn-secondary" href="./admin/shop/product/edit/<?php echo $item->id; ?>">
                            <i class="fa fa-pencil"></i>
                        </a>
                        &nbsp;
                        <a class="btn btn-xs btn-danger" data-bootbox="confirm" href="./admin/shop/product/delete/<?php echo $item->id; ?>">
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
                <?php if (!empty($list['count']) && $list['count'] > 1 && !empty($pagination)) { ?>
                    <?php echo (!empty($list['count']) && $list['count'] > 1) ? $pagination->serve() : null; ?>
                <?php } ?>
                </div>
                <div class="col-sm-2">
                    <div class="datatable-results-count pull-right">
                        <span class="pagination">
                            <?php echo (!empty($pagination)) ? $pagination->getResultsCounter() : null; ?>
                        </span>
                    </div>
                </div>        
            </div>
        </div>
    
    </div>
    <!-- /.no-padding -->
    
</form>