<h3 class="">Checkout Settings</h3>
<hr />

<div class="form-group">
    <div class="row">
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
</div>

<div class="form-group">
    <label>Sort order for Countries</label>
    <select name="countries_sort" class="form-control">
        <option value="name" <?php if ($flash->old('countries_sort' == 'name')) { echo "selected"; } ?>>Name of Country</option>
        <option value="ordering" <?php if ($flash->old('countries_sort' == 'ordering')) { echo "selected"; } ?>>Manual Ordering</option>        
    </select>
</div>