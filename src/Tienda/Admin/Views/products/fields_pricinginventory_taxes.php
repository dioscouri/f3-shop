<div class="row">
    <div class="col-md-2">
        
        <h3>Taxes</h3>
        <p class="help-block">Some helpful text</p>
                
    </div>
    <!-- /.col-md-2 -->
                
    <div class="col-md-10">

        <div class="form-group">
            <label>Charge Taxes</label>
            <div class="form-group">
            <label class="radio-inline">
                <input type="radio" name="taxes[enabled]" value="1" <?php if ($flash->old('taxes.enabled')) { echo 'checked'; } ?>> Yes
            </label>
            <label class="radio-inline">
                <input type="radio" name="taxes[enabled]" value="0" <?php if (!$flash->old('taxes.enabled')) { echo 'checked'; } ?>> No
            </label>
            </div>
        </div>
        <!-- /.form-group -->

        <div class="form-group">
            <label>Tax Class</label>
            <select name="taxes[class]" class="form-control">
                <option value="default" <?php if (!$flash->old('taxes.class') || $flash->old('taxes.class') == "default") { echo "selected='selected'"; } ?>>Default</option>
            </select>
        </div>
    
    </div>
    <!-- /.col-md-10 -->
    
</div>
<!-- /.row -->