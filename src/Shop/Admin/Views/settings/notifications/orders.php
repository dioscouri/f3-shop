<h4>Order Notifications</h4>

<hr/>

<div class="row">
    <div class="col-md-12">
        
        <div class="form-group">
            <label>Emails</label>
            <input id="notification-orders-emails" name="__notifications_orders_emails" placeholder="Email address..." class="form-control ui-select2-tags" value="<?php echo implode( ",", (array) $flash->old('notifications.orders.emails') ); ?>" data-tags="[]" />
            <p class="help-block">When a new order is accepted, send a notification email to the addresses above.</p>
        </div>
        <!-- /.form-group -->
        
    </div>
    <!-- /.col-md-10 -->
</div>
<!-- /.row -->

<hr />
