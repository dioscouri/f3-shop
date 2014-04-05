<div class="container">
    <?php if (empty($order->id)) { ?>
        <h1>Order not found.</h1>
    <?php } else { ?>
    
        <h1>Thank you for your order <br/> <small>Your order number is <a href="./shop/order/<?php echo $order->id; ?>"><?php echo $order->number; ?></a></small></h1>
        
        <p>
            <a href="./shop/order/print/<?php echo $order->id; ?>">Print receipt.</a>
        </p>
        
        <?php /* ?>
        <p>
        [tracking pixels]
        </p>
        */ ?>
        
        <?php /* ?>
        <p>
        [upsells]
        </p>
        */ ?>
        
        <?php /* ?>
        <p>
        [newsletter signup w/one-click]
        </p>
        */ ?>
        
    <?php } ?>
</div>