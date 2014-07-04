<h4>Google Merchant - Products</h4>

<hr/>

<p class="alert alert-info">
If enabled, you can access your XML feed at this URL: <a href="./shop/google-merchant/products.xml" target="_blank">/shop/google-merchant/products.xml</a> 
<?php /* ?>and your text feed at this URL: <a href="./shop/google-merchant/products.txt" target="_blank">/shop/google-merchant/products.txt</a> */ ?>
</p>

<div class="row">
    <div class="col-md-12">
        
        <div class="form-group">
            <label>Enabled?</label>
            <div class="input-group">
                <label class="radio-inline">
                    <input type="radio" name="feeds[gm_products][enabled]" value="0" <?php if ($flash->old('feeds.gm_products.enabled') == 0) { echo "checked"; } ?>> No
                </label>
                <label class="radio-inline">
                    <input type="radio" name="feeds[gm_products][enabled]" value="1" <?php if ($flash->old('feeds.gm_products.enabled') == 1) { echo "checked"; } ?>> Yes
                </label>
            </div>
        </div>
        <!-- /.form-group -->
        
        <div class="form-group">
            <label>Title</label>
            <input name="feeds[gm_products][title]" placeholder="A title for the feed.  Defaults to 'Product Feed'" value="<?php echo $flash->old('feeds.gm_products.title'); ?>" class="form-control" type="text" />
        </div>        
        <!-- /.form-group -->
        
        <div class="form-group">
            <label>Brief description</label>
            <input name="feeds[gm_products][description]" placeholder="A brief, brief description of the feed." value="<?php echo $flash->old('feeds.gm_products.description'); ?>" class="form-control" type="text" />
        </div>        
        <!-- /.form-group -->
        
        <div class="form-group">
            <label>Brand</label>
            <input name="feeds[gm_products][brand]" placeholder="Your 'brand'. This is attached to every product in your feed." value="<?php echo $flash->old('feeds.gm_products.brand'); ?>" class="form-control" type="text" />
        </div>        
        <!-- /.form-group -->
        
        <div class="form-group">
            <div class="row">
                <div class="col-md-6">
                    <label>Default Gender</label>
                    <select name="feeds[gm_products][gender]" class="form-control">
                        <?php echo Dsc\Html\Select::options(array(
                            array('value'=>'female', 'text'=>'Female'),
                            array('value'=>'male', 'text'=>'Male'),
                            array('value'=>'unisex', 'text'=>'Unisex'),
                        ), $flash->old('feeds.gm_products.gender')); ?>
                    </select>                
                </div>                
                <div class="col-md-6">
                    <label>Default Age Group</label>
                    <select name="feeds[gm_products][age_group]" class="form-control">
                        <?php echo Dsc\Html\Select::options(array(
                            array('value'=>'adult', 'text'=>'Adult'),
                            array('value'=>'kids', 'text'=>'Kids'),
                            array('value'=>'toddler', 'text'=>'Toddler'),
                            array('value'=>'infant', 'text'=>'Infant'),
                            array('value'=>'newborn', 'text'=>'Newborn'),
                        ), $flash->old('feeds.gm_products.age_group')); ?>
                    </select>                
                </div>
            </div>

        </div>        
        <!-- /.form-group -->       
                        
    </div>
    <!-- /.col-md-10 -->
</div>
<!-- /.row -->

<hr />
