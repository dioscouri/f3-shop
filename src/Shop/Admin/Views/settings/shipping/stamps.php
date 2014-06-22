


<div class="row">
<input type="hidden" name="name" required value="stamps">
	<div class="form-group">
                    <label>Enabled?</label>
                    <select name="shipping[stamps][enabled]" class="form-control">
                    	<?php  echo \Dsc\Html\Select::options( $no_yes, $flash->old('shipping.stamps.enabled') ); ?>
                    </select>
    </div>
</div>