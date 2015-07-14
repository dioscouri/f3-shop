<?php $order_link = $SCHEME . '://' . $HOST . $BASE . '/shop/order/' . $order->id; ?>
<?php $print_link = $SCHEME . '://' . $HOST . $BASE . '/shop/order/print/' . $order->id; ?>

<p>Thank you for your order!</p>
<p>To view your order online, click here: <a href="<?php echo $order_link; ?>"><?php echo $order_link; ?></a></p>
<p>Complete details of your order are below.</p>
<p>Thanks again!</p>

<hr/> 

<h3>
    Summary
</h3>
<div>    
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
    <div>
        <label>Status:</label>
        <span><?php echo $order->{'status'}; ?></span>
    </div>                        
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

</div>
<?php } ?>

<hr/>

<h3>
    Payment Information
</h3>

<div>
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
    
    <p class="order-totals">
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
    </p>
    
    <?php if ($userCoupons = $order->userCoupons()) { ?>
    <p>
        <label class="strong">Coupons applied to this order:</label>
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
    </p>
    <?php } ?>
    
    <?php if ($autoCoupons = $order->autoCoupons()) { ?>
    <p>
        <label class="strong">Automatic Coupons applied to this order:</label>
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
    </p>
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
                    <span class="price"><?php echo \Shop\Models\Currency::format( $price = \Dsc\ArrayHelper::get($item, 'price') ); ?></span>
                    </small> 
                </div>
            </h4>
        </td>
        <td style="vertical-align: top; text-align: right;">
            <h4>
                <?php echo \Shop\Models\Currency::format( $quantity * $price ); ?>
            </h4>
        </td>
    </tr>        
    <?php } ?>
</div>