<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!-->
<html class="no-js">
<!--<![endif]-->

<head>
    <?php echo $this->renderView('Theme/Views::head.php'); ?>
</head>

<body class="print">

    <div class="container order-detail order-print">

        <div class="order-print-header form-group">
        <?php echo \Shop\Models\Settings::fetch()->{'orders.printing.header'}; ?>
        </div>

        <div class="clearfix">
            <div class="pull-right hidden-print">
                <a class="btn btn-link" href="./shop/order/<?php echo $order->id; ?>"><i class="fa fa-chevron-left"></i> Return to Order Summary</a>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-body">

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
                                <?php echo (new \DateTime($order->{'metadata.created.local'}))->format('F j, Y'); ?>
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
                            <span><?php echo $order->{'status'}; ?></span>
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
                        <?php if ($method = $order->paymentMethod()) { ?>
                            <div>
                                <label>Method:</label> <?php echo $method->{'name'}; ?>
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
                            <?php if ($order->discount_total > 0) { ?>
                            <div>
                                <label class="strong">Discount:</label>
                                <span class="price"><?php echo \Shop\Models\Currency::format( $order->discount_total ); ?></span>
                            </div>
                            <?php } ?>                
                            <?php if ($order->shipping_total > 0) { ?>
                            <div>
                                <label class="strong">Shipping:</label>
                                <span class="price"><?php echo \Shop\Models\Currency::format( $order->shipping_total ); ?></span>
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
                            <div class="col-xs-10 col-sm-10 col-md-10">
                                <small>Items</small>
                            </div>
                            <div class="col-xs-2 col-sm-2 col-md-2">
                                <div class="pull-right">
                                    <small>Price</small>
                                </div>
                            </div>
                        </div>
                    </legend>        
                
                    <?php foreach ($order->items as $item) { ?>
                        <div class="row">
                            <div class="col-xs-10 col-sm-10 col-md-10">
                                <div class="title">
                                <?php echo \Dsc\ArrayHelper::get($item, 'product.title'); ?>
                                <?php if (\Dsc\ArrayHelper::get($item, 'attribute_title')) { ?>
                                <div>
                                    <small><?php echo \Dsc\ArrayHelper::get($item, 'attribute_title'); ?></small>
                                </div>
                                <?php } ?>                        
                                </div>
                                <div class="details"></div>
                                <div>
                                    <span class="quantity"><?php echo $quantity = \Dsc\ArrayHelper::get($item, 'quantity'); ?></span> x <span class="price"><?php echo \Shop\Models\Currency::format( $price = \Dsc\ArrayHelper::get($item, 'price') ); ?></span>
                                </div>
                            </div>
                            <div class="col-xs-2 col-sm-2 col-md-2">
                                <div class="pull-right"><?php echo \Shop\Models\Currency::format( $quantity * $price ); ?></div>
                            </div>
                        </div>        
                    <?php } ?>
                </div>
    
            </div>
        </div>

        <div class="order-footer">
            <?php echo \Shop\Models\Settings::fetch()->{'orders.printing.footer'}; ?>
        </div>

    </div>

</body>

</html>