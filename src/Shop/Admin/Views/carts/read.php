<div class="row">
    <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
        <h1 class="page-title txt-color-blueDark">
            <i class="fa fa-table fa-fw "></i> Cart
            <span> > Details </span>
        </h1>
    </div>
    <div class="col-xs-12 col-sm-5 col-md-5 col-lg-8">
        <ul id="sparks" class="list-actions list-unstyled list-inline">
            <li>
                <a class="btn btn-default" href="./admin/shop/carts">Return to List</a>
            </li>
        </ul>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <h2>
            Customer: <?php echo $item->user()->fullName(); ?><br/>
            <?php echo $item->user()->email; ?>
            <small class="help-block">Cart ID: <?php echo $item->id; ?></small>
        </h2>        
    </div>
</div>

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
                            <span class="price"><?php echo \Shop\Models\Currency::format( $item->subTotal() ); ?></span>
                        </div>
                        <?php if ($item->discount_total - $item->shipping_discount_total > 0) { ?>
                        <div>
                            <label class="strong">Discount:</label>
                            <span class="price">-<?php echo \Shop\Models\Currency::format( $item->discount_total - $item->shipping_discount_total ); ?></span>
                        </div>
                        <?php } ?>                
                        <?php if ($item->shipping_total > 0) { ?>
                        <div>
                            <label class="strong">Shipping:</label>
                            <span class="price"><?php echo \Shop\Models\Currency::format( $item->shipping_total ); ?></span>
                        </div>
                        <?php } ?>
                        <?php if ($item->shipping_discount_total > 0) { ?>
                        <div>
                            <label class="strong">Shipping Discount:</label>
                            <span class="price">-<?php echo \Shop\Models\Currency::format( $item->shipping_discount_total ); ?></span>
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
                            <span class="price"><?php echo \Shop\Models\Currency::format( $item->total() ); ?></span>
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
                            <small></small>
                        </div>                        
                    </div>
                </legend>        
                
                <?php foreach ($item->items as $orderitem) { ?>
                <div class="list-group-item">
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
                                                    
                    </div>                        

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
        <p>
            <a class="btn btn-success" href="./admin/shop/cart/create-order/<?php echo $item->id; ?>">Create an order</a>
        </p>            
    </div>
</div>