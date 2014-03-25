<h3 class="">General Settings</h3>
<hr />

<div class="form-group">
    <label class="control-label col-md-3">Require Shipping Address for All Orders</label>

    <div class="col-md-7">
        <label class="radio-inline">
            <input type="radio" name="shipping[required]" value="0" <?php if (!$flash->old('shipping.required')) { echo "checked"; } ?>> No
        </label>
        <label class="radio-inline">
            <input type="radio" name="shipping[required]" value="1" <?php if ($flash->old('shipping.required')) { echo "checked"; } ?>> Yes
        </label>
    </div>
</div>
