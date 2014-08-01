<?php $view_link = $SCHEME . '://' . $HOST . $BASE . '/shop/cart?email=1'; ?>
Hi <?php echo $user->fullName(); ?>

<?php echo $notification['plain']; ?>

Your cart contains:
<?php foreach ($cart->items as $item) { ?>
--- 

<?php echo \Dsc\ArrayHelper::get($item, 'product.title'); ?> 
<?php if (\Dsc\ArrayHelper::get($item, 'attribute_title')) { ?><?php echo \Dsc\ArrayHelper::get($item, 'attribute_title'); ?><?php } ?> 
<?php echo $quantity = \Dsc\ArrayHelper::get($item, 'quantity'); ?> x <?php echo \Shop\Models\Currency::format( $price = \Dsc\ArrayHelper::get($item, 'price') ); ?>    
Subtotal: <?php echo \Shop\Models\Currency::format( $quantity * $price ); ?> 
<?php } ?>

If you want to see your cart, click on the following link:
<?php echo $view_link; ?>