<?php $order_link = $SCHEME . '://' . $HOST . $BASE . '/shop/order/' . $order->id; ?>
<?php $print_link = $SCHEME . '://' . $HOST . $BASE . '/shop/order/print/' . $order->id; ?>

Thank you for your order!

To view your order online, open this URL in your browser: <?php echo $order_link; ?> 

Complete details of your order are below.

Thanks again!

-------------

View a printable version here: <?php echo $print_link; ?> 

Order placed: <?php echo (new \DateTime($order->{'metadata.created.local'}))->format('F j, Y'); ?> 
Order total: <?php echo \Shop\Models\Currency::format( $order->{'grand_total'} ); ?> 
Order #<?php echo $order->{'number'}; ?> 
Order status: <?php echo $order->{'status'}; ?> 

-------------

<?php if ($order->{'shipping_required'}) { ?>
Shipping Information:

<?php if ($order->{'shipping_address'}) { ?>
    
<?php echo $order->{'shipping_address.name'}; ?> 
<?php echo $order->{'shipping_address.line_1'}; ?> 
<?php echo !empty($order->{'shipping_address.line_2'}) ? $order->{'shipping_address.line_2'} . ' ' : null; ?> 
<?php echo $order->{'shipping_address.city'}; ?> <?php echo $order->{'shipping_address.region'}; ?> <?php echo $order->{'shipping_address.postal_code'}; ?> 
<?php echo $order->{'shipping_address.country'}; ?> 
    
<?php if (!empty($order->{'shipping_address.phone_number'})) { ?>
Phone: <?php echo $order->{'shipping_address.phone_number'}; ?> 
<?php } ?>

<?php } ?>

<?php if ($method = $order->shippingMethod()) { ?>
Method: <?php echo $method->{'name'}; ?> - $<?php echo $method->total(); ?> 
<?php } ?>

<?php foreach ($order->shipments as $key=>$shipment) { ?>
--- 
Shipment <?php echo $key+1; ?> 
Shipping Vendor (UPS/USPS/Fedex/etc) 
Tracking number + link 
Address 
Items in shipment
<?php } ?>
<?php } ?>

-------------

Payment Information:

<?php if ($order->{'billing_address'}) { ?>

<?php echo $order->{'billing_address.name'}; ?> 
<?php echo $order->{'billing_address.line_1'}; ?> 
<?php echo !empty($order->{'billing_address.line_2'}) ? $order->{'billing_address.line_2'} . '<br/>' : null; ?> 
<?php echo $order->{'billing_address.city'}; ?> <?php echo $order->{'billing_address.region'}; ?> <?php echo $order->{'billing_address.postal_code'}; ?> 
<?php echo $order->{'billing_address.country'}; ?> 

<?php if (!empty($order->{'billing_address.phone_number'})) { ?>
Phone: <?php echo $order->{'billing_address.phone_number'}; ?> 
<?php } ?>

<?php } ?>

<?php if ($method = $order->paymentMethod()) { ?>
Method: <?php echo $method->{'name'}; ?> 
<?php } ?>
    
<?php foreach ($order->payments as $key=>$payment) { ?>
---
Payment <?php echo $key+1; ?> 
Payment method(s) (if CC, last 4) 
Address (if different from primary) 
Amount paid via payment method 
<?php } ?>    

-------------

Items:

<?php foreach ($order->items as $item) { ?>
--- 

<?php echo \Dsc\ArrayHelper::get($item, 'product.title'); ?> 
<?php if (\Dsc\ArrayHelper::get($item, 'attribute_title')) { ?><?php echo \Dsc\ArrayHelper::get($item, 'attribute_title'); ?><?php } ?> 
<?php echo $quantity = \Dsc\ArrayHelper::get($item, 'quantity'); ?> x <?php echo \Shop\Models\Currency::format( $price = \Dsc\ArrayHelper::get($item, 'price') ); ?>    
Subtotal: <?php echo \Shop\Models\Currency::format( $quantity * $price ); ?> 
<?php } ?>