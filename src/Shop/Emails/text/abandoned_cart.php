<?php $view_link = $SCHEME . '://' . $HOST . $BASE . '/shop/cart/'.(string)$cart->id.'?email=1&user_id='.(string)$user->id.'&idx='.$idx.'&auto_login_token='.$token; ?>
<?php echo trim( 'Hi ' . $user->fullName() ); ?>, 

<?php echo $notification['text']['plain']; ?> 

Your cart contains:
<?php foreach ($cart->items as $item) { ?>
--- 

<?php echo \Dsc\ArrayHelper::get($item, 'product.title'); ?> 
<?php if (\Dsc\ArrayHelper::get($item, 'attribute_title')) { ?><?php echo \Dsc\ArrayHelper::get($item, 'attribute_title'); ?><?php } ?> 
<?php echo $quantity = \Dsc\ArrayHelper::get($item, 'quantity'); ?> x <?php echo \Shop\Models\Currency::format( $price = \Dsc\ArrayHelper::get($item, 'price') ); ?> 
Subtotal: <?php echo \Shop\Models\Currency::format( $quantity * $price ); ?> 
<?php } ?> 

To complete your purchase, open this URL in your browser:  
<?php echo $view_link; ?>