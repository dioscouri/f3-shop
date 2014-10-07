<div class="row">
    <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
        <h1 class="page-title txt-color-blueDark">
            <i class="fa fa-table fa-fw "></i> Wishlist
            <span> > Details </span>
        </h1>
    </div>
    <div class="col-xs-12 col-sm-5 col-md-5 col-lg-8">
        <ul id="sparks" class="list-actions list-unstyled list-inline">
            <li>
                <a class="btn btn-default" href="./admin/shop/wishlists">Return to List</a>
            </li>
        </ul>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <h2>
            Customer: <?php echo $item->user()->fullName(); ?><br/>
            <?php echo $item->user()->email; ?>
            <small class="help-block">Wishlist ID: <?php echo $item->id; ?></small>
        </h2>        
    </div>
</div>

<div class="row">
    <div class="col-md-9">

        <div class="well">
            
            <div class="form-group">
                <legend>
                    <div class="row">
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <small>Items</small>
                        </div>
                        <div class="col-xs-3 col-sm-3 col-md-3">
                            <small>Price</small>
                        </div>
                        <div class="col-xs-3 col-sm-3 col-md-3">
                            <small></small>
                        </div>                        
                    </div>
                </legend>        
                
                <?php foreach ($item->items as $orderitem) { ?>
                <div class="list-group-item">
                <div class="row">
                    <div class="col-xs-6 col-sm-6 col-md-6">
                        <div class="row">
                        
                            <?php if (\Dsc\ArrayHelper::get($orderitem, 'image')) { ?>
                            <div class="hidden-xs hidden-sm col-md-2">
                                <img class="img-responsive" src="./asset/thumb/<?php echo \Dsc\ArrayHelper::get($orderitem, 'image'); ?>" alt="" />
                            </div>
                            <?php } ?>
                            <div class="col-xs-12 col-sm-12 col-md-10">
                                <h4>
                                    <?php echo \Dsc\ArrayHelper::get($orderitem, 'product.title'); ?>
                                    <?php if (\Dsc\ArrayHelper::get($orderitem, 'attribute_title')) { ?>
                                    <div>
                                        <small><?php echo \Dsc\ArrayHelper::get($orderitem, 'attribute_title'); ?></small>
                                    </div>
                                    <?php } ?>                        
                                </h4>
                                <div class="details">
                
                                </div>
                                <div>
                                    <span class="quantity"><?php echo $quantity = \Dsc\ArrayHelper::get($orderitem, 'quantity'); ?></span>
                                    x
                                    <span class="price"><?php echo \Shop\Models\Currency::format( $price = \Dsc\ArrayHelper::get($orderitem, 'price') ); ?></span> 
                                </div>
                            </div>                        
                        
                        </div>
                    </div>
                    <div class="col-xs-3 col-sm-3 col-md-3">
                        <?php echo \Shop\Models\Currency::format( $quantity * $price ); ?>
                    </div>
                    <div class="col-xs-3 col-sm-3 col-md-3">
                                                    
                    </div>                        

                </div> 
                
                </div>       
                <?php } ?>
            </div>
        </div>
        
        <div class="well">
            <div class="row">
                <div class="col-md-2">
                    
                    <h3>History</h3>
                    <p class="help-block">The activity log for this wishlist.</p>
                            
                </div>
                <!-- /.col-md-2 -->
                            
                <div class="col-md-10">
                    
                    <ul class="list-group">
                        <li class="list-group-item">
                            <div class="row">
                                <div class="col-md-2">
                                    <?php echo (new \DateTime($item->{'metadata.created.local'}))->format('F j, Y g:ia'); ?>
                                </div>
                                <div class="col-md-10">
                                    Created
                                </div>
                            </div>
                        </li>                    
                    </ul>
                    
                </div>
                <!-- /.col-md-10 -->
                
            </div>        
        </div>
        <!-- /.well -->
        
    </div>
    <div class="col-md-3">
        <p>
            <?php /* ?><a class="btn btn-success" href="./admin/shop/wishlist/email/<?php echo $item->id; ?>">Email User</a> */ ?>
        </p>            
    </div>
</div>