<div class="container">
    <?php if (empty($order->id)) { ?>
        <h1>Order not found.</h1>
    <?php } else { ?>
    
        <h1>Your purchase is complete. <small>Order #<?php echo $order->number; ?></small></h1>
        
        <div>
        [Order details]
        </div>
        
        <div>
        [tracking pixels]
        </div>
        
    <?php } ?>
</div>