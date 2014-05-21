<?php $link = $SCHEME . '://' . $HOST . $BASE . '/shop/giftcard/' . $giftcard->id . '/' . $giftcard->token; ?>

Thank you for ordering a gift card! 

Your gift card has a balance of <?php echo \Shop\Models\Currency::format( $giftcard->balance() ) ?>. 

To view, print, or share your gift card, please open this URL in your browser: <?php echo $link; ?> 

Thanks again! 