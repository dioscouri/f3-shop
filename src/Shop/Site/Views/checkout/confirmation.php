<div class="container">
    <?php if (empty($order->id)) { ?>
        <h1>Order not found.</h1>
    <?php } else { ?>
    
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-9">
                <h1>
                    Thank you for your order <br/> 
                    <small>Your order number is <a href="/shop/order/<?php echo $order->id; ?>"><?php echo $order->number; ?></a>.</small>
                </h1>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-3">
                <h3 class="pull-right">
                    <a href="/shop/order/print/<?php echo $order->id; ?>">Print receipt</a>
                </h3>            
            </div>
        </div>
        
        <?php /* ?>
        <p>
        [tracking pixels]
        </p>
        */ ?>
        
        <?php /* ?>
        <p>
        [upsells of related products]
        </p>
        */ ?>
        
        <?php /* ?>
        <p>
        [upsells with "false urgency"]
        </p>
        */ ?>
        
        <?php /* ?>
        <p>
        [newsletter signup w/one-click]
        </p>
        */ ?>
        
    <?php } ?>
</div>