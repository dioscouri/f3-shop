<div class="no-padding">

    <div class="row">
        <div class="col-xs-12 col-sm-5 col-md-5 col-lg-8">
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
                        <option value="delete" data-action="./admin/shop/categories/delete">Delete</option>
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
		    <th class="col-md-1 checkbox-column"><input type="checkbox" class="icheck-input"></th>
			<th>Title</th>
			<th>Path</th>
			<th class="col-md-1">Products</th>
			<th class="col-md-1">Cat Specs</th>
			
			<th class="col-md-1"></th>
		</tr>
	</thead>
	<tbody>    

    <?php if (!empty($paginated->items)) { ?>
            
        <?php foreach($paginated->items as $item) { ?>
        
        
        <tr  class="<?php // if( (string) $item->{'metadata.last_modified_by.name'} == (string)'Justin Smith') { echo 'success';}?>">
            <td class="checkbox-column">
                <input type="checkbox" class="icheck-input" name="ids[]" value="<?php echo $item->_id; ?>">
            </td>
            
            <td class="" colspan="2">
    
    		<?php if(!empty($item->{'category_image.slug'})) : ?>        
            <img style="float:left; max-width:100px;" class="thumbnail" src="/asset/thumb/<?php echo $item->{'category_image.slug'}; ?>">
            <?php endif;?>
                <a href="./admin/shop/category/edit/<?php echo $item->_id; ?>">
                <?php echo @str_repeat( "&ndash;", substr_count( @$item->path, "/" ) - 1 ) . " " . $item->title; ?>
                </a> <br>
                 <a style="color:#333; font-size: 9px;" href="./admin/shop/category/edit/<?php echo $item->_id; ?>">
                <?php echo $item->path; ?>
                </a>
            </td>
            
          
            
            <td class="">
                <?php echo \Shop\Models\Categories::productCount( $item->_id ); ?>
            </td>
            <td class="">
                <?php echo count($item->product_specs); ?>
            </td>
            
         
                            
            <td class="text-center col-lg-2 col-md-3">
	        	<?php if( $allow_preview ) { ?>
                <a class="btn btn-xs btn-warning" target="_blank" title="Unpublished Preview" href="./shop/category/<?php echo $item->slug; ?>?preview=1">
                   <i class="fa fa-search"></i>
                 </a>
   	            &nbsp;
	            <?php } ?>
            
                <a class="btn btn-xs btn-secondary" href="./admin/shop/category/edit/<?php echo $item->_id; ?>">
                    <i class="fa fa-pencil"></i>
                </a>
                &nbsp;
                <a class="btn btn-xs btn-danger" data-bootbox="confirm" href="./admin/shop/category/delete/<?php echo $item->_id; ?>">
                    <i class="fa fa-times"></i>
                </a>
                <a class="btn btn-xs btn-info" href="./shop/category<?php echo $item->get('path'); ?>" target="_blank"> <i class="fa fa-globe"></i></a>
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