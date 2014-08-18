<h3 class="clearfix">
    Create Order from Cart <small>Step 2 of 2</small>
    <span class="pull-right">
        <small><i class="fa fa-chevron-left"></i></small>
        <a href="./admin/shop/cart/create-order/<?php echo $cart->id; ?>"><small>Back to Step 1</small></a>
    </span>    
</h3>

<form action="./admin/shop/cart/create-order/<?php echo $cart->id; ?>" method="post" id="checkout-payment-form">

    <div id="checkout-shipping-summary" class="panel panel-default">
        <?php if ($cart->shippingRequired() && (!$cart->shippingMethod() || !$cart->validShippingAddress())) { ?>
            <?php \Base::instance()->reroute('/admin/shop/cart/create-order/' . $cart->id); ?>
        <?php } ?>
        <div class="panel-heading">
            <h4 class="panel-title">Shipping Summary
            <a class="pull-right" href="./admin/shop/cart/create-order/<?php echo $cart->id; ?>"><small>Edit Shipping Info</small></a>
            </h4>
        </div>
        <div class="panel-body">        
        <?php if ($cart->{'checkout.shipping_address'}) { ?>
            <address>
                <?php echo $cart->{'checkout.shipping_address.name'}; ?><br/>
                <?php echo $cart->{'checkout.shipping_address.line_1'}; ?><br/>
                <?php echo !empty($cart->{'checkout.shipping_address.line_2'}) ? $cart->{'checkout.shipping_address.line_2'} . '<br/>' : null; ?>
                <?php echo $cart->{'checkout.shipping_address.city'}; ?> <?php echo $cart->{'checkout.shipping_address.region'}; ?> <?php echo $cart->{'checkout.shipping_address.postal_code'}; ?><br/>
                <?php echo $cart->{'checkout.shipping_address.country'}; ?><br/>
            </address>
            <?php if (!empty($cart->{'checkout.shipping_address.phone_number'})) { ?>
            <div>
                <label>Phone:</label> <?php echo $cart->{'checkout.shipping_address.phone_number'}; ?>
            </div>
            <?php } ?>
        
        <?php } ?>
        
        <?php if ($method = $cart->shippingMethod()) { ?>
            <div>
                <label>Method:</label> <?php echo $method->{'name'}; ?> &mdash; $<?php echo $method->total(); ?>
            </div>
        <?php } ?>
        <?php if ($cart->{'checkout.order_comments'}) { ?>
            <div>
                <label>Comments:</label>
                <?php echo $cart->{'checkout.order_comments'}; ?>
            </div>
        <?php } ?>
        </div>
    </div>
    
    <?php echo $this->renderView('Shop/Admin/Views::carts/billing_forms.php'); ?>

    <div id="checkout-payment-methods" class="panel panel-default">
        
        <div class="panel-heading">
            <h4 class="panel-title">Payment
            <span class="pull-right">
                <a href="./admin/shop/cart/create-order-payment/<?php echo $cart->id; ?>"><small>Perform Payment Authorization</small></a>
            </span>
            </h4>
        </div>
        <div class="panel-body">
            <?php
            /* 
            $order->financial_status = \Shop\Constants\OrderFinancialStatus::paid;
            $order->payment_method_id = $this->identifier;
            $order->payment_method_result = $purchase_data;
            $order->payment_method_validation_result = $data;
            $order->payment_method_status = !empty($purchase_data['PAYMENTINFO_0_PAYMENTSTATUS']) ? $purchase_data['PAYMENTINFO_0_PAYMENTSTATUS'] : null;
            $order->payment_method_auth_id = !empty($purchase_data['TOKEN']) ? $purchase_data['TOKEN'] : null;
            $order->payment_method_tran_id = $purchase_response->getTransactionReference();
            */
            ?>
        
            <div class="form-group">
                <label>Payment Gateway</label>
                <?php $payment_methods = (new \Shop\Models\PaymentMethods)->setState('filter.enabled', true)->setState('filter.configured', true)->getList(); ?>
                <select name="order[payment_method_id]" class="form-control" required data-required="true">
                <?php foreach ($payment_methods as $payment_method) { ?>
                    <option value="<?php echo $payment_method->identifier; ?>"><?php echo $payment_method->title; ?></option>
                <?php } ?>
                </select>
            </div>
            
            <div class="form-group">
                <label>Order Financial Status</label>
                <select name="order[financial_status]" class="form-control">
                <?php foreach (\Shop\Constants\OrderFinancialStatus::fetch() as $fin_status) { ?>
                    <option value="<?php echo $fin_status; ?>"><?php echo $fin_status; ?></option>
                <?php } ?>
                </select>
            </div>
            
            <div class="form-group">
                <label>Payment Method Status</label>
                <input type="text" name="order[payment_method_status]" class="form-control" />
            </div>            
            
            <div class="form-group">
                <label>Payment Method Authorization ID</label>
                <input type="text" name="order[payment_method_auth_id]" class="form-control" />
            </div>
            
            <div class="form-group">
                <label>Payment Method Transaction ID</label>
                <input type="text" name="order[payment_method_tran_id]" class="form-control" />
            </div>

            <div class="form-group">
                <label>Payment Method Result</label>
                <textarea class="form-control" name="order[payment_method_result]"></textarea>
            </div>
            
            <div class="form-group">
                <label>Payment Method Validation Result</label>
                <textarea class="form-control" name="order[payment_method_validation_result]"></textarea>
            </div>
            
        </div>
    </div>

    <?php echo $this->renderView('Shop/Site/Views::checkout/before_submit_button.php'); ?>
    
    <div class="input-group form-group">
        <button id="submit-order" type="submit" class="btn btn-primary btn-lg">Submit Order</button>
        <?php \Dsc\System::instance()->get('session')->set('shop.checkout.redirect', '/admin/shop/cart/create-order-confirmation'); ?>
        <?php \Dsc\System::instance()->get('session')->set('shop.checkout.redirect_fail', '/admin/shop/cart/create-order-payment-manually/' . $cart->id); ?>
        <p class="help-block">This will save the order to the database without authorizing payment with the selected payment gateway.</p>
    </div>

    <?php echo $this->renderView('Shop/Site/Views::checkout/after_submit_button.php'); ?>
    
</form>