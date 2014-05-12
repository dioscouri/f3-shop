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
                                <li><a href="./user/change-email">Change email</a></li>
                                <li><a href="./user/change-password">Change password</a></li>
                            </ul>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-6">
                            <h4>Address Book</h4>
                            <ul class="list-unstyled">
                                <li>Manage existing addresses</li>
                                <li>Add new address</li>
                            </ul>
                        </div>
                    </div>                
                </div>
            </div>
        </div>
    </div>
    
</div>