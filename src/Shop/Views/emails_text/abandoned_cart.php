<?php $view_link = $SCHEME . '://' . $HOST . $BASE . '/shop/cart/'.(string)$cart->id.'?email=1&user_id='.(string)$user->id.'&idx='.$idx.'&auto_login_token='.$token; ?>
Hi <?php echo $user->fullName(); ?>

<?php echo $notification['text']['plain']; ?>

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