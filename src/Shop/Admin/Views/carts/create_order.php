<div class="row">
    <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
        <h1 class="page-title txt-color-blueDark">
            <i class="fa fa-table fa-fw "></i> Cart
            <span> > Create Order </span>
        </h1>
    </div>
    <div class="col-xs-12 col-sm-5 col-md-5 col-lg-8">
        <ul id="sparks" class="list-actions list-unstyled list-inline">
            <li>
                <a class="btn btn-default" href="./admin/shop/cart/read/<?php echo $cart->id; ?>">Return to Cart Details</a>
            </li>        
            <li>
                <a class="btn btn-danger" href="./admin/shop/carts">Return to List</a>
            </li>
        </ul>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <h2>
            Customer: <?php echo $cart->user()->fullName(); ?><br/>
            <?php echo $cart->user()->email; ?>
            <small class="help-block">Cart ID: <?php echo $cart->id; ?></small>
        </h2>        
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <?php echo $this->renderView('Shop/Admin/Views::carts/shipping_forms.php'); ?>
    </div>
    
    <div class="col-md-4">
        <?php echo $this->renderView('Shop/Admin/Views::carts/mini.php'); ?>
    </div>
    
</div>