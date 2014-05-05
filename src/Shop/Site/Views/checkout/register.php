<div id="checkout-register" class="checkout-form">
    <h3>New Customers</h3>
    
    <div class="well well-sm">
        <?php 
        if (class_exists('Hybrid_Auth')) 
        {
            ?>
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <legend>
                        <small>Register with the following</small>
                    </legend>
                    <?php echo $this->renderLayout('Users/Site/Views::login/social.php'); ?>
                </div>
            </div>    
            
            <hr/>
                
            <?php  
        } 
        ?>    
            
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <form method="post" action="/shop/checkout/register">
                
                    <legend>
                        <small>
                            <label for="checkout-method-register" class="radio">
                                <input type="radio" value="register" id="checkout-method-register" name="checkout_method" <?php if (!$flash->old('checkout_method') || $flash->old('checkout_method') == 'register') { echo "checked"; } ?>>
                                Register with your email address                    
                            </label>
                            <?php /* ?>
                            <label for="checkout-method-guest" class="radio">
                                <input type="radio" value="guest" id="checkout-method-guest" name="checkout_method" <?php if ($flash->old('checkout_method') == 'guest') { echo "checked"; } ?>>
                                Checkout as a Guest
                            </label> 
                            */ ?>                       
                        </small>
                    </legend>                

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>First Name</label>
                                <input type="text" name="first_name" placeholder="First Name" x-autocompletetype="given-name" autocomplete="given-name" class="form-control" required />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Last Name</label>
                                <input type="text" name="last_name" placeholder="Last Name" x-autocompletetype="surname" autocomplete="family-name" class="form-control" required />
                            </div>
                        </div>
                    </div>                                           
                
                    <div id="email-password" class="form-group">
                        <label>Email Address</label>
                        <input type="text" name="email_address" class="form-control" required />
                        <p id="guest-email-message" class="help-block">Will only be used for order-related communication.</p>
                    </div>
                    
                    <fieldset id="register-password" class="control-group">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>New Password</label>
                                    <input type="password" name="new_password" placeholder="New Password" autocomplete="off" class="form-control" required />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Confirm Password</label>
                                    <input type="password" name="confirm_new_password" placeholder="Confirm New Password" autocomplete="off" class="form-control" required />
                                </div>
                            </div>
                        </div>                            
                    </fieldset>                    
                
                    <p class="help-block">By creating an account, you will be able to move through the checkout process faster, store multiple shipping addresses, view and track your orders in your account and more.</p>
                    
                    <button class="btn btn-default custom-button btn-lg" type="submit">Continue</button>
                </form>
                
            </div>
        </div>
    
    </div>    

</div>

<script>
jQuery(document).ready(function(){
    jQuery('#checkout-method-guest').click(function(){
        jQuery('#email-password').show();
        jQuery('#guest-email-message').show() 
        jQuery('#register-password').hide().find('input').each(function(){
            jQuery(this).data('required', false);
            jQuery(this).removeAttr('required'); 
        });
        jQuery('#opc-checkout-method-button').removeAttr('disabled');
    });
    if (jQuery('#checkout-method-guest').attr('checked')) {
        jQuery('#checkout-method-guest').click();
    }
    
    jQuery('#checkout-method-register').click(function(){
        jQuery('#email-password').show();
        jQuery('#guest-email-message').hide() 
        jQuery('#register-password').show().find('input').each(function(){
            jQuery(this).data('required', true);
            jQuery(this).attr('required', 'required');
        });
        jQuery('#opc-checkout-method-button').removeAttr('disabled');
    });
    if (jQuery('#checkout-method-register').attr('checked')) {
        jQuery('#checkout-method-register').click();
    }
});
</script>