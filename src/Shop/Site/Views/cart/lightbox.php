
<?php if (empty($cart->items)) { ?>
    <h2>Your cart is empty!</h2>
<?php } ?>

<?php if (!empty($cart->items)) { $count = count($cart->items); $qty= 0; ?>
<div class="cart-lightbox-container">
    <div class="cart-lightbox-body">
        <?php foreach ($cart->items as $key=>$item) { ?>
        <?php $qty = $qty + \Dsc\ArrayHelper::get($item, 'quantity');?>
        <div class="cart-item">
            <div class="row">        
                <div class="col-xs-4 col-md-2">
                    <?php if (\Dsc\ArrayHelper::get($item, 'image')) { ?>
                    <div class="cart-item-image">
                    <img src="./asset/thumb/<?php echo \Dsc\ArrayHelper::get($item, 'image'); ?>" alt="" class="img-responsive" />
                    </div>
                    <?php } ?>
                </div>
                <div class="col-xs-8 col-md-8">
                    <h4>
                        <?php echo \Dsc\ArrayHelper::get($item, 'product.title'); ?>
                        <?php if (\Dsc\ArrayHelper::get($item, 'attribute_title')) { ?>
                        <div><small><?php echo \Dsc\ArrayHelper::get($item, 'attribute_title'); ?></small></div>
                        <?php } ?>                                            
                    </h4>
                    <div class="price hidden-sm hidden-md hidden-lg"><?php echo \Shop\Models\Currency::format( $cart->calcItemSubtotal( $item ) ); ?></div>
                </div>
                <div class="col-xs-12 col-md-2">
                    <div class="price hidden-xs"><?php echo \Shop\Models\Currency::format( $cart->calcItemSubtotal( $item ) ); ?></div>
                </div>
            </div>

        </div>
        <?php } ?>
    </div>
    <div class="cart-lightbox-footer">
        <div class="row">
            <div class="col-xs-6 col-md-6">
                <h4>
                <span class="cart-items">
                            Items: <?php echo $count; ?>
                </span>
    
                <span class="cart-quantity"> Qty:  <?php echo $qty; ?></span>
                </h4>   
                              
            </div>
            <div class="col-xs-6 col-md-6">
                <h4 class="pull-right">
                Subtotal: <?php echo \Shop\Models\Currency::format( $cart->subtotal() ); ?>
                </h4>
            </div>            
        </div>

    </div>
</div>
        
<?php } ?>
