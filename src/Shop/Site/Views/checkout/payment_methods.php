<?php if (empty($cart->paymentMethods(true))) 
{
	?>
	<div class="form-group has-error">
	   <label class="control-label">No valid payment methods could be found.</label>
	   <input data-required="true" type="hidden" name="checkout[payment_method]" value="" class="form-control" disabled />
	</div>	
	<?php
}
else 
{
    ?>
    <div class="form-group">
    <?php
	foreach ($cart->paymentMethods() as $method_array) 
    {
        $method = new \Shop\Models\PaymentMethods( $method_array );
        ?>
		<div class="form-field">
			<label class="radio control-label">
				<input data-required="true" type="radio" name="checkout[payment_method]" value="<?php echo $method->{'id'}; ?>" <?php if (\Dsc\ArrayHelper::get( $cart, 'checkout.payment_method' ) == $method->{'id'}) { echo 'checked'; } ?> />
				<?php echo $method->{'name'}; ?> &mdash; <?php if (empty($method->total())) { echo "FREE"; } else { echo '$' . $method->total(); } ?>
			</label>
		</div>        
        <?php		
	}
	?>
	</div>
	<?php
}
?>