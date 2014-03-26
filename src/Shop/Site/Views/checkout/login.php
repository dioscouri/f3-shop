<div id="checkout-login" class="checkout-form">
    <h3>Returning Customers</h3>

    <div class="well well-sm">
    
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <legend>
                    <small>Login with the following</small>
                </legend>
                <p>social sign-on options</p>
            </div>
        </div>    
        
        <hr/>
        
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <legend>
                    <small>Login with your registered email address</small>
                </legend>
                <form action="./login" method="post" class="form" role="form">
        
                    <div class="input-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-envelope"></i></span>
                        <input name="login-username" type="email" class="form-control" placeholder="E-Mail Address*" />
                    </div>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                        <input name="login-password" type="password" class="form-control" placeholder="Password*" />
                    </div>            
                    
                    <div class="input-group">    
                        <button class="btn btn-default custom-button btn-lg" type="submit">Login</button>
                        <?php \Dsc\System::instance()->get('session')->set('site.login.redirect', '/shop/checkout'); ?>
                    </div>
                    
                </form>
                
                <p class="margin-top">Forgot your password?</p>
            </div>
        </div>
    
    </div>
    
</div>