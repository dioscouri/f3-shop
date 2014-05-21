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
                                <a href="./admin/shop/order/edit/<?php echo $order->id; ?>"><?php echo (new \DateTime($order->{'metadata.created.local'}))->format('F j, Y'); ?></a>
                                
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

