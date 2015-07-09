<?php $link = $SCHEME . '://' . $HOST . $BASE . '/shop'; ?>

Hello <?php echo $data['recipient_name']; ?>, 

You have received a <?php echo \Shop\Models\Currency::format( $giftcard->balance() ) ?> gift card from <?php echo $data['sender_name']; ?>. 
<?php if ($data['message']) { ?>

<?php echo $data['message']; ?> 

<?php } ?> 

----- 

Code: <?php echo $giftcard->code; ?> 

----- 

Start shopping now by opening this link in your browser: <?php echo $link; ?> 
