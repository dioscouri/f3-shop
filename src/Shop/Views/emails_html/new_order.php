<?php $order_link = $SCHEME . '://' . $HOST . $BASE . '/shop/order/' . $order->id; ?>
<?php $print_link = $SCHEME . '://' . $HOST . $BASE . '/shop/order/print/' . $order->id; ?>

<p>Thank you for your order!</p>
<p>To view your order online, click here: <a href="<?php echo $order_link; ?>"><?php echo $order_link; ?></a></p>
<p>Complete details of your order are below.</p>
<p>Thanks again!</p>

<hr/> 

<h3>
    Summary
    <div>
        <small><a href="<?php echo $print_link; ?>"><small>Printable version</small></a></small>
    </div>
</h3>
<div>    
    <div><b>Order placed:</b> <?php echo (new \DateTime($order->{'metadata.created.local'}))->format('F j, Y'); ?></div>
    <div><b>Order total:</b> <?php echo $order->{'grand_total'}; ?></div>
    <div><b>Order #</b><?php echo $order->{'number'}; ?></div>
    <div><b>Order status:</b> <?php echo $order->{'status'}; ?></div>
</div>       

<hr/>

<?php if ($order->{'shipping_required'}) { ?>
<h3>
    Shipping Information
</h3>

<div>
<?php if ($order->{'shipping_address'}) { ?>
    <address>
        <?php echo $order->{'shipping_address.name'}; ?><br/>
        <?php echo $order->{'shipping_address.line_1'}; ?><br/>
        <?php echo !empty($order->{'shipping_address.line_2'}) ? $order->{'shipping_address.line_2'} . '<br/>' : null; ?>
        <?php echo $order->{'shipping_address.city'}; ?> <?php echo $order->{'shipping_address.region'}; ?> <?php echo $order->{'shipping_address.postal_code'}; ?><br/>
        <?php echo $order->{'shipping_address.country'}; ?><br/>
    </address>
    <?php if (!empty($order->{'shipping_address.phone_number'})) { ?>
    <div>
        <b>Phone:</b> <?php echo $order->{'shipping_address.phone_number'}; ?>
    </div>
    <?php } ?>

<?php } ?>

<?php if ($method = $order->shippingMethod()) { ?>
    <div>
        <b>Method:</b> <?php echo $method->{'name'}; ?> &mdash; $<?php echo $method->total(); ?>
    </div>
<?php } ?>

    <?php foreach ($order->shipments as $key=>$shipment) { ?>
    <div>
        <h4>Shipment <?php echo $key+1; ?></h4>
        <div>Shipping Vendor (UPS/USPS/Fedex/etc)</div>
        <div>Tracking number + link</div>
        <div>Address</div>
        <div>Items in shipment</div>
    </div>
    <?php } ?>
</div>
<?php } ?>

<hr/>

<h3>
    Payment Information
</h3>

<div>
    <?php if ($order->{'billing_address'}) { ?>
        <address>
            <?php echo $order->{'billing_address.name'}; ?><br/>
            <?php echo $order->{'billing_address.line_1'}; ?><br/>
            <?php echo !empty($order->{'billing_address.line_2'}) ? $order->{'billing_address.line_2'} . '<br/>' : null; ?>
            <?php echo $order->{'billing_address.city'}; ?> <?php echo $order->{'billing_address.region'}; ?> <?php echo $order->{'billing_address.postal_code'}; ?><br/>
            <?php echo $order->{'billing_address.country'}; ?><br/>
        </address>
        <?php if (!empty($order->{'billing_address.phone_number'})) { ?>
        <div>
            <b>Phone:</b> <?php echo $order->{'billing_address.phone_number'}; ?>
        </div>
        <?php } ?>
    
    <?php } ?>

    <?php if ($method = $order->paymentMethod()) { ?>
        <div>
            <b>Method:</b> <?php echo $method->{'name'}; ?>
        </div>
    <?php } ?>
    
    <?php foreach ($order->payments as $key=>$payment) { ?>
    <div>
        <h4>Payment <?php echo $key+1; ?></h4>
        <div>Payment method(s) (if CC, last 4)</div>
        <div>Address (if different from primary)</div>
        <div>Amount paid via payment method</div>
    </div>
    <?php } ?>    
</div>

<hr/>

<h3>
    Items
</h3>

<table style="width: 100%;">
    <thead>
        <tr>
            <th colspan="2" style="text-align: left;">
            </th>
            <th style="text-align: right;">
                Subtotal
            </th>
        </tr>
    </thead>        
    
    <?php foreach ($order->items as $item) { ?>
    <tr>
        <td style="width: 75px;">
            <?php if (\Dsc\ArrayHelper::get($item, 'image')) { ?>
            <img style="width: 100%;" src="<?php echo $SCHEME . '://' . $HOST . $BASE; ?>/asset/thumb/<?php echo \Dsc\ArrayHelper::get($item, 'image'); ?>" alt="" />
            <?php } ?>
        </td>
        <td style="vertical-align: top;">
            <h4>
                <?php echo \Dsc\ArrayHelper::get($item, 'product.title'); ?>
                <?php if (\Dsc\ArrayHelper::get($item, 'attribute_title')) { ?>
                <div>
                    <small><?php echo \Dsc\ArrayHelper::get($item, 'attribute_title'); ?></small>
                </div>
                <?php } ?>
                <div>
                    <small>
                    <span class="quantity"><?php echo $quantity = \Dsc\ArrayHelper::get($item, 'quantity'); ?></span>
                    x
                    <span class="price">$<?php echo $price = \Dsc\ArrayHelper::get($item, 'price'); ?></span>
                    </small> 
                </div>
            </h4>
        </td>
        <td style="vertical-align: top; text-align: right;">
            <h4>
                $<?php echo $quantity * $price; ?>
            </h4>
        </td>
    </tr>        
    <?php } ?>
</div>