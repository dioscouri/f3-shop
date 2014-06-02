<div class="container">
    <h2>
        <small>Hello, <?php echo $this->auth->getIdentity()->username; ?><br/></small>
        Your Account
    </h2>
    
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-4">
                    <legend>
                        Orders
                        <div class="pull-right">
                            <a href="./shop/orders"><small>Browse</small></a>
                        </div>                    
                    </legend>
                    <p class="help-block"><small>View and print recent orders</small></p>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-8">
                    <div>
                        <form action="./shop/orders" method="post">
                        
                            <div class="input-group">
                                <input class="form-control" type="text" name="filter[keyword]" placeholder="Search..." maxlength="200" value=""> 
                                <span class="input-group-btn">
                                    <input class="btn btn-primary" type="submit" onclick="this.form.submit();" value="Search" />
                                    <button class="btn btn-danger" type="button" onclick="Dsc.resetFormFilters(this.form);">Reset</button>
                                </span>
                            </div>
                        
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <?php if ($balance = $this->auth->getIdentity()->reload()->{'shop.credits.balance'}) { ?>
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-4">
                    <legend>Store Credit</legend>
                    <p class="help-block"><small>Store credits can be applied during checkout</small></p>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-8">
                    <h4>Available Balance: <span class="label label-success"><?php echo \Shop\Models\Currency::format( $balance ); ?></span></h4>                
                </div>
            </div>
        </div>
    </div>
    <?php } ?>
    
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-4">
                    <legend>Settings</legend>
                    <p class="help-block"><small>Change your password, email, and stored addresses</small></p>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-8">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-6">
                            <h4>Account Settings</h4>
                            <ul class="list-unstyled">
                                <li><a href="./user/change-basic">Change basic information</a></li>
                            	<li><a href="./user/change-email">Change email</a></li>
                                <li><a href="./user/change-password">Change password</a></li>
                            </ul>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-6">
                            <h4>Address Book</h4>
                            <ul class="list-unstyled">
                                <li><a href="./shop/account/addresses">Manage existing addresses</a></li>
                                <li><a href="./shop/account/addresses/create">Add new address</a></li>
                            </ul>
                        </div>
                    </div>                
                </div>
            </div>
        </div>
    </div>
    
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-4">
                    <legend>Personalization</legend>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-8">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-6">
                            <h4>Social</h4>
                            <ul class="list-unstyled">
                                <li><a href="./user/social-profiles">Linked Social Profiles</a></li>
                            </ul>                        
                            <h4>Lists</h4>
                            <ul class="list-unstyled">
                                <li><a href="./shop/wishlist">Wishlist</a></li>
                            </ul>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-6">
                            <?php if (class_exists('\Affiliates\Models\Referrals')) { ?>
                            <h4>Referrals</h4>
                            <ul class="list-unstyled">
                                <li><a href="./affiliate/dashboard">Your affiliate account</a></li>
                                <li><a href="./affiliate/invite-friends">Invite friends</a></li>
                            </ul>
                            <?php } ?>
                            <?php /* ?>
                            <h4>Newsletters</h4>
                            <ul class="list-unstyled">
                                <li>Manage subscriptions</li>
                            </ul>
                            */ ?>
                        </div>
                    </div>                
                </div>
            </div>
        </div>
    </div>
    
</div>