<div class="clearfix">
    <div class="pull-right">
        <a class="btn btn-default" href="./admin/shop/orders">Close</a>
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
                            <label>Status:</label>
                            <span><?php echo $item->{'status'}; ?></span>
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
                    <?php if ($method = $item->paymentMethod()) { ?>
                        <div>
                            <label>Method:</label> <?php echo $method->{'name'}; ?>
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
                
                <?php foreach ($item->items as $orderitem) { ?>
                <div class="row">
                    <div class="hidden-xs hidden-sm col-md-2">
                        <?php if (\Dsc\ArrayHelper::get($orderitem, 'image')) { ?>
                        <img class="img-responsive" src="./asset/thumb/<?php echo \Dsc\ArrayHelper::get($orderitem, 'image'); ?>" alt="" />
                        <?php } ?>
                    </div>
                    <div class="col-xs-10 col-sm-10 col-md-8">
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
                    <div class="col-xs-2 col-sm-2 col-md-2">
                        <div class="pull-right"><?php echo \Shop\Models\Currency::format( $quantity * $price ); ?></div>
                    </div>
                </div>        
                <?php } ?>
            </div>
        </div>
        
    </div>
    <div class="col-md-3">
        <h5>Actions to perform on this order</h5>
        <ul class="list-group">
            <li class="list-group-item">Cras justo odio</li>
            <li class="list-group-item">Dapibus ac facilisis in</li>
            <li class="list-group-item">Morbi leo risus</li>
            <li class="list-group-item">Porta ac consectetur ac</li>
            <li class="list-group-item">Vestibulum at eros</li>
        </ul>
    </div>
</div>