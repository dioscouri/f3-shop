<div class="row">
	<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
		<h1 class="page-title txt-color-blueDark">
			<i class="fa fa-table fa-fw "></i> 
				Orders 
			<span> > 
				Gift Cards
			</span>
		</h1>
	</div>
	<div class="col-xs-12 col-sm-5 col-md-5 col-lg-8">
        <ul id="sparks" class="list-actions list-unstyled list-inline">
            <li>
                <a class="btn btn-default" href="./admin/shop/orders/giftcard/create">Add New</a>
            </li>
        </ul>
	</div>
</div>

<form class="searchForm" method="post">

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
                            <option value="delete" data-action="./admin/shop/orders/giftcard/delete">Delete</option>
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
            
            <?php if (!empty($paginated->items)) { ?>
            
            <?php foreach($paginated->items as $item) { ?>
            <div class="list-group-item">
                
                    <div class="row">
                        <div class="col-xs-2 col-md-1">
                            <input type="checkbox" class="icheck-input" name="ids[]" value="<?php echo $item->id; ?>">
                        </div>
                        <div class="col-xs-10 col-md-4">
                            <h4>
                                <label>Code:</label>
                                <a href="./admin/shop/orders/giftcard/edit/<?php echo $item->id; ?>">
                                    <?php echo $item->code; ?>
                                </a>                            
                            </h4>
                            <div>
                                <label>Created:</label>
                                <a href="./admin/shop/orders/giftcard/edit/<?php echo $item->id; ?>">
                                    <?php echo date( 'Y-m-d g:ia', $item->{'metadata.created.time'} ); ?>
                                </a>
                            </div>
                            <div>
                                <label>Created By:</label>
                                <?php echo $item->{'metadata.creator.name'}; ?>
                            </div>                            
                          
                        </div>
                        <div class="col-xs-10 col-xs-offset-2 col-md-6 col-md-offset-0">
                            <?php if ($item->{'issued_id'}) { ?>
                            <h4>
                                <label>Issued To:</label>
                                <a href="./admin/shop/customer/read/<?php echo $item->issued_id; ?>">
                                    <?php echo $item->{'issued_name'}; ?>
                                </a>
                            </h4>
                            <?php } elseif ($item->issued_email) { ?>
                            <h4>
                                <label>Issued To:</label>
                                <?php echo $item->issued_email; ?>
                            </h4>                            
                            <?php } ?>
                        
                            <div>
                                <label>Initial Value:</label> <?php echo \Shop\Models\Currency::format( $item->{'initial_value'} ); ?> 
                            </div>

                            <div>
                                <label>Balance:</label> <span class='label label-default'><?php echo \Shop\Models\Currency::format( $item->balance() ); ?></span>
                            </div>
                        </div>
                        <div class="hidden-xs hidden-sm col-md-1">
    	                    <a class="btn btn-xs btn-danger" data-bootbox="confirm" href="./admin/shop/orders/giftcard/delete/<?php echo $item->id; ?>">
    	                        <i class="fa fa-times"></i>
    	                    </a>                        
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

