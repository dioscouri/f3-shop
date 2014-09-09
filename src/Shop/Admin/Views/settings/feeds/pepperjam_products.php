<h4>Pepperjam - Products</h4>

<hr/>

<p class="alert alert-info">
If enabled, you can access your XML feed at this URL: <a href="./shop/pepperjam/products.txt" target="_blank">/shop/pepperjam/products.txt</a> 
</p>

<div class="row">
    <div class="col-md-12">
        
        <div class="form-group">
            <label>Enabled?</label>
            <div class="input-group">
                <label class="radio-inline">
                    <input type="radio" name="feeds[pepperjam_products][enabled]" value="0" <?php if ($flash->old('feeds.pepperjam_products.enabled') == 0) { echo "checked"; } ?>> No
                </label>
                <label class="radio-inline">
                    <input type="radio" name="feeds[pepperjam_products][enabled]" value="1" <?php if ($flash->old('feeds.pepperjam_products.enabled') == 1) { echo "checked"; } ?>> Yes
                </label>
            </div>
        </div>
        <!-- /.form-group -->
        
        <div class="form-group">
            <label>Brand</label>
            <input name="feeds[pepperjam_products][brand]" placeholder="Your 'brand'. This is attached to every product in your feed." value="<?php echo $flash->old('feeds.pepperjam_products.brand'); ?>" class="form-control" type="text" />
        </div>        
        <!-- /.form-group -->
                        
    </div>
    <!-- /.col-md-10 -->
</div>
<!-- /.row -->

<hr />
