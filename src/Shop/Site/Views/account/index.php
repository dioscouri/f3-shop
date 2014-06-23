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
                    </legend>
                    <p class="help-block"><small>View and print recent orders</small></p>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-8">
                    <div class="row">
                        <div class="col-xs-4 col-sm-4 col-md-3">
                            <a class="btn btn-info" href="./shop/orders">Browse All</a>
                        </div>
                        <div class="col-xs-8 col-sm-8 col-md-9">
                            <form action="./shop/orders" method="post">
                            
                                <div class="input-group">
                                    <input class="form-control" type="text" name="filter[keyword]" placeholder="Search..." maxlength="200" value=""> 
                                    <span class="input-group-btn">
                                        <input class="btn btn-primary" type="submit" onclick="this.form.submit();" value="Search" />
                                    </span>
                                </div>
                            
                            </form>
                        </div>
                    </div>
                    
                    <?php if (!empty($order)) { ?>
                    <hr />
                    
                    <h4>Most Recent Order</h4>
                    
                    <div class="list-group-item">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-4">
                                <legend>
                                    <a href="./shop/order/<?php echo $order->id; ?>"><?php echo (new \DateTime($order->{'metadata.created.local'}))->format('F j, Y'); ?></a>
                                    
                                    <?php switch($order->{'status'}) {
                                    	case \Shop\Constants\OrderStatus::cancelled:
                                    	    $label_class = 'label-danger';
                                    	    break;
                                	    case \Shop\Constants\OrderStatus::closed:
                                	        $label_class = 'label-default';
                                	        break;
                                    	case \Shop\Constants\OrderStatus::open:
                                    	default:
                                    	    $label_class = 'label-success';
                                    	    break;
                                    
                                    } ?>
                                    
                                    <span class="pull-right label <?php echo $label_class; ?>">
                                    <?php echo $order->{'status'}; ?>
                                    </span>
                                                                
                                </legend>
                                <div></div>
                                <div><label>Total:</label> <?php echo \Shop\Models\Currency::format( $order->{'grand_total'} ); ?></div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-8">
                                <legend>
                                    <label>#</label><a href="./shop/order/<?php echo $order->id; ?>"><?php echo $order->{'number'}; ?></a>                                                    
                                </legend>
                                
                                <?php foreach ($order->items as $item) { ?>
                                <div class="row">
                                    <?php if (\Dsc\ArrayHelper::get($item, 'image')) { ?>
                                    <div class="hidden-xs hidden-sm col-md-2">
                                        <img class="img-responsive" src="./asset/thumb/<?php echo \Dsc\ArrayHelper::get($item, 'image'); ?>" alt="" />
                                    </div>
                                    <?php } ?>
                                    <div class="col-xs-12 col-sm-12 col-md-10">
                                        <div class="title">
                                            <?php echo \Dsc\ArrayHelper::get($item, 'product.title'); ?>
                                            <?php if (\Dsc\ArrayHelper::get($item, 'attribute_title')) { ?>
                                            <div>
                                                <small><?php echo \Dsc\ArrayHelper::get($item, 'attribute_title'); ?></small>
                                            </div>
                                            <?php } ?>                        
                                        </div>
                                        <div class="details">
                        
                                        </div>
                                        <div>
                                            <span class="quantity"><?php echo \Dsc\ArrayHelper::get($item, 'quantity'); ?></span>
                                            x
                                            <span class="price"><?php echo \Shop\Models\Currency::format( \Dsc\ArrayHelper::get($item, 'price') ); ?></span> 
                                        </div>                                
                                    </div>
                                </div>        
                                <?php } ?>
                                                        
                            </div>
                        </div>
                    </div>                    
                    
                    <?php } ?>
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
                    <p class="help-block"><small>Store credits will be applied automatically during checkout</small></p>
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