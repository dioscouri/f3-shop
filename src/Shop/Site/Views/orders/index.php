<div class="container order-history">

    <ol class="breadcrumb">
        <li>
            <a href="./shop/account">My Account</a>
        </li>
        <li class="active">My Orders</li>
    </ol>

    <?php if (empty($paginated->items)) { ?>
        <h2>You have made no orders. <a href="./shop"><small>Go Shopping</small></a></h2>
    <?php } else { ?>
        
        <div class="well well-sm search">
            <div class="input-group">
                <input class="form-control" type="text" name="filter[keyword]" placeholder="Search..." maxlength="200" value="<?php echo $state->get('filter.keyword'); ?>"> 
                <span class="input-group-btn">
                    <input class="btn btn-primary" type="submit" onclick="this.form.submit();" value="Search" />
                    <button class="btn btn-danger" type="button" onclick="Dsc.resetFormFilters(this.form);">Reset</button>
                </span>
            </div>
        </div>
        
        <div class="row form-group">
            <div class="col-xs-12 col-sm-5 col-md-3 col-lg-3">
                <span class="pagination">
                    <div class="input-group">
                        <select id="period-filter" name="filter[period]" class="form-control">
                            <option value="">All orders</option>
                        </select>
                        <span class="input-group-btn">
                            <button class="btn btn-default" type="button">Apply</button>
                        </span>
                    </div>
                </span>
            </div>    
            <div class="col-xs-12 col-sm-7 col-md-6 col-md-offset-3 col-lg-6 col-lg-offset-3">
                <div class="pull-right">
                    <?php if (!empty($paginated->total_pages) && $paginated->total_pages > 1) { ?>
                        <?php echo $paginated->serve(); ?>
                    <?php } ?>
                </div>           
            </div>
        </div>
        
        <?php foreach ($paginated->items as $order) { ?>
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-4">
                        <legend><a href="./shop/order/<?php echo $order->id; ?>"><?php echo (new \DateTime($order->{'metadata.created.local'}))->format('F j, Y'); ?></a></legend>
                        <div><label>#</label><a href="./shop/order/<?php echo $order->id; ?>"><?php echo $order->{'number'}; ?></a></div>
                        <div><label>Total:</label> <?php echo $order->{'grand_total'}; ?></div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-8">
                        <legend><?php echo $order->{'status'}; ?></legend>
                        
                        <?php foreach ($order->items as $item) { ?>
                        <div class="row">
                            <div class="hidden-xs hidden-sm col-md-2">
                                <?php if (\Dsc\ArrayHelper::get($item, 'image')) { ?>
                                <img class="img-responsive" src="./asset/thumb/<?php echo \Dsc\ArrayHelper::get($item, 'image'); ?>" alt="" />
                                <?php } ?>
                            </div>
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
                                    <span class="price">$<?php echo \Dsc\ArrayHelper::get($item, 'price'); ?></span> 
                                </div>                                
                            </div>
                        </div>        
                        <?php } ?>
                                                
                    </div>
                </div>
            </div>
        </div>
        <?php } ?>
        
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-10 col-lg-10">
                <?php if (!empty($paginated->total_pages) && $paginated->total_pages > 1) { ?>
                    <?php echo $paginated->serve(); ?>
                <?php } ?>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-2 col-lg-2">
                <div class="datatable-results-count pull-right">
                    <span class="pagination">
                        <?php echo (!empty($paginated->total_pages)) ? $paginated->getResultsCounter() : null; ?>
                    </span>
                </div>
            </div>
        </div>        
            
    <?php } ?>
</div>