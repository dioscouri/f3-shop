<div class="container order-detail">

    <ol class="breadcrumb">
        <li>
            <a href="./shop/account">My Account</a>
        </li>
        <li>
            <a href="./shop/orders">My Orders</a>
        </li>
        <li class="active">Order Detail</li>
    </ol>

    <div class="clearfix hidden-xs">
        <div class="pull-left">
            <a class="btn btn-link" href="./shop/orders"><i class="fa fa-chevron-left"></i> Back to List</a>
        </div>
        <div class="pull-right">
            <a class="btn btn-link" href="./shop/order/print/<?php echo $order->id; ?>">Printable version <i class="fa fa-chevron-right"></i></a>
        </div>    
    </div>

    <div class="panel panel-default">
        <div class="panel-body">

            <div class="form-group">
                <legend>
                    <div class="row">
                        <div class="col-xs-12 col-md-12">
                            <small>Summary</small>
                        </div>
                    </div>
                </legend>

                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div>
                            <label>Order #</label>
                            <span class="order-number">
                                <?php echo $order->{'number'}; ?>
                            </span>
                        </div>
                        <div>
                            <label>Date:</label>
                            <span>
                                <?php echo (new \DateTime($order->{'metadata.created.local'}))->format('F j, Y g:ia'); ?>
                            </span>
                        </div>
                        <div>
                            <label class="strong">Total:</label>
                            <span class="price"><?php echo \Shop\Models\Currency::format( $order->grand_total ); ?></span>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div>
                            <label>Status:</label>
                            
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
                            
                            <span class="label <?php echo $label_class; ?>">
                            <?php echo $order->{'status'}; ?>
                            </span>
                            
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-body">
            <div class="form-group">
                <legend>
                    <small>Shipping Information</small>
                </legend>
                <?php if (!$order->{'shipping_required'}) { ?>
                    <p>Shipping not required.</p>
                <?php } else { ?>
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-6">
                    <?php if ($order->{'shipping_address'}) { ?>
                        <address>
                            <?php echo $order->{'shipping_address.name'}; ?><br />
                            <?php echo $order->{'shipping_address.line_1'}; ?><br />
                            <?php echo !empty($order->{'shipping_address.line_2'}) ? $order->{'shipping_address.line_2'} . '<br/>' : null; ?>
                            <?php echo $order->{'shipping_address.city'}; ?> <?php echo $order->{'shipping_address.region'}; ?> <?php echo $order->{'shipping_address.postal_code'}; ?><br />
                            <?php echo $order->{'shipping_address.country'}; ?><br />
                        </address>
                        <?php if (!empty($order->{'shipping_address.phone_number'})) { ?>
                        <div>
                            <label>Phone:</label> <?php echo $order->{'shipping_address.phone_number'}; ?>
                        </div>
                        <?php } ?>
                
                    <?php } ?>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-6">
                    <?php if ($method = $order->shippingMethod()) { ?>
                    <div>
                            <label>Method:</label> <?php echo $method->{'name'}; ?> &mdash; <?php echo \Shop\Models\Currency::format( $method->total() ); ?>
                    </div>
                    <?php } ?>
                    </div>
                </div>
        <?php } ?>
        
    </div>

        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-body">
            <div class="form-group">
                <legend>
                    <small>Payment Information</small>
                </legend>

                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-6">
                    <?php if (($method = $order->paymentMethod()) && $order->grand_total) { ?>
                        <div>
                            <label>Method:</label> <?php echo $method->{'name'}; ?>
                        </div>
                    <?php } ?>
                    <?php if ($order->credit_total) { ?>
                        <div>
                            <label>Store Credit Applied:</label> <?php echo \Shop\Models\Currency::format( $order->credit_total ); ?>
                        </div>
                    <?php } ?>                    
                        
                    <?php if ($order->{'billing_address'}) { ?>
                        <address>
                            <?php echo $order->{'billing_address.name'}; ?><br />
                            <?php echo $order->{'billing_address.line_1'}; ?><br />
                            <?php echo !empty($order->{'billing_address.line_2'}) ? $order->{'billing_address.line_2'} . '<br/>' : null; ?>
                            <?php echo $order->{'billing_address.city'}; ?> <?php echo $order->{'billing_address.region'}; ?> <?php echo $order->{'billing_address.postal_code'}; ?><br />
                            <?php echo $order->{'billing_address.country'}; ?><br />
                        </address>
                        <?php if (!empty($order->{'billing_address.phone_number'})) { ?>
                        <div>
                            <label>Phone:</label> <?php echo $order->{'billing_address.phone_number'}; ?>
                        </div>
                        <?php } ?>                
                    <?php } ?>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="order-totals">
                        <div>
                            <label class="strong">Subtotal:</label>
                            <span class="price"><?php echo \Shop\Models\Currency::format( $order->sub_total ); ?></span>
                        </div>
                        <?php if ($order->discount_total - $order->shipping_discount_total > 0) { ?>
                        <div>
                            <label class="strong">Discount:</label>
                            <span class="price">-<?php echo \Shop\Models\Currency::format( $order->discount_total - $order->shipping_discount_total ); ?></span>
                        </div>
                        <?php } ?>                
                        <?php if ($order->shipping_total > 0) { ?>
                        <div>
                            <label class="strong">Shipping:</label>
                            <span class="price"><?php echo \Shop\Models\Currency::format( $order->shipping_total ); ?></span>
                        </div>
                        <?php } ?>
                        <?php if ($order->shipping_discount_total > 0) { ?>
                        <div>
                            <label class="strong">Shipping Discount:</label>
                            <span class="price">-<?php echo \Shop\Models\Currency::format( $order->shipping_discount_total ); ?></span>
                        </div>
                        <?php } ?>                        
                        <?php if ($order->tax_total > 0) { ?>
                        <div>
                            <label class="strong">Tax:</label>
                            <span class="price"><?php echo \Shop\Models\Currency::format( $order->tax_total ); ?></span>
                        </div>
                        <?php } ?>
                        <?php if ($order->giftcard_total > 0) { ?>
                        <div>
                            <label class="strong">Giftcard:</label>
                            <span class="price">-<?php echo \Shop\Models\Currency::format( $order->giftcard_total ); ?></span>
                        </div>
                        <?php } ?>
                        <?php if ($order->credit_total > 0) { ?>
                        <div>
                            <label class="strong">Credit:</label>
                            <span class="price">-<?php echo \Shop\Models\Currency::format( $order->credit_total ); ?></span>
                        </div>
                        <?php } ?>                        
                        <div>
                            <label class="strong">Total:</label>
                            <span class="price"><?php echo \Shop\Models\Currency::format( $order->grand_total ); ?></span>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-body">
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
                            <?php /* ?>
                            <small>Status</small>
                            */ ?>
                        </div>
                    </div>
                </legend>        
        
                <?php foreach ($order->items as $item) { ?>
                <div class="row">
                    <div class="col-xs-6 col-sm-6 col-md-6">
                        <div class="row">
                        
                            <?php if (\Dsc\ArrayHelper::get($item, 'image')) { ?>
                            <div class="hidden-xs hidden-sm col-md-2">
                                <img class="img-responsive" src="./asset/thumb/<?php echo \Dsc\ArrayHelper::get($item, 'image'); ?>" alt="" />
                            </div>
                            <?php } ?>
                            <div class="col-xs-12 col-sm-12 col-md-10">
                                <h4>
                                    <?php echo \Dsc\ArrayHelper::get($item, 'product.title'); ?>
                                    <?php if (\Dsc\ArrayHelper::get($item, 'attribute_title')) { ?>
                                    <div>
                                        <small><?php echo \Dsc\ArrayHelper::get($item, 'attribute_title'); ?></small>
                                    </div>
                                    <?php } ?>                        
                                </h4>
                                <div class="details"></div>
                                <div>
                                    <span class="quantity"><?php echo $quantity = \Dsc\ArrayHelper::get($item, 'quantity'); ?></span> x <span class="price"><?php echo \Shop\Models\Currency::format( $price = \Dsc\ArrayHelper::get($item, 'price') ); ?></span>
                                </div>
                            </div>                        
                        
                        </div>
                    </div>

                    <div class="col-xs-3 col-sm-3 col-md-3">
                        <?php echo \Shop\Models\Currency::format( $quantity * $price ); ?>
                    </div>
                    <div class="col-xs-3 col-sm-3 col-md-3">
                        <?php /* ?>
                        <?php switch(\Dsc\ArrayHelper::get($item, 'fulfillment_status')) {
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
                        <?php echo \Dsc\ArrayHelper::get($item, 'fulfillment_status', 'n/a'); ?>
                        </span>
                        */ ?>                    
                    </div>  
                    
                </div>        
        <?php } ?>
    </div>

        </div>
    </div>


</div>