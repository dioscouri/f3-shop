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
                
        <div class="form-group">
            <div class="row">
                <div class="col-md-6">        
                    <label>Who is eligible to review a product?</label>
                    <select name="reviews[eligibile]" class="form-control">
                        <option value="purchasers" <?php echo ($flash->old('reviews.eligibile') == 'purchasers') ? "selected='selected'" : null; ?>>Purchasers Only (default)</option>
                        <option value="identified" <?php echo ($flash->old('reviews.eligibile') == 'identified') ? "selected='selected'" : null; ?>>Any logged in user</option>
                    </select>
                </div>
            </div>
        </div>
        <!-- /.form-group -->
        
        <hr/>
                
        <div class="form-group">
            <div class="row">
                <div class="col-md-6">        
                    <label>How many days after purchase before emailing customer to request a review?</label>
                    <input type="text" name="reviews[email_days]" class="form-control" value="<?php echo $flash->old('reviews.email_days'); ?>" />
                    <p class="help-block">Set this to 0 to disable emails.</p>
                </div>
            </div>
        </div>
        <!-- /.form-group -->
                
    </div>
</div>
