
<?php if (empty($cart->items)) { ?>
    <div class="container">
        <h2>Your cart is empty! <a href="./shop"><small>Go Shopping</small></a></h2>
    </div>
<?php } ?>

<?php if (!empty($cart->items)) { ?>
<div class="container">
    <div class="row">
        <div class="col-sm-12">
            <form method="post">
            <div class="table-responsive shopping-cart">
                <table class="table">
                    <thead>
                    <tr>
                        <th width="41%"><div class="title-wrap">Product</div></th>
                        <th width="14%"><div class="title-wrap">Unit Price</div></th>
                        <th width="14%"><div class="title-wrap">Quantity</div></th>
                        <th width="14%"><div class="title-wrap">Subtotal</div></th>
                        <th width="3%"><div class="title-wrap"><i class="glyphicon glyphicon-remove"></i></div></th>
                    </tr>
                    </thead>
                    <tbody>
                    
                    <?php foreach ($cart->items as $key=>$item) { ?>
                    <tr>
                        <td>
                            <div class="cart-product">
                                <figure>
                                    <?php if (\Dsc\ArrayHelper::get($item, 'image')) { ?>
                                    <a href="./shop/product/<?php echo \Dsc\ArrayHelper::get($item, 'product.slug'); ?>">
                                        <img src="./asset/thumb/<?php echo \Dsc\ArrayHelper::get($item, 'image'); ?>" alt=""/>
                                    </a>
                                    <?php } ?>
                                </figure>
                                <div class="text">
                                    <h2>
                                        <a href="./shop/product/<?php echo \Dsc\ArrayHelper::get($item, 'product.slug'); ?>"><?php echo \Dsc\ArrayHelper::get($item, 'product.title'); ?></a>
                                        <?php if (\Dsc\ArrayHelper::get($item, 'attribute_title')) { ?>
                                        <div><small><?php echo \Dsc\ArrayHelper::get($item, 'attribute_title'); ?></small></div>
                                        <?php } ?>                                            
                                    </h2>
                                    <div class="details">
                                        <?php if (\Dsc\ArrayHelper::get($item, 'sku')) { ?>
                                        <span class="detail-line">
                                            <strong>SKU:</strong> <?php echo \Dsc\ArrayHelper::get($item, 'sku'); ?>
                                        </span>
                                        <?php } ?>
                                        
                                    </div>
                                </div>
                            </div>
                            
                        </td>
                        <td><div class="price"><?php echo \Shop\Models\Currency::format( \Dsc\ArrayHelper::get($item, 'price') ); ?></div></td>
                        <td>
                            <div class="quantity">
                                <div class="input-group">
                                    <input type="text" class="form-control" value="<?php echo \Dsc\ArrayHelper::get($item, 'quantity'); ?>" placeholder="Quantity" name="quantities[<?php echo \Dsc\ArrayHelper::get($item, 'hash'); ?>]" />
                                    <span class="input-group-addon">
                                        #
                                    </span>
                                </div>
                            </div>
                        </td>
                        <td><div class="subtotal"><?php echo \Shop\Models\Currency::format( $cart->calcItemSubtotal( $item ) ); ?></div></td>
                        <td><a href="./shop/cart/remove/<?php echo \Dsc\ArrayHelper::get($item, 'hash'); ?>" class="btn btn-default custom-button"><i class="glyphicon glyphicon-remove"></i></a></td>
                    </tr>
                    <?php } ?>
                    
                    </tbody>
                </table>
            </div>
            <div class="cart-table-actions">
        	    <div class="pull-right">
            		<button type="submit" name="updateQuantities" onclick="this.form.action='./shop/cart/updateQuantities';" class="cart-table-update btn btn-default custom-button btn-block">
            			Update Quantities
                    </button>
                </div>
                <div class="clearfix"></div>
        	</div>
            </form>                
        </div>
    </div>
    <div class="row margin-top">
        <div class="col-sm-8">
            <div class="total-cost-selectors">
                <div class="row">
                    <div class="col-sm-5">
                        <ul id="myTab" class="nav nav-pills nav-stacked">
                            <?php /*?><li class="active"><a href="#shipping" data-toggle="tab">Estimate Shipping &Taxes</a></li>*/ ?>
                            <?php /*?><li class="active"><a href="#coupon" data-toggle="tab">Coupon Code</a></li><?php */ ?>
                            <?php /*?><li class=""><a href="#voucher" data-toggle="tab">Gift Voucher</a></li><?php */ ?>
                        </ul>
                    </div>
                    <div class="col-sm-7">
                        <div class="tab-content">
                            <?php /*?>
                            <div class="tab-pane fade active in" id="shipping">
                                <p class="info">Enter your destination to get a shipping estimate</p>
                                <form class="form-horizontal" role="form">
                                    <div class="form-group">
                                        <label for="country" class="col-lg-3 control-label">Country<span class="required">*</span></label>
                                        <div class="col-lg-9">
                                            <select name="country" id="country" class="chosen-select full-width">
                                                <option value="default">--Please Select--</option>
                                                <option value="10">10</option>
                                                <option value="25">25</option>
                                                <option value="30">30</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="region" class="col-lg-3 control-label">Region/State<span class="required">*</span></label>
                                        <div class="col-lg-9">
                                            <select name="region" id="region" class="chosen-select full-width">
                                                <option value="default">--Please Select--</option>
                                                <option value="10">10</option>
                                                <option value="25">25</option>
                                                <option value="30">30</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputPostCode" class="col-lg-3 control-label">Post Code<span class="required">*</span></label>
                                        <div class="col-lg-5">
                                            <input type="text" class="form-control" id="inputPostCode" placeholder="Post code">
                                        </div>
                                        <div class="col-lg-4">
                                            <input class="btn btn-default custom-button btn-block" type="submit" value="Get Quotes" />
                                        </div>
                                    </div>
                                </form>
                            </div>
                            
                            <div class="tab-pane fade active in" id="coupon">
                                <p class="info">Enter your coupon code</p>
                                <form class="form-horizontal" role="form">
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="inputCouponCode" placeholder="Code">
                                        <span class="input-group-btn">
                                            <button class="btn btn-default" type="button">Add</button>
                                        </span>                                            
                                    </div>                                    
                                </form>
                            </div>
                            <?php /*?>
                            <div class="tab-pane fade" id="voucher"></div>
                            <?php */ ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="total-box">
                <div class="table-responsive">
                    <table class="table">
                        <tbody>
                        <tr>
                            <td><div class="strong">Subtotal:</div></td>
                            <td><div class="price"><?php echo \Shop\Models\Currency::format( $cart->subtotal() ); ?></div></td>
                        </tr>
                        <tr>
                            <td><div class="strong">Shipping <small>(est)</small>:</div></td>
                            <td><div class="price"><?php echo \Shop\Models\Currency::format( $cart->shippingEstimate() ); ?></div></td>
                        </tr>
                        <tr>
                            <td><div class="strong">Tax <small>(est)</small>:</div></td>
                            <td><div class="price"><?php echo \Shop\Models\Currency::format( $cart->taxEstimate() ); ?></div></td>
                        </tr>
                        </tbody>
                        <tfoot>
                            <td><div class="strong">Total <small>(est)</small>:</div></td>
                            <td><div class="price"><?php echo \Shop\Models\Currency::format( $cart->total() ); ?></div></td>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="clearfix"></div>
    <div class="row">
        <div class="col-sm-12 margin-top">
            <a href="./shop" class="btn btn-default btn-lg custom-button pull-left">Continue Shopping</a>
            <a href="./shop/checkout" class="btn btn-default btn-lg custom-button pull-right">Checkout</a>
            <div class="spacer"></div>
        </div>
    </div>
    
</div>
<?php } ?>
