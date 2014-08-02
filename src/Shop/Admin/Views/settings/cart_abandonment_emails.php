<h3>Cart Abandonment Emails</h3>
<hr />

<div class="form-group">
    <label>Abandonment Cart Time Limit <small>(in minutes)</small></label>
    <input name="abandoned_cart_time" type="text" class="form-control" value="<?php echo $flash->old('abandoned_cart_time')?>" placeholder="For example, 10 as in 10 minutes" />
    <p class="help-block">How long must a cart be idle before it is considered abandoned?</p>
</div>
<div class="form-group">
    <label>Abandonment Cart Email Subject</label>
    <input name="abandoned_cart_subject" type="text" class="form-control" value="<?php echo $flash->old('abandoned_cart_subject')?>" placeholder="Subject for all abandoned cart notification emails" />
</div>

<hr />

<div class="row">
	<div class="col-lg-3 col-md-3 col-xs-5">
		<h3>Notification Emails</h3>
		<button class="btn btn-success" id="add-abandonment-email">Add Email Notification</button>
	</div>
	
	<div class="col-lg-9 col-md-9 col-xs-7">
		
		<div class="from-groups" id="abandonment-emails">
<?php foreach ((array) $flash->old('abandoned_cart_emails') as $key => $attribute) { ?>
			<div class="form-group well" data-email-idx="<?php echo $key; ?>">
				<div class="clearfix">
					<b>Email Notification</b>
					<a class="remove-option btn btn-xs btn-secondary pull-right" onclick="javascript:ShopRemoveAbandonmentEmail(this);" href="javascript:;">
						<i class="fa fa-times"></i>
					</a>
				</div>
				<div class="col-lg-12 col-xs-12">
					<div class="form-group clearfix">
						<label>Time Delay <small>(in minutes)</small></label>
						<input type="text" value="<?php echo $flash->old( 'abandoned_cart_emails.'.$key.'.delay' ); ?>" name="abandoned_cart_emails[<?php echo $key; ?>][delay]" class="form-control" placeholder="Time delay since the cart was abandoned in minutes" />
						<p class="help-block">How many minutes should we wait after the cart is abandoned before sending this email?</p>
					</div>
				</div>
				<div class="col-lg-12 col-xs-12">
					<div class="form-group clearfix">
						<label>Email plain text version</label>
			            <textarea name="abandoned_cart_emails[<?php echo $key; ?>][text][plain]" class="form-control" rows="4"><?php echo $flash->old( 'abandoned_cart_emails.'.$key.'.text.plain' ); ?></textarea>
					</div>
				</div>
				<div class="col-lg-12 col-xs-12">
					<div class="form-group clearfix">
						<label>Email HTML version</label>
			            <textarea name="abandoned_cart_emails[<?php echo $key; ?>][text][html]" class="form-control wysiwyg"><?php echo $flash->old( 'abandoned_cart_emails.'.$key.'.text.html' ); ?></textarea>
					</div>
				</div>
			</div>
 <?php } ?>
 		</div>
        
        <template type="text/template" id="add-abandonment-email-template">
			<div class="form-group well" data-email-idx="{id}">
				<div class="clearfix">
					<b>Email Notification</b>
					<a class="remove-option btn btn-xs btn-secondary pull-right" onclick="javascript:ShopRemoveAbandonmentEmail(this);" href="javascript:;">
						<i class="fa fa-times"></i>
					</a>
				</div>
				<div class="col-lg-12 col-xs-12">
					<div class="form-group clearfix">
						<label>Time Delay <small>(in minutes)</small></label>
						<input type="text" value="" name="abandoned_cart_emails[{id}][delay]" class="form-control" placeholder="Time delay since the cart was abandoned in minutes" />
					</div>
				</div>
				<div class="col-lg-12 col-xs-12">
					<div class="form-group clearfix">
						<label>Email plain text version</label>
			            <textarea name="abandoned_cart_emails[{id}][text][plain]" class="form-control" rows="4"></textarea>
					</div>
				</div>
				<div class="col-lg-12 col-xs-12">
					<div class="form-group clearfix">
						<label>Email HTML version</label>
			            <textarea name="abandoned_cart_emails[{id}][text][html]" class="form-control wysiwyg"></textarea>
					</div>
				</div>
			</div>
        </template>
        
        <script>
        jQuery(document).ready(function(){
            window.new_abandonment_emails = <?php echo count( (array)$flash->old('abandoned_cart_emails') ); ?>;
            jQuery('#add-abandonment-email').click(function(e){
                e.preventDefault();
                var container = jQuery('#abandonment-emails');
                var template = jQuery('#add-abandonment-email-template').html();
                template = template.replace( new RegExp("{id}", 'g'), window.new_abandonment_emails );
                container.append(template);
//                $( 'div[data-email-idx='+window.new_abandonment_emails+']' ).ckeditor();
                window.new_abandonment_emails = window.new_abandonment_emails + 1;
            });
    
            ShopRemoveAbandonmentEmail = function(el) {
                jQuery(el).parents('div[data-email-idx]').remove();                            
            }
    
        });
        </script>
	</div>
</div>


<!-- /.form-group -->     

