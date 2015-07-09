<?php $link = $SCHEME . '://' . $HOST . $BASE . '/shop'; ?>

<p>Hello <?php echo $data['recipient_name']; ?>,</p>
<p>You have received a <?php echo \Shop\Models\Currency::format( $giftcard->balance() ) ?> gift card from <?php echo $data['sender_name']; ?>.</p>
<?php if ($data['message']) { ?>
<p><?php echo $data['message']; ?></p>
<?php } ?>
<hr />
<p>Code: <?php echo $giftcard->code; ?></p>
<hr />
<p><a href="<?php echo $link; ?>">Start shopping now!</a></p>