<?php echo trim( 'Hi ' . $user->fullName() ); ?>, 

Thank you for your recent order! 
 
Please take a moment to let us know what you think of your purchases. 
 
<?php foreach ($products as $item) { ?>
--- 

<?php echo \Dsc\ArrayHelper::get($item, 'product.title'); ?> 
<?php if (\Dsc\ArrayHelper::get($item, 'attribute_title')) { ?><?php echo \Dsc\ArrayHelper::get($item, 'attribute_title'); ?><?php } ?> 
<?php } ?> 
 
To review any of your recent and past purchases, open this URL in your browser: 
<?php echo $SCHEME . '://' . $HOST . $BASE; ?>/shop/account/product-reviews 
 
Thanks!