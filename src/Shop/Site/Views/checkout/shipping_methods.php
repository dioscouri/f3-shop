<?php if (empty($cart->shippingMethods(true))) 
{
	?>
	<div class="form-group has-error">
	   <label class="control-label">No valid shipping methods could be found for the provided shipping address.</label>
	   <input data-required="true" type="hidden" name="checkout[shipping_method]" value="" class="form-control" disabled />
	</div>	
	<?php
}
else 
{
    ?>
    <div class="form-group">
    <?php
	foreach ($cart->shippingMethods() as $method_array) 
    {
        $method = new \Shop\Models\Prefabs\ShippingMethods( $method_array );
        ?>
		<div class="form-field">
			<label class="radio control-label">
				<input data-required="true" type="radio" name="checkout[shipping_method]" value="<?php echo $method->{'id'}; ?>" <?php if (\Dsc\ArrayHelper::get( $cart, 'checkout.shipping_method' ) == $method->{'id'}) { echo 'checked'; } ?> />
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