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

<?php } ?>

-------------

Payment Information:

<?php if (($method = $order->paymentMethod()) && $order->grand_total) { ?>
Method: <?php echo $method->{'name'}; ?> 

<?php } ?>
<?php if ($order->credit_total) { ?>  
Store Credit Applied: <?php echo $order->credit_total; ?> 
 
<?php } ?>

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

---

Subtotal: <?php echo \Shop\Models\Currency::format( $order->sub_total ); ?> 
<?php if ($order->discount_total - $order->shipping_discount_total > 0) { ?>
Discount: -<?php echo \Shop\Models\Currency::format( $order->discount_total - $order->shipping_discount_total ); ?> 
<?php } ?>                
<?php if ($order->shipping_total > 0) { ?>
Shipping: <?php echo \Shop\Models\Currency::format( $order->shipping_total ); ?> 
<?php } ?>
<?php if ($order->shipping_discount_total > 0) { ?>
Shipping Discount: -<?php echo \Shop\Models\Currency::format( $order->shipping_discount_total ); ?> 
<?php } ?>
<?php if ($order->tax_total > 0) { ?>
Tax: <?php echo \Shop\Models\Currency::format( $order->tax_total ); ?> 
<?php } ?>
<?php if ($order->giftcard_total > 0) { ?>
Giftcard: -<?php echo \Shop\Models\Currency::format( $order->giftcard_total ); ?> 
<?php } ?>
<?php if ($order->credit_total > 0) { ?>
Store Credit: -<?php echo \Shop\Models\Currency::format( $order->credit_total ); ?> 
<?php } ?>
Total: <?php echo \Shop\Models\Currency::format( $order->grand_total ); ?> 
 
<?php if ($userCoupons = $order->userCoupons()) { ?>
Coupons applied to this order: 
<?php foreach ($userCoupons as $coupon) { ?>
  <?php echo \Dsc\ArrayHelper::get( $coupon, 'code' ); ?>: <?php echo \Shop\Models\Currency::format( $price = \Dsc\ArrayHelper::get($coupon, 'amount') ); ?> 
 
<?php } ?>
<?php } ?>
<?php if ($autoCoupons = $order->autoCoupons()) { ?>
Automatic Coupons applied to this order: 
<?php foreach ($autoCoupons as $coupon) { ?>
  <?php echo \Dsc\ArrayHelper::get( $coupon, 'code' ); ?>: <?php echo \Shop\Models\Currency::format( $price = \Dsc\ArrayHelper::get($coupon, 'amount') ); ?> 
 
<?php } ?>
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