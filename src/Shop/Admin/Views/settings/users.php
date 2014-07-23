<h3 class="">User Settings</h3>
<hr />

<div class="form-group">
    <label>Default Shopper Group</label>
    <select name="users[default_group]" class="form-control">
        <?php foreach ($groups = \Users\Models\Groups::find() as $group) { ?>
        <option value="<?php echo (string) $group->id; ?>" <?php if ($flash->old('users.default_group') == (string) $group->id) { echo "selected='selected'"; } ?>><?php echo $group->title; ?></option>
        <?php } ?>
    </select>                        
</div>

<hr />

<h3>Default Special Group Prices</h3>
<div class="row">
	<div class="col-lg-3 col-md-3 col-sd-4">
		<b>Wholesale</b>
	</div>
	<div class="col-lg-9 col-md-9 col-sd-8">
		<div class="form-group">
			<label>Regular price discount (in percent)</label>
			<input class="form-control" value="<?php echo $flash->old( 'special_group_default_prices.wholesale.regular', 0.6 ); ?>" name="special_group_default_prices[wholesale][regular]" type="text" placeholder="For 40% discount from regular price use 40" />
		</div>
		<div class="form-group">
			<label>Sale price discount (in percent)</label>
			<input class="form-control" value="<?php echo $flash->old( 'special_group_default_prices.wholesale.sale', 0.5 ); ?>" name="special_group_default_prices[wholesale][sale]" type="text" placeholder="For 50% discount from sale  price use 50" />
		</div>
	</div>
</div>


<!-- /.form-group -->                    
