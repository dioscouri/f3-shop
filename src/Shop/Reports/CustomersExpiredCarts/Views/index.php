<div class="row">
    <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
        <h1 class="page-title txt-color-blueDark">
            <i class="fa fa-table fa-fw "></i> Reports
            <span> > Expired Carts </span>
        </h1>
    </div>
    <div class="col-xs-12 col-sm-5 col-md-5 col-lg-8">
        <ul id="sparks" class="list-actions list-unstyled list-inline">
            <li>
                <a class="btn btn-success" href="./admin/shop/reports/<?php echo $report->slug; ?>/purge-expired">Purge Expired Session Carts</a>
            </li>
            <li>
                <a class="btn btn-warning" href="./admin/shop/reports">Close Report</a>
            </li>
        </ul>
    </div>
</div>

<hr />

<form method="post" action="./admin/shop/reports/<?php echo $report->slug; ?>">

    <div class="row">
        <div class="col-xs-12 col-sm-6">
            <ul class="list-filters list-unstyled list-inline">
                <li>
                    <select name="filter[cart_type]" class="form-control" onchange="this.form.submit();">
                        <option value=''>All Cart Types</option>
                        <option value='session' <?php if($state->get('filter.cart_type') == 'session') { echo 'selected'; } ?>>Only show Session Carts</option>
                        <option value='user' <?php if($state->get('filter.cart_type') == 'user') { echo 'selected'; } ?>>Only show User Carts</option>
                    </select>
                </li>
                <li>
                    <a class="btn btn-link" href="javascript:void(0);" onclick="ShopToggleAdvancedFilters();">Advanced Filters</a>
                </li>
                <li>
                    <button class="btn btn-sm btn-danger" type="button" onclick="Dsc.resetFormFilters(this.form);">Reset Filters</button>
                </li>                
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
                            <div class="input-daterange input-group" id="datepicker">
                                <input type="text" name="filter[last_modified_after]" value="<?php echo $state->get('filter.last_modified_after'); ?>" class="input-sm ui-datepicker form-control" data-date-format="yyyy-mm-dd" data-date-today-highlight="true" data-date-today-btn="true" />
                                <span class="input-group-addon">to</span>
                                <input type="text" name="filter[last_modified_before]" value="<?php echo $state->get('filter.last_modified_before'); ?>" class="input-sm ui-datepicker form-control" data-date-format="yyyy-mm-dd" data-date-today-highlight="true" data-date-today-btn="true" />
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

    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="row">
                <div class="col-xs-12 col-sm-6 col-lg-3">
                    <span class="pagination"> </span>
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
        <div class="panel-body">

            <?php if (!empty($paginated->items)) { ?>
                <ul class="list-group">
                <li class="list-group-item">
                    <div class="row">
                        <div class="col-sm-4">
                            <b>Customer</b>
                        </div>
                        <div class="col-sm-2">
                            <b>Items</b>
                        </div>
                        <div class="col-sm-2">
                            <b>Subtotal</b>
                        </div>
                        <div class="col-sm-3">
                            <b>Last Modified</b>
                        </div>
                        <div class="col-sm-1">
                        </div>
                    </div>
                </li>

                <?php \Dsc\System::instance()->get( 'session' )->set( 'delete.redirect', '/admin/shop/reports/' . $report->slug ); ?>
                
                <?php foreach($paginated->items as $key=>$item) { ?>
                    <li class="list-group-item" data-id="<?php echo $item->id; ?>">
                    <div class="row">
                        <div class="col-sm-4">
                            <?php if (!empty($item->user_id)) { ?>
                                <p> <span class="label label-success">Registered User</span> <?php echo $item->user()->fullName(); ?></p>
                                <p><?php echo $item->user()->email; ?></p>
                            <?php } else { ?>
                                <p><?php echo $item->user_email; ?> <span class="label label-warning">Session Cart</span></p>
                            <?php } ?>
                            
                            <p><label>Cart ID:</label> <?php echo $item->id; ?></p>
                            
                            <?php if (!empty($item->user_id)) { ?>
                            <p><a class="btn btn-info" href="./admin/shop/cart/read/<?php echo $item->id; ?>"> View Cart </a></p>
                            <?php } ?>
                        </div>
                        <div class="col-sm-2">
                            <p>Total Items: <?php echo count($item->items); ?></p>
                            <?php if (!empty($item->items)) { ?>
                            <ul>
                            <?php foreach ($item->items as $cartitem) { ?>
                                <li>
                                    <?php echo \Dsc\ArrayHelper::get($cartitem, 'product.title'); ?>
                                    <?php if (\Dsc\ArrayHelper::get($cartitem, 'attribute_title')) { ?>
                                    <div><small><?php echo \Dsc\ArrayHelper::get($cartitem, 'attribute_title'); ?></small></div>
                                    <?php } ?>                                            
                                    <?php if (\Dsc\ArrayHelper::get($cartitem, 'sku')) { ?>
                                    <div>
                                        <small><label>SKU:</label> <?php echo \Dsc\ArrayHelper::get($cartitem, 'sku'); ?></small>
                                    </div>
                                    <?php } ?>                                    
                                </li>
                            <?php } ?>
                            </ul>
                            <?php } ?>
                        </div>
                        <div class="col-sm-2">
                            <?php echo \Shop\Models\Currency::format( $item->subtotal() ); ?>
                        </div>
                        <div class="col-sm-3">
                            <?php echo date( 'Y-m-d g:i a', $item->{'metadata.last_modified.time'} ); ?>
                        </div>
                        <div class="col-sm-1">
                            <a class="btn btn-xs btn-danger" data-bootbox="confirm" href="./admin/shop/reports/<?php echo $report->slug; ?>/deleteCart/<?php echo $item->id; ?>">
                                <i class="fa fa-times"></i>
                            </a>                            
                        </div>
                    </div>
                </li>
                <?php } ?>
                </ul>
            
            <?php } else { ?>
                <p>No items found.</p>
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

    </div>

</form>