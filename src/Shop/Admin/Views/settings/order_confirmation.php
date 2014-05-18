<h3 class="">Order Confirmation Page</h3>
<hr />

<div class="row">
                
    <div class="col-md-12">

        <div class="form-group">
            <h3>Google Analytics E-commerce Tracking</h3>
            <label>Enabled?</label>
            <div class="input-group">
                <label class="radio-inline">
                    <input type="radio" name="order_confirmation[gtm][enabled]" value="0" <?php if ($flash->old('order_confirmation.gtm.enabled') == 0) { echo "checked"; } ?>> No
                </label>
                <label class="radio-inline">
                    <input type="radio" name="order_confirmation[gtm][enabled]" value="1" <?php if ($flash->old('order_confirmation.gtm.enabled') == 1) { echo "checked"; } ?>> Yes
                </label>
            </div>
            <p class="help-block"><b>Note: </b> You must have your Google Tag Manager container code pasted in your site's Theme immediately below the <?php echo htmlspecialchars('<body>'); ?> tag.</p>
            <p class="help-block">Remember to add a condition to your GTM rule to wait until the page (DOM) is fully loaded, with the built-in event "gtm.dom".  Please see <a href="https://support.google.com/tagmanager/answer/3002596">https://support.google.com/tagmanager/answer/3002596</a> for details.</p>
            <p class="help-block">Here is a sample of the code we'll insert in your order confirmation page if this is enabled:</p>
            <pre><?php echo htmlspecialchars("
<script>
dataLayer.push({
    'event': 'transaction',                
    'transactionId': '1234',
    'transactionTotal': 11.99,
    'transactionTax': 1.29,
    'transactionShipping': 5,
    'transactionProducts': [{
        'sku': 'DD44',
        'name': 'T-Shirt',
        'price': 11.99,
        'quantity': 1
    },{
        'sku': 'AA1243544',
        'name': 'Socks',
        'price': 9.99,
        'quantity': 2
    }]
});
</script>
"); ?>      </pre>
        </div>
        <!-- /.form-group -->
    
    </div>
    <!-- /.col-md-10 -->
</div>
<!-- /.row -->

<hr />

<?php /* ?>
<div class="row">
    <div class="col-md-2">
    
        <h3>Generic Tracking Pixels</h3>
                
    </div>
    <!-- /.col-md-2 -->
                
    <div class="col-md-10">

        <div class="row">
            <div class="col-md-8">
                <div class="form-group">
                    <p class="help-block">This will be displayed on the order confirmation page.</p>
                    <textarea rows="15" name="order_confirmation[tracking_pixels][generic]" class="form-control"><?php echo $flash->old('order_confirmation.tracking_pixels.generic'); ?></textarea>
                    
                </div>
                <!-- /.form-group -->
            </div>
            <div class="col-md-4">
                <label>Available Tags</label>
                <ul class="list-unstyled">
                    <li>{user_name}</li>
                    <li>etc</li>
                </ul>
            </div>
        </div>
    
    </div>
    <!-- /.col-md-10 -->
</div>
<!-- /.row -->

<hr />

*/ ?>