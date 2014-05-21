<?php $link = $SCHEME . '://' . $HOST . $BASE . '/shop/giftcard/' . $giftcard->id . '/' . $giftcard->token; ?>

<p>Thank you for ordering a gift card!</p>
<p>Your gift card has a balance of <?php echo \Shop\Models\Currency::format( $giftcard->balance() ) ?>.</p>
<p>To view, print, or share your gift card, please click here: <a href="<?php echo $link; ?>"><?php echo $link; ?></a></p>
<p>Thanks again!</p>