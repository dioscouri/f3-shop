<h3 class="">Product Reviews</h3>
<hr />

<div class="row">
    <div class="col-md-12">

        <div class="form-group">
            <div class="row">
                <div class="col-md-4">        
                    <label>Enable Product Reviews</label>
                    <select name="reviews[enabled]" class="form-control">
                        <option value="0" <?php echo (!$flash->old('reviews.enabled')) ? "selected='selected'" : null; ?>>No</option>
                        <option value="1" <?php echo ($flash->old('reviews.enabled')) ? "selected='selected'" : null; ?>>Yes</option>
                    </select>
                </div>
            </div>
        </div>
        <!-- /.form-group -->
        
        <hr/>
                
                
    </div>
</div>
