<div class="row">
	<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
		<h1 class="page-title txt-color-blueDark">
			<i class="fa fa-table fa-fw "></i> 
				Orders 
			<span> > 
				List
			</span>
		</h1>
	</div>
	<div class="col-xs-12 col-sm-5 col-md-5 col-lg-8">
        <ul id="sparks" class="list-actions list-unstyled list-inline">
            <li>
                <a class="btn btn-default" href="./admin/shop/order/create">Add New</a>
            </li>
        </ul>
	</div>
</div>

<form class="searchForm" method="post">

    <div class="no-padding">
        
        <div class="row">
            <div class="col-xs-12 col-sm-5 col-md-5 col-lg-8">
            
                <ul class="list-filters list-unstyled list-inline">
                    <li>
                        <a class="btn btn-link" href="javascript:void(0);" onclick="ShopToggleAdvancedFilters();">Advanced Filters</a>
                    </li>                
                    <li>
                        <select name="filter[status]" class="form-control" onchange="this.form.submit();">
                            <option value="">All Statuses</option>
                            <?php foreach (\Shop\Constants\OrderStatus::fetch() as $status) { ?>
                                <option <?php if($state->get('filter.status') == $status) { echo 'selected'; } ?> value="<?php echo $status; ?>"><?php echo $status; ?></option>
                            <?php } ?>
                        </select>
                    </li>                
                    <li>
                        <select name="filter[fulfillment_status]" class="form-control" onchange="this.form.submit();">
                            <option value="">All Fulfillment Statuses</option>
                             <?php foreach (\Shop\Constants\OrderFulfillmentStatus::fetch() as $status) { ?>
                                <option <?php if($state->get('filter.fulfillment_status') == $status) { echo 'selected'; } ?> value="<?php echo $status; ?>"><?php echo $status; ?></option>
                            <?php } ?>
                        </select>
                    </li>        
                    <li>
                        <select name="filter[financial_status]" class="form-control" onchange="this.form.submit();">
                            <option value="">All Financial Statuses</option>
                             <?php foreach (\Shop\Constants\OrderFinancialStatus::fetch() as $status) { ?>
                                <option <?php if($state->get('filter.financial_status') == $status) { echo 'selected'; } ?> value="<?php echo $status; ?>"><?php echo $status; ?></option>
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
        
    <div id="advanced-filters" class="panel panel-default" 
    <?php 
    if (!$state->get('filter.last_modified_after')
        && !$state->get('filter.last_modified_before')
        && !$state->get('filter.created_after')
        && !$state->get('filter.created_before')
        && !$state->get('filter.user')            
    ) { ?>
        style="display: none;"
    <?php } ?>
    >
        <div class="panel-body">
            <div class="row">
                <div class="col-md-10">
                    <div class="row">
                        <div class="col-md-2">
                            <h4>Customer</h4>
                        </div>
                        <div class="col-md-10">
                            <div class="form-group">
                                <input id="filter_user" type="text" name="filter[user]" placeholder="Search..." class="form-control" value="<?php echo (string) $state->get('filter.user'); ?>" />
                            </div>
                        </div>                
                    </div>
                    
                    <script>
                    jQuery(document).ready(function() {
                        
                        jQuery("#filter_user").select2({
                            allowClear: true, 
                            placeholder: "Search...",
                            multiple: true,
                            maximumSelectionSize: 1,
                            minimumInputLength: 3,
                            ajax: {
                                url: "./admin/shop/customers/forSelection",
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
                            <?php if ($state->get('filter.user')) { ?>
                            , initSelection : function (element, callback) {
                                var data = <?php echo json_encode( \Shop\Models\Customers::forSelection( array('_id'=>new \MongoId( $state->get('filter.user') ) ) ) ); ?>;
                                callback(data);            
                            }
                            <?php } ?>                        
                        });
                    
                    });
                    </script>
                                    
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
        
        <div class="widget-body-toolbar">    
    
            <div class="row">
                <div class="col-xs-12 col-sm-5 col-md-3 col-lg-3">
                    <span class="pagination">
                    <div class="input-group">
                        <select id="bulk-actions" name="bulk_action" class="form-control">
                            <option value="null">-Bulk Actions-</option>
                            <option value="delete" data-action="./admin/shop/orders/delete">Delete</option>
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
            
            <?php foreach($paginated->items as $order) { ?>
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-4">
                            <legend>
                                <a href="./admin/shop/order/edit/<?php echo $order->id; ?>"><?php echo (new \DateTime($order->{'metadata.created.local'}))->format('F j, Y g:i a'); ?></a>
                                
                                <?php switch($order->{'status'}) {
                                	case \Shop\Constants\OrderStatus::cancelled:
                                	    $label_class = 'label-danger';
                                	    break;
                            	    case \Shop\Constants\OrderStatus::closed:
                            	        $label_class = 'label-default';
                            	        break;
                                	case \Shop\Constants\OrderStatus::open:
                                	default:
                                	    $label_class = 'label-success';
                                	    break;
                                
                                } ?>
                                
                                <span class="pull-right label <?php echo $label_class; ?>">
                                <?php echo $order->{'status'}; ?>
                                </span>
                                                                
                            </legend>
                            <div><label>#</label><a href="./admin/shop/order/edit/<?php echo $order->id; ?>"><?php echo $order->{'number'}; ?></a></div>
                            <div><label>Total:</label> <?php echo \Shop\Models\Currency::format( $order->{'grand_total'} ); ?></div>
                            <div>
                                <?php switch($order->{'financial_status'}) {
                                	case \Shop\Constants\OrderFinancialStatus::voided:
                                	    $label_class = 'label-danger';
                                	    break;
                                	     
                                	case \Shop\Constants\OrderFinancialStatus::refunded:
                                	case \Shop\Constants\OrderFinancialStatus::partially_refunded:
                                	    $label_class = 'label-info';
                                	    break;
                                	     
                                	case \Shop\Constants\OrderFinancialStatus::partially_paid:
                                	case \Shop\Constants\OrderFinancialStatus::authorized:
                                	case \Shop\Constants\OrderFinancialStatus::pending:
                                	    $label_class = 'label-warning';
                                	    break;
                                	case \Shop\Constants\OrderFinancialStatus::paid:
                                	default:
                                	    $label_class = 'label-default';
                                	    break;
                                
                                } ?>
                                
                                Financial status: 
                                <span class="label <?php echo $label_class; ?>">
                                <?php echo $order->{'financial_status'}; ?>
                                </span>                            
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-8">
                            <legend>
                                <?php echo $order->customerName(); ?>
                            </legend>
                            
                            <?php foreach ($order->items as $item) { ?>
                            <div class="row">
                                <?php if (\Dsc\ArrayHelper::get($item, 'image')) { ?>
                                <div class="hidden-xs hidden-sm col-md-2">
                                    <img class="img-responsive" src="./asset/thumb/<?php echo \Dsc\ArrayHelper::get($item, 'image'); ?>" alt="" />
                                </div>
                                <?php } ?>
                                <div class="col-xs-12 col-sm-12 col-md-10">
                                    <div class="title">
                                        <?php echo \Dsc\ArrayHelper::get($item, 'product.title'); ?>
                                        <?php if (\Dsc\ArrayHelper::get($item, 'attribute_title')) { ?>
                                        <div>
                                            <small><?php echo \Dsc\ArrayHelper::get($item, 'attribute_title'); ?></small>
                                        </div>
                                        <?php } ?>                        
                                    </div>
                                    <div class="details">
                    
                                    </div>
                                    <div>
                                        <span class="quantity"><?php echo \Dsc\ArrayHelper::get($item, 'quantity'); ?></span>
                                        x
                                        <span class="price"><?php echo \Shop\Models\Currency::format( \Dsc\ArrayHelper::get($item, 'price') ); ?></span> 
                                    </div>                                
                                </div>
                            </div>        
                            <?php } ?>
                                                    
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

