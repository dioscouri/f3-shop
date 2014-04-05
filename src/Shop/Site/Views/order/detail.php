<div class="container order-detail">

    <ol class="breadcrumb">
        <li>
            <a href="./shop/account">My Account</a>
        </li>
        <li>
            <a href="./shop/orders">My Orders</a>
        </li>
        <li class="active">Order Detail</li>
    </ol>

    <div class="form-group">
        <legend>
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-9">
                    <small>Summary</small>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-3">
                    <div class="pull-right">
                        <a href="./shop/order/print/<?php echo $item->id; ?>"><small>Printable version</small></a>
                    </div>
                </div>
            </div>
        </legend>
        
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-6">
                <div><label>Order placed:</label><?php echo $item->{'metadata.created.local'}; ?></div>
                <div><label>Order total:</label><?php echo $item->{'grand_total'}; ?></div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-6">
                <div><label>Order number:</label><?php echo $item->{'number'}; ?></div>
                <div><label>Order status:</label><?php echo $item->{'status'}; ?></div>
            </div>
        </div>        
    </div>
    
    <div class="form-group">
        <legend>
            <small>Shipping Information</small>
        </legend>
        <?php // TODO Eventually, do this for each shipment in the order ?>
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-6">
                <div><label>Order placed:</label><?php echo $item->{'metadata.created.local'}; ?></div>
                <div><label>Order total:</label><?php echo $item->{'grand_total'}; ?></div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-6">
                <div><label>Order number:</label><?php echo $item->{'number'}; ?></div>
                <div><label>Order status:</label><?php echo $item->{'status'}; ?></div>
            </div>
        </div>
    </div>
    
    <div class="form-group">
        <legend>
            <small>Payment Information</small>
        </legend>
        <div class="row">
            <div class=""></div>
        </div>    
    </div>
    
</div>