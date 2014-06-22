<?php  /* // UPS shipper configuration settings
// sign up for credentials at: https://www.ups.com/upsdeveloperkit - Note: Chrome browser does not work for this page.
$config['ups'] = array();
$config['ups']['key'] = '';
$config['ups']['user'] = '';
$config['ups']['password'] = '';
$config['ups']['account_number'] = '';
$config['ups']['testing_url'] = 'https://wwwcie.ups.com/webservices';
$config['ups']['production_url'] = 'https://onlinetools.ups.com/webservices'; 
// absolute path to the UPS API files relateive to the Ups.php file
$config['ups']['path_to_api_files'] = SHIP_PATH . '/Awsp/Ship/ups_api_files'; 

// shipper information - make any necessary overrides
// note: needs to match information on file with UPS or the API call will fail
$config['ups']['shipper_name'] = $config['shipper_name']; 
$config['ups']['shipper_attention_name'] = $config['shipper_attention_name']; 
$config['ups']['shipper_phone'] = $config['shipper_phone']; 
$config['ups']['shipper_email'] = $config['shipper_email'];
$config['ups']['shipper_address1'] = $config['shipper_address1']; 
$config['ups']['shipper_address2'] = $config['shipper_address2'];
$config['ups']['shipper_address3'] = $config['shipper_address3']; 
$config['ups']['shipper_city'] = $config['shipper_city'];
$config['ups']['shipper_state'] = $config['shipper_state']; 
$config['ups']['shipper_postal_code'] = $config['shipper_postal_code']; 
$config['ups']['shipper_country_code'] = $config['shipper_country_code']; 

/*
01 - Daily Pickup (default)
03 - Customer Counter
06 - One Time Pickup
07 - On Call Air
19 - Letter Center
20 - Air Service Center

$config['ups']['pickup_type'] = '01'; 


00 - Rates Associated with Shipper Number
01 - Daily Rates
04 - Retail Rates
53 - Standard List Rates

$config['ups']['rate_type'] = '00'; 

<?php */ ?>

<div class="row">
    <input type="hidden" name="name" required value="ups">

	<div class="form-group">
                    <label>Enabled?</label>
                    <select name="shippingmethods[ups][enabled]" class="form-control">
                    	<?php  echo \Dsc\Html\Select::options( $no_yes, $flash->old('shippingmethods.ups.enabled') ); ?>
                    </select>
    </div>
    
	<div class="form-group">
		<div class="row">
			<div class="col-md-3">
				<label>Mode</label> 
				<select name="shippingmethods[ups][mode]"
					class="form-control">
					<option value="test"
						<?php if ($flash->old('shippingmethods.ups.mode') == 'test') { echo "selected='selected'"; } ?>>Test</option>
					<option value="live"
						<?php if ($flash->old('shippingmethods.ups.mode') == 'live') { echo "selected='selected'"; } ?>>Live</option>
				</select>
			</div>
			<div class="col-md-9"></div>
		</div>
	</div>
	<!-- /.form-group -->
	<!-- /.col-md-2 -->
</div>





<div class="row">
	
		<h3>UPS Settings</h3>
		<p class="help-text"></p>
	
</div>

<div class="row">
<div class="col-md-6">

	<div class="form-group">
		<label>Daily Pickup</label> <select name="shippingmethods[ups][pickup_type]">
			<?php
			
			$array = array ();
			
			$array [] = array (
					'value' => '01',
					'text' => 'Daily Pickup (default)' 
			);
			$array [] = array (
					'value' => '03',
					'text' => 'Customer Counter' 
			);
			$array [] = array (
					'value' => '06',
					'text' => 'One Time Pickup' 
			);
			$array [] = array (
					'value' => '07',
					'text' => 'On Call Air' 
			);
			$array [] = array (
					'value' => '19',
					'text' => 'Letter Center' 
			);
			$array [] = array (
					'value' => '20',
					'text' => 'Air Service Center' 
			);
			
			echo \Dsc\Html\Select::options ( $array, $flash->old ( 'shippingmethods.ups.pickup_type' ) );
			?>
			</select>
	</div>
	<!-- /.form-group -->
</div>
<!-- /.col-md-10 -->
</div>

<hr>
<div class="row">

		<h3>UPS Shipper Information</h3>
		<p class="help-text">Needs to match information on file with UPS or
			the API call will fail.</p>
</div>





<div class="row">
	<div class="col-md-6">

		
		<div class="form-group">
		<label>Shipper Name</label> <input name="shippingmethods[ups][shipper_name]"
			class="form-control"
			value="<?php echo $flash->old('shippingmethods.ups.shipper_name'); ?>">
	</div>
		<!-- /.form-group -->

		<div class="form-group">
			<label>Shipper Attention Name</label> <input
				name="shippingmethods[ups][shipper_attention_name]" class="form-control"
				value="<?php echo $flash->old('shippingmethods.ups.shipper_attention_name'); ?>">
		</div>
		<!-- /.form-group -->
		<div class="form-group">
			<label>Shipper Phone</label> <input
				name="shippingmethods[ups][shipper_phone]" class="form-control"
				value="<?php echo $flash->old('shippingmethods.ups.shipper_phone'); ?>">
		</div>
		<!-- /.form-group -->
		<div class="form-group">
			<label>Shipper Email</label> <input
				name="shippingmethods[ups][shipper_email]" class="form-control"
				value="<?php echo $flash->old('shippingmethods.ups.shipper_email'); ?>">
		</div>
		<!-- /.form-group -->
		<div class="form-group">
			<label>Address 1</label> <input
				name="shippingmethods[ups][shipper_address1]" class="form-control"
				value="<?php echo $flash->old('shippingmethods.ups.shipper_address1'); ?>">
		</div>
		<!-- /.form-group -->
		<div class="form-group">
			<label>Address 2</label> <input
				name="shippingmethods[ups][shipper_address2]" class="form-control"
				value="<?php echo $flash->old('shippingmethods.ups.shipper_address2'); ?>">
		</div>
		<!-- /.form-group -->
		<div class="form-group">
			<label>Address 3</label> <input
				name="shippingmethods[ups][shipper_address3]" class="form-control"
				value="<?php echo $flash->old('shippingmethods.ups.shipper_address3'); ?>">
		</div>
		<!-- /.form-group -->
	<div class="form-group">
		<label>City</label> <input name="shippingmethods[ups][shipper_city]"
			class="form-control"
			value="<?php echo $flash->old('shippingmethods.ups.shipper_city'); ?>">
	</div>
	<!-- /.form-group -->
	<div class="form-group">
		<label>State</label> <input name="shippingmethods[ups][shipper_state]"
			class="form-control"
			value="<?php echo $flash->old('shippingmethods.ups.shipper_state'); ?>">
	</div>
	<!-- /.form-group -->
	<div class="form-group">
		<label>Postal Code</label> <input
			name="shippingmethods[ups][shipper_postal_code]" class="form-control"
			value="<?php echo $flash->old('shippingmethods.ups.shipper_postal_code'); ?>">
	</div>
	<!-- /.form-group -->
	<div class="form-group">
		<label>Country Code</label> <input
			name="shippingmethods[ups][shipper_country_code]" class="form-control"
			value="<?php echo $flash->old('shippingmethods.ups.shipper_country_code'); ?>">
	</div>
		
	</div>
</div>

<hr />

<div class="row">
	
		<h3>UPS API</h3>
		<p class="help-text"></p>
	
</div>

<div class="row">
<div class="col-md-6">

	<div class="form-group">
			<label>Key</label> <input name="shippingmethods[ups][key]"
				class="form-control"
				value="<?php echo $flash->old('shippingmethods.ups.key'); ?>">
		</div>
	<!-- /.form-group -->

	<div class="form-group">
		<label>User</label> <input name="shippingmethods[ups][user]"
			class="form-control"
			value="<?php echo $flash->old('shippingmethods.ups.user'); ?>">
	</div>
	<!-- /.form-group -->
	<div class="form-group">
		<label>Password</label> <input name="shippingmethods[ups][password]"
			class="form-control"
			value="<?php echo $flash->old('shippingmethods.ups.password'); ?>">
	</div>

	<!-- /.form-group -->
	<div class="form-group">
		<label>Account Number</label> <input
			name="shippingmethods[ups][account_number]" class="form-control"
			value="<?php echo $flash->old('shippingmethods.ups.account_number'); ?>">
	</div>
	<input type="hidden" name="shippingmethods[ups][testing_url]"
			class="form-control" value="https://wwwcie.ups.com/webservices"> <input
			type="hidden" name="shippingmethods[ups][production_url]"
			class="form-control" value="https://onlinetools.ups.com/webservices">
	
	

</div>
<!-- /.col-md-10 -->
</div>
<!-- /.row -->