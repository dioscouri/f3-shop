<h3 class="">Order Confirmation Page</h3>
<hr />

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
