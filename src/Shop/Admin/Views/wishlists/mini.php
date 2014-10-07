<div class="total-box panel panel-default">
    <div class="panel-heading">
        <h4 class="panel-title">Summary</h4>
    </div>
    <div class="list-group">
        <div class="list-group-item">
            <div class="row">
                <div class="col-xs-6">
                    <label>Subtotal:</label>
                </div>
                <div class="col-xs-6">
                    <div class="price"><?php echo \Shop\Models\Currency::format( $cart->subtotal() ); ?></div>
                </div>                
            </div>
        </div>
        <?php if ($user_coupons_nonshipping = $cart->userCoupons(false)) { 
            \Dsc\System::instance()->get( 'session' )->set( 'site.removecoupon.redirect', '/shop/checkout' ); ?>
            <?php foreach ($user_coupons_nonshipping as $coupon) { ?>
                <div class="list-group-item coupon">
                    <div class="row">
                        <div class="col-xs-6">
                            <div class="row">
                                <div class="col-xs-8"><div class="strong">Coupon:<br/><?php echo $coupon['code']; ?></div></div>
                                <div class="col-xs-4"><a href="./shop/cart/removeCoupon/<?php echo $coupon['code']; ?>" class="btn btn-default custom-button"><i class="glyphicon glyphicon-remove"></i></a></div>
                            </div>
                        </div>
                        <div class="col-xs-6">
                            <div class="price">-<?php echo \Shop\Models\Currency::format( \Dsc\ArrayHelper::get($coupon, 'amount') ); ?></div>
                        </div>                
                    </div>
                </div>
            <?php } ?>
        <?php } ?>
        
        <?php if ($autocoupons_nonshipping_discount = $cart->autoDiscountTotal(true)) { ?>
            <div class="list-group-item auto-discount">
                <div class="row">
                    <div class="col-xs-6">
                        <label>Discount:</label>
                    </div>
                    <div class="col-xs-6">
                        <div class="price">-<?php echo \Shop\Models\Currency::format( $autocoupons_nonshipping_discount ); ?></div>
                    </div>                
                </div>
            </div>
        <?php } ?>                            
                        
        <div class="list-group-item">
            <div class="row">
                <div class="col-xs-6">
                    <label>Shipping:</label>
                </div>
                <div class="col-xs-6">
                    <div class="price">
                        <?php if (!$shippingMethod = $cart->shippingMethod()) {
                            echo \Shop\Models\Currency::format( $cart->shippingEstimate() );
                            echo ' <small>(est)</small>';
                        } else {
                        	echo \Shop\Models\Currency::format( $shippingMethod->total() );
                        }
                        ?>                    
                    </div>
                </div>                
            </div>        
        </div>
        
        <?php if ($user_coupons_shipping = $cart->userCoupons(true)) { 
            \Dsc\System::instance()->get( 'session' )->set( 'site.removecoupon.redirect', '/shop/checkout' ); ?>
            <?php foreach ($user_coupons_shipping as $coupon) { ?>
                <div class="list-group-item coupon">
                    <div class="row">
                        <div class="col-xs-6">
                            <div class="row">
                                <div class="col-xs-8"><div class="strong">Shipping Coupon:<br/><?php echo $coupon['code']; ?></div></div>
                                <div class="col-xs-4"><a href="./shop/cart/removeCoupon/<?php echo $coupon['code']; ?>" class="btn btn-default custom-button"><i class="glyphicon glyphicon-remove"></i></a></div>
                            </div>
                        </div>
                        <div class="col-xs-6">
                            <div class="price">-<?php echo \Shop\Models\Currency::format( \Dsc\ArrayHelper::get($coupon, 'amount') ); ?></div>
                        </div>                
                    </div>
                </div>
            <?php } ?>
        <?php } ?>
        
        <?php if ($autocoupons_shipping_discount = $cart->autoShippingDiscountTotal()) { ?>
            <div class="list-group-item auto-discount">
                <div class="row">
                    <div class="col-xs-6">
                        <label>Shipping Discount:</label>
                    </div>
                    <div class="col-xs-6">
                        <div class="price">-<?php echo \Shop\Models\Currency::format( $autocoupons_shipping_discount ); ?></div>
                    </div>                
                </div>            
            </div>
        <?php } ?>
        
        <?php if ($credit = $cart->creditTotal()) { ?>
            <div class="list-group-item auto-discount">
                <div class="row">
                    <div class="col-xs-6">
                        <label>Store Credit:</label>
                    </div>
                    <div class="col-xs-6">
                        <div class="price">-<?php echo \Shop\Models\Currency::format( $credit ); ?></div>
                    </div>                
                </div>            
            </div>
        <?php } ?>                
        
        <div class="list-group-item">
            <div class="row">
                <div class="col-xs-6">
                    <label>
                        <span data-toggle="tooltip" data-placement="top" title="Taxable amount: <?php echo \Shop\Models\Currency::format( $cart->taxableTotal() ); ?>">
                        Tax:
                        </span>                    
                    </label>
                </div>
                <div class="col-xs-6">
                    <div class="price">
                        <span data-toggle="tooltip" data-placement="top" title="Taxable amount: <?php echo \Shop\Models\Currency::format( $cart->taxableTotal() ); ?>">
                        <?php if (!$shippingMethod = $cart->shippingMethod()) {
                            echo \Shop\Models\Currency::format( $cart->taxEstimate() );
                            echo ' <small>(est)</small>';
                        } else {
                        	echo \Shop\Models\Currency::format( $cart->taxTotal() );
                        }
                        ?>
                        </span>
                    </div>
                </div>                
            </div>
        </div>
        
        <?php if ($giftcards = $cart->giftcards) { \Dsc\System::instance()->get( 'session' )->set( 'site.removegiftcard.redirect', '/shop/checkout' ); ?>
            <?php foreach ($giftcards as $giftcard) { ?>
                <div class="list-group-item giftcard">
                    <div class="row">
                        <div class="col-xs-6">
                            <div class="row">
                                <div class="col-xs-8"><div class="strong">Gift Card</div></div>
                                <div class="col-xs-4"><a href="./shop/cart/removeGiftCard/<?php echo $giftcard['code']; ?>" class="btn btn-default custom-button"><i class="glyphicon glyphicon-remove"></i></a></div>
                            </div>
                            <small><?php echo $giftcard['code']; ?></small>
                        </div>
                        <div class="col-xs-6">
                            <div class="price">-<?php echo \Shop\Models\Currency::format( \Dsc\ArrayHelper::get($giftcard, 'amount') ); ?></div>
                        </div>                
                    </div>
                </div>
            <?php } ?>
        <?php } ?>
        
        <div class="list-group-item">
            <div class="row">
                <div class="col-xs-6">
                    <label>Total<?php if (!$shippingMethod = $cart->shippingMethod()) { ?> <small>(est)</small> <?php } ?>:</label>
                </div>
                <div class="col-xs-6">
                    <div class="price"><?php echo \Shop\Models\Currency::format( $cart->total() ); ?></div>
                </div>                
            </div>        
        </div>

    </div>
</div>

<?php if (empty($cart->userCoupons())) { \Dsc\System::instance()->get( 'session' )->set( 'addcoupon.redirect', '/admin/shop/cart/create-order/' . $cart->id ); ?>
<div class="margin-top">
    <div class="row">
        <div class="col-md-12">
            <div id="coupon">
                <form class="form" role="form" action="./admin/shop/cart/addCoupon/<?php echo $cart->id; ?>" method="post">
                    <div class="form-group">
                        <div class="input-group">
                            <input type="text" name="coupon_code" class="form-control" id="inputCouponCode" placeholder="Add a Coupon?">
                            <span class="input-group-btn">
                                <button class="btn btn-default" type="submit">Add</button>
                            </span>                  
                        </div>                          
                    </div>                                    
                </form>
            </div>
        </div>
    </div>
</div>
<?php } ?>

<?php if (empty($giftcards)) { \Dsc\System::instance()->get( 'session' )->set( 'addgiftcard.redirect', '/admin/shop/cart/create-order/' . $cart->id ); ?>
<div class="margin-top">
    <div class="row">
        <div class="col-md-12">
            <div id="coupon">
                <form class="form" role="form" action="./admin/shop/cart/addGiftCard/<?php echo $cart->id; ?>" method="post">
                    <div class="form-group">
                        <div class="input-group">
                            <input type="text" name="giftcard_code" class="form-control" id="inputGiftCardCode" placeholder="Add a Gift Card?">
                            <span class="input-group-btn">
                                <button class="btn btn-default" type="submit">Add</button>
                            </span>                  
                        </div>                          
                    </div>                                    
                </form>
            </div>
        </div>
    </div>
</div>           
<?php } ?>

<div class="checkout-cart panel panel-default">
    <div class="panel-heading">
        <h4 class="panel-title">Cart Items</h4>
    </div>
    <div class="list-group">
    <?php foreach ($cart->items as $key=>$item) { ?>
        <div class="list-group-item">
            <div class="row">
                <div class="col-xs-3 checkout-cart-image">
                    <figure>
                        <?php if (\Dsc\ArrayHelper::get($item, 'image')) { ?>
                        <img class="img-responsive" src="./asset/thumb/<?php echo \Dsc\ArrayHelper::get($item, 'image'); ?>" alt="" />
                        <?php } ?>
                    </figure>
                </div>
                <div class="col-xs-9 checkout-cart-product">
                    <div class="row">
                        <div class="col-xs-8">
                            <h4>
                                <?php echo \Dsc\ArrayHelper::get($item, 'product.title'); ?>
                                <?php if (\Dsc\ArrayHelper::get($item, 'attribute_title')) { ?>
                                <div>
                                    <small><?php echo \Dsc\ArrayHelper::get($item, 'attribute_title'); ?></small>
                                </div>
                                <?php } ?>                        
                            </h4>
                            <div class="details">
            
                            </div>
                            <div>
                                <span class="quantity"><?php echo \Dsc\ArrayHelper::get($item, 'quantity'); ?></span>
                                x
                                <span class="price"><?php echo \Shop\Models\Currency::format( \Dsc\ArrayHelper::get($item, 'price') ); ?></span> 
                            </div>                        
                        </div>
                        <div class="col-xs-4">
                            <div class="subtotal"><?php echo \Shop\Models\Currency::format( $cart->calcItemSubtotal( $item ) ); ?></div>
                        </div>                        
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>
    </div>
</div>

<script>
jQuery(document).ready(function(){
	jQuery('[data-toggle="tooltip"]').tooltip();
});
</script>