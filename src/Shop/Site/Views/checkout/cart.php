<h4 class="margin-top">Summary</h4>
<div class="total-box">
    <div class="table-responsive">
        <table class="table">
            <tbody>
                <tr>
                    <td><div class="strong">Subtotal:</div></td>
                    <td><div class="price"><?php echo \Shop\Models\Currency::format( $cart->subtotal() ); ?></div></td>
                </tr>
                <?php if ($user_coupons_nonshipping = $cart->userCoupons(false)) { 
                    \Dsc\System::instance()->get( 'session' )->set( 'site.removecoupon.redirect', '/shop/checkout' ); ?>
                    <?php foreach ($user_coupons_nonshipping as $coupon) { ?>
                        <tr class="coupon">
                            <td>
                                <div class="row">
                                    <div class="col-xs-8"><div class="strong">Coupon:<br/><?php echo $coupon['code']; ?></div></div>
                                    <div class="col-xs-4"><a href="./shop/cart/removeCoupon/<?php echo $coupon['code']; ?>" class="btn btn-default custom-button"><i class="glyphicon glyphicon-remove"></i></a></div>
                                </div>
                            </td>
                            <td class="col-xs-6">
                                <div class="price">-<?php echo \Shop\Models\Currency::format( \Dsc\ArrayHelper::get($coupon, 'amount') ); ?></div>
                            </td>                            
                        </tr>
                    <?php } ?>
                <?php } ?>
                
                <?php if ($autocoupons_nonshipping_discount = $cart->autoDiscountTotal(true)) { ?>
                    <tr class="auto_discount">
                        <td>
                            <div class="strong">Auto Discount:</div>
                        </td>
                        <td class="col-xs-6">
                            <div class="price">-<?php echo \Shop\Models\Currency::format( $autocoupons_nonshipping_discount ); ?></div>
                        </td>                            
                    </tr>
                <?php } ?>                            
                                
                <tr>
                    <td><div class="strong">
                            Shipping:
                        </div></td>
                    <td><div class="price">
                        <?php if (!$shippingMethod = $cart->shippingMethod()) {
                            echo \Shop\Models\Currency::format( $cart->shippingEstimate() );
                            echo ' <small>(est)</small>';
                        } else {
                        	echo \Shop\Models\Currency::format( $shippingMethod->total() );
                        }
                        ?>
                    </div></td>
                </tr>
                
                <?php if ($user_coupons_shipping = $cart->userCoupons(true)) { 
                    \Dsc\System::instance()->get( 'session' )->set( 'site.removecoupon.redirect', '/shop/checkout' ); ?>
                    <?php foreach ($user_coupons_shipping as $coupon) { ?>
                        <tr class="coupon">
                            <td>
                                <div class="row">
                                    <div class="col-xs-8"><div class="strong">Shipping Coupon:<br/><?php echo $coupon['code']; ?></div></div>
                                    <div class="col-xs-4"><a href="./shop/cart/removeCoupon/<?php echo $coupon['code']; ?>" class="btn btn-default custom-button"><i class="glyphicon glyphicon-remove"></i></a></div>
                                </div>
                            </td>
                            <td class="col-xs-6">
                                <div class="price">-<?php echo \Shop\Models\Currency::format( \Dsc\ArrayHelper::get($coupon, 'amount') ); ?></div>
                            </td>                            
                        </tr>
                    <?php } ?>
                <?php } ?>
                
                <?php if ($autocoupons_shipping_discount = $cart->autoShippingDiscountTotal()) { ?>
                    <tr class="auto_discount">
                        <td>
                            <div class="strong">Shipping Discount:</div>
                        </td>
                        <td class="col-xs-6">
                            <div class="price">-<?php echo \Shop\Models\Currency::format( $autocoupons_shipping_discount ); ?></div>
                        </td>                            
                    </tr>
                <?php } ?>
                
                <tr>
                    <td><div class="strong">
                            <span data-toggle="tooltip" data-placement="top" title="Taxable amount: <?php echo \Shop\Models\Currency::format( $cart->taxableTotal() ); ?>">
                            Tax:
                            </span>
                        </div></td>
                    <td><div class="price">
                        <span data-toggle="tooltip" data-placement="top" title="Taxable amount: <?php echo \Shop\Models\Currency::format( $cart->taxableTotal() ); ?>">
                        <?php if (!$shippingMethod = $cart->shippingMethod()) {
                            echo \Shop\Models\Currency::format( $cart->taxEstimate() );
                            echo ' <small>(est)</small>';
                        } else {
                        	echo \Shop\Models\Currency::format( $cart->taxTotal() );
                        }
                        ?>
                        </span>                    
                    </div></td>
                </tr>
                <?php if ($giftcards = $cart->giftcards) { \Dsc\System::instance()->get( 'session' )->set( 'site.removegiftcard.redirect', '/shop/checkout' ); ?>
                    <?php foreach ($giftcards as $giftcard) { ?>
                        <tr class="giftcard">
                            <td>
                                <div class="row">
                                    <div class="col-xs-8"><div class="strong">Gift Card</div></div>
                                    <div class="col-xs-4"><a href="./shop/cart/removeGiftCard/<?php echo $giftcard['code']; ?>" class="btn btn-default custom-button"><i class="glyphicon glyphicon-remove"></i></a></div>
                                </div>
                                <small><?php echo $giftcard['code']; ?></small>
                            </td>
                            <td class="col-xs-6">
                                <div class="price">-<?php echo \Shop\Models\Currency::format( \Dsc\ArrayHelper::get($giftcard, 'amount') ); ?></div>
                            </td>                            
                        </tr>
                    <?php } ?>
                <?php } ?>                                            
            </tbody>
            <tfoot>
                <td><div class="strong">
                        Total<?php if (!$shippingMethod = $cart->shippingMethod()) { ?> <small>(est)</small> <?php } ?>:
                    </div></td>
                <td><div class="price"><?php echo \Shop\Models\Currency::format( $cart->total() ); ?></div></td>
            </tfoot>
        </table>
    </div>
</div>

<?php if (empty($cart->userCoupons())) { \Dsc\System::instance()->get( 'session' )->set( 'site.addcoupon.redirect', '/shop/checkout' ); ?>
<div class="margin-top">
    <div class="row">
        <div class="col-md-12">
            <div id="coupon">
                <form class="form" role="form" action="./shop/cart/addCoupon" method="post">
                    <div class="form-group">
                        <div class="input-group">
                            <input type="text" name="coupon_code" class="form-control" id="inputCouponCode" placeholder="Have a Coupon?">
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

<?php if (empty($giftcards)) { \Dsc\System::instance()->get( 'session' )->set( 'site.addgiftcard.redirect', '/shop/checkout' ); ?>
<div class="margin-top">
    <div class="row">
        <div class="col-md-12">
            <div id="coupon">
                <form class="form" role="form" action="./shop/cart/addGiftCard" method="post">
                    <div class="form-group">
                        <div class="input-group">
                            <input type="text" name="giftcard_code" class="form-control" id="inputGiftCardCode" placeholder="Have a Gift Card?">
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

<div class="table-responsive checkout-cart margin-top">
    <table class="table">
        <thead>
            <tr>
                <th colspan="3">Items</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($cart->items as $key=>$item) { ?>
            <tr>
                <td class="checkout-cart-image">
                    <figure>
                        <?php if (\Dsc\ArrayHelper::get($item, 'image')) { ?>
                        <img class="img-responsive" src="./asset/thumb/<?php echo \Dsc\ArrayHelper::get($item, 'image'); ?>" alt="" />
                        <?php } ?>
                    </figure>
                </td>
                <td class="checkout-cart-product">
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
                    
                </td>
                <td>
                    <div class="subtotal"><?php echo \Shop\Models\Currency::format( $cart->calcItemSubtotal( $item ) ); ?></div>
                </td>
            </tr>
        <?php } ?>
        
        </tbody>
    </table>
</div>

<script>
jQuery(document).ready(function(){
	jQuery('[data-toggle="tooltip"]').tooltip();
});
</script>