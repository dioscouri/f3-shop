<div class="container">
    <h1>Account</h1>
    
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
                    <small>View and print recent orders</small>
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
                    <small>Change your password, email, and stored addresses</small>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-8">
                                            
                </div>
            </div>
        </div>
    </div>
    
</div>