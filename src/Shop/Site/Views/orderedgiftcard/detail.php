<p>Here is your gift card!</p>
<p><?php echo $giftcard->code; ?>
<p>Balance: <?php echo \Shop\Models\Currency::format( $giftcard->balance() ) ?></p>
<p>[Print]</p>
<p>[Email]</p>
<p>[Save to My Account]</p>
<p>[Start Shopping button]</p>