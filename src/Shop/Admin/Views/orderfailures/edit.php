<div class="clearfix">
    <div class="pull-right">
        <a class="btn btn-default" href="./admin/shop/orderfailures">Return to List</a>
    </div>
</div>
<!-- /.form-group -->

<hr />

<div class="row">
    <div class="col-md-9">

        <div class="well">
        
            <div class="form-group">
                <legend>
                    <div class="row">
                        <div class="col-xs-12 col-sm-6 col-md-9">
                            <small>Summary</small>
                        </div>
                        <div class="hidden-xs col-sm-6 col-md-3">
        
                        </div>
                    </div>
                </legend>
                
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div>
                            <label>Order #</label>
                            <span class="order-number">
                                <?php echo $item->{'number'}; ?>
                            </span>
                        </div>
                        <div>
                            <label>Date:</label>
                            <span>
                                <?php echo (new \DateTime($item->{'metadata.created.local'}))->format('F j, Y g:ia'); ?>
                            </span>
                        </div>
                        <div>
                            <label class="strong">Total:</label>
                            <span class="price"><?php echo \Shop\Models\Currency::format( $item->grand_total ); ?></span>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div>
                            <label>Order Status:</label>
                            
                            <?php switch($item->{'status'}) {
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
                            
                            <span class="label <?php echo $label_class; ?>">
                            <?php echo $item->{'status'}; ?>
                            </span>
                                                       
                        </div>
                        <div>
                            <label>Fulfillment Status:</label>
                            
                            <?php switch($item->{'fulfillment_status'}) {
                            	case \Shop\Constants\OrderFulfillmentStatus::fulfilled:
                            	    $label_class = 'label-default';
                            	    break;
                        	    case \Shop\Constants\OrderFulfillmentStatus::partial:
                        	        $label_class = 'label-warning';
                        	        break;
                            	case \Shop\Constants\OrderFulfillmentStatus::unfulfilled:
                            	default:
                            	    $label_class = 'label-success';
                            	    break;
                            
                            } ?>
                            
                            <span class="label <?php echo $label_class; ?>">
                            <?php echo $item->{'fulfillment_status'}; ?>
                            </span>
                                                       
                        </div>
                        <div>
                            <label>Payment Status:</label>
                            
                            <?php switch($item->{'financial_status'}) {
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
                            
                            <span class="label <?php echo $label_class; ?>">
                            <?php echo $item->{'financial_status'}; ?>
                            </span>
                                                       
                        </div>
                        
                    </div>
                </div>
              
            </div>
            
            <div class="form-group">
                <legend>
                    <small>Shipping Information</small>
                </legend>
                <?php if (!$item->{'shipping_required'}) { ?>
                    <p>Shipping not required.</p>
                <?php } else { ?>
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <?php if ($item->{'shipping_address'}) { ?>
                            <address>
                                <?php echo $item->{'shipping_address.name'}; ?><br/>
                                <?php echo $item->{'shipping_address.line_1'}; ?><br/>
                                <?php echo !empty($item->{'shipping_address.line_2'}) ? $item->{'shipping_address.line_2'} . '<br/>' : null; ?>
                                <?php echo $item->{'shipping_address.city'}; ?> <?php echo $item->{'shipping_address.region'}; ?> <?php echo $item->{'shipping_address.postal_code'}; ?><br/>
                                <?php echo $item->{'shipping_address.country'}; ?><br/>
                            </address>
                            <?php if (!empty($item->{'shipping_address.phone_number'})) { ?>
                            <div>
                                <label>Phone:</label> <?php echo $item->{'shipping_address.phone_number'}; ?>
                            </div>
                            <?php } ?>
                        
                        <?php } ?>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <?php if ($method = $item->shippingMethod()) { ?>
                            <div>
                                <label>Method:</label> <?php echo $method->{'name'}; ?> &mdash; <?php echo \Shop\Models\Currency::format( $method->total() ); ?>
                            </div>
                        <?php } ?>
                    </div>
                </div>
                <?php } ?>
                
            </div>
            
            <div class="form-group">
                <legend>
                    <small>Payment Information</small>
                </legend>
                
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-6">
                    <?php if ($item->payment_method_id) { ?>
                        <div>
                            <label>Gateway:</label> <?php echo $item->payment_method_id; ?>
                        </div>
                    <?php } ?>                    
                    <?php if (($method = $item->paymentMethod()) && $item->grand_total) { ?>
                        <div>
                            <label>Method:</label> <?php echo $method->{'name'}; ?>
                        </div>
                    <?php } ?>
                    <?php if ($item->credit_total) { ?>
                        <div>
                            <label>Store Credit Applied:</label> <?php echo \Shop\Models\Currency::format( $item->credit_total ); ?>
                        </div>
                    <?php } ?>
                        
                    <?php if ($item->{'billing_address'}) { ?>
                        <address>
                            <?php echo $item->{'billing_address.name'}; ?><br />
                            <?php echo $item->{'billing_address.line_1'}; ?><br />
                            <?php echo !empty($item->{'billing_address.line_2'}) ? $item->{'billing_address.line_2'} . '<br/>' : null; ?>
                            <?php echo $item->{'billing_address.city'}; ?> <?php echo $item->{'billing_address.region'}; ?> <?php echo $item->{'billing_address.postal_code'}; ?><br />
                            <?php echo $item->{'billing_address.country'}; ?><br />
                        </address>
                        <?php if (!empty($item->{'billing_address.phone_number'})) { ?>
                        <div>
                            <label>Phone:</label> <?php echo $item->{'billing_address.phone_number'}; ?>
                        </div>
                        <?php } ?>                
                    <?php } ?>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="order-totals">
                        <div>
                            <label class="strong">Subtotal:</label>
                            <span class="price"><?php echo \Shop\Models\Currency::format( $item->sub_total ); ?></span>
                        </div>
                        <?php if ($item->discount_total > 0) { ?>
                        <div>
                            <label class="strong">Discount:</label>
                            <span class="price">-<?php echo \Shop\Models\Currency::format( $item->discount_total ); ?></span>
                        </div>
                        <?php } ?>                
                        <?php if ($item->shipping_total > 0) { ?>
                        <div>
                            <label class="strong">Shipping:</label>
                            <span class="price"><?php echo \Shop\Models\Currency::format( $item->shipping_total ); ?></span>
                        </div>
                        <?php } ?>
                        <?php if ($item->tax_total > 0) { ?>
                        <div>
                            <label class="strong">Tax:</label>
                            <span class="price"><?php echo \Shop\Models\Currency::format( $item->tax_total ); ?></span>
                        </div>
                        <?php } ?>
                        <?php if ($item->giftcard_total > 0) { ?>
                        <div>
                            <label class="strong">Giftcard:</label>
                            <span class="price">-<?php echo \Shop\Models\Currency::format( $item->giftcard_total ); ?></span>
                        </div>
                        <?php } ?>
                        <?php if ($item->credit_total > 0) { ?>
                        <div>
                            <label class="strong">Credit:</label>
                            <span class="price">-<?php echo \Shop\Models\Currency::format( $item->credit_total ); ?></span>
                        </div>
                        <?php } ?>                        
                        <div>
                            <label class="strong">Total:</label>
                            <span class="price"><?php echo \Shop\Models\Currency::format( $item->grand_total ); ?></span>
                        </div>
                        </div>
                    </div>
                </div>
                   
            </div>
            
            <div class="form-group">
                <legend>
                    <div class="row">
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <small>Items</small>
                        </div>
                        <div class="col-xs-3 col-sm-3 col-md-3">
                            <small>Price</small>
                        </div>
                        <div class="col-xs-3 col-sm-3 col-md-3">
                            <small>Status</small>
                        </div>                        
                    </div>
                </legend>        
                
                <?php foreach ($item->items as $orderitem) { ?>
                <div class="row">
                    <div class="col-xs-6 col-sm-6 col-md-6">
                        <div class="row">
                        
                            <?php if (\Dsc\ArrayHelper::get($orderitem, 'image')) { ?>
                            <div class="hidden-xs hidden-sm col-md-2">
                                <img class="img-responsive" src="./asset/thumb/<?php echo \Dsc\ArrayHelper::get($orderitem, 'image'); ?>" alt="" />
                            </div>
                            <?php } ?>
                            <div class="col-xs-12 col-sm-12 col-md-10">
                                <h4>
                                    <?php echo \Dsc\ArrayHelper::get($orderitem, 'product.title'); ?>
                                    <?php if (\Dsc\ArrayHelper::get($orderitem, 'attribute_title')) { ?>
                                    <div>
                                        <small><?php echo \Dsc\ArrayHelper::get($orderitem, 'attribute_title'); ?></small>
                                    </div>
                                    <?php } ?>                        
                                </h4>
                                <div class="details">
                
                                </div>
                                <div>
                                    <span class="quantity"><?php echo $quantity = \Dsc\ArrayHelper::get($orderitem, 'quantity'); ?></span>
                                    x
                                    <span class="price"><?php echo \Shop\Models\Currency::format( $price = \Dsc\ArrayHelper::get($orderitem, 'price') ); ?></span> 
                                </div>
                            </div>                        
                        
                        </div>
                    </div>
                    <div class="col-xs-3 col-sm-3 col-md-3">
                        <?php echo \Shop\Models\Currency::format( $quantity * $price ); ?>
                    </div>
                    <div class="col-xs-3 col-sm-3 col-md-3">

                        <?php switch(\Dsc\ArrayHelper::get($orderitem, 'fulfillment_status')) {
                        	case \Shop\Constants\OrderFulfillmentStatus::fulfilled:
                        	    $label_class = 'label-default';
                        	    break;
                    	    case \Shop\Constants\OrderFulfillmentStatus::partial:
                    	        $label_class = 'label-warning';
                    	        break;
                        	case \Shop\Constants\OrderFulfillmentStatus::unfulfilled:
                        	default:
                        	    $label_class = 'label-success';
                        	    break;
                        
                        } ?>
                        
                        <span class="label <?php echo $label_class; ?>">
                        <?php echo \Dsc\ArrayHelper::get($orderitem, 'fulfillment_status', 'n/a'); ?>
                        </span>
                                                    
                    </div>                        

                </div>        
                <?php } ?>
            </div>
        </div>
        
        <div class="well">
            <div class="row">
                <div class="col-md-2">
                    
                    <h3>Discounts</h3>
                    <p class="help-block">The discounts applied to this order.</p>
                            
                </div>
                <!-- /.col-md-2 -->
                            
                <div class="col-md-10">
                    <?php if ($userCoupons = $item->userCoupons()) { ?>
                    <h5>User-submitted Coupons</h5>
                    <ul class="list-group">
                    <?php foreach ($userCoupons as $coupon) { ?>
                        <li class="list-group-item">
                            <div class="row">
                                <div class="col-md-2">
                                    <?php echo \Dsc\ArrayHelper::get( $coupon, 'code' ); ?>
                                </div>
                                <div class="col-md-10">
                                    <span class="price"><?php echo \Shop\Models\Currency::format( $price = \Dsc\ArrayHelper::get($coupon, 'amount') ); ?></span>
                                </div>
                            </div>
                        </li>
                    <?php } ?>
                    </ul>
                    <?php } ?>
                    
                    <?php if ($autoCoupons = $item->autoCoupons()) { ?>
                    <h5>Automatic Coupons</h5>
                    <ul class="list-group">
                    <?php foreach ($autoCoupons as $coupon) { ?>
                        <li class="list-group-item">
                            <div class="row">
                                <div class="col-md-2">
                                    <?php echo \Dsc\ArrayHelper::get( $coupon, 'code' ); ?>
                                </div>
                                <div class="col-md-10">
                                    <span class="price"><?php echo \Shop\Models\Currency::format( $price = \Dsc\ArrayHelper::get($coupon, 'amount') ); ?></span>
                                </div>
                            </div>
                        </li>
                    <?php } ?>
                    </ul>
                    <?php } ?>
                    
                </div>
                <!-- /.col-md-10 -->
                
            </div>        
        </div>
        <!-- /.well -->
        
        <div class="well">
            <div class="row">
                <div class="col-md-2">
                    
                    <h3>History</h3>
                    <p class="help-block">The activity log for this order.</p>
                            
                </div>
                <!-- /.col-md-2 -->
                            
                <div class="col-md-10">
                    
                    <ul class="list-group">
                    <?php foreach ($item->history as $history) { ?>
                        <li class="list-group-item">
                            <div class="row">
                                <div class="col-md-2">
                                    <?php echo \Dsc\ArrayHelper::get( $history, 'created.local' ); ?>
                                </div>
                                <div class="col-md-10">
                                    <?php $dump = $history; unset( $dump['created'] ); ?>
                                    <?php echo \Dsc\Debug::dump( $dump ); ?>
                                </div>
                            </div>
                        </li>
                    <?php } ?>
                        <li class="list-group-item">
                            <div class="row">
                                <div class="col-md-2">
                                    <?php echo (new \DateTime($item->{'metadata.created.local'}))->format('F j, Y g:ia'); ?>
                                </div>
                                <div class="col-md-10">
                                    Created
                                </div>
                            </div>
                        </li>                    
                    </ul>
                    
                </div>
                <!-- /.col-md-10 -->
                
            </div>        
        </div>
        <!-- /.well -->
        
    </div>
    <div class="col-md-3">
        <?php if (!empty($item->errors)) { ?>
        <div>
            <label>Errors:</label>
            <ul class="list-group">
            <?php foreach ($item->errors as $error) { ?>
                <li class="list-group-item"><?php echo $error; ?></li>
            <?php } ?>
            </ul>
        </div>
        <?php } ?>

    </div>
</div>