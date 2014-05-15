<div class="container">

	<ol class="breadcrumb">
        <li>
            <a href="./shop/account">My Account</a>
        </li>
        <li class="active">My Wishlist</li>
    </ol>

<?php if (empty($wishlist->items)) { ?>
    
        <h2>Your wishlist is empty! <a href="./shop"><small>Go Shopping</small></a></h2>

<?php } ?>

<?php if (!empty($wishlist->items)) { ?>

    <div class="row">
        <div class="col-sm-12">
            <form method="post">
            <div class="table-responsive shopping-cart">
                <table class="table">
                    <thead>
                    <tr>
                        <th width="41%"><div class="title-wrap">Product</div></th>
                        <th width="14%"><div class="title-wrap">Unit Price</div></th>
                        <th width="14%"><div class="title-wrap">Status</div></th>
                        <th width="3%"><div class="title-wrap"><i class="glyphicon glyphicon-remove"></i></div></th>
                    </tr>
                    </thead>
                    <tbody>
                    
                    <?php foreach ($wishlist->items as $key=>$item) { ?>
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
                            <div class="price text-center">
                            <?php if (\Shop\Models\Variants::quantity(\Dsc\ArrayHelper::get($item, 'variant_id'))) { ?>
                                <a class="btn btn-default" href="./shop/wishlist/<?php echo $wishlist->id; ?>/cart/<?php echo \Dsc\ArrayHelper::get($item, 'hash'); ?>">Add to Cart</a>
                            <?php } else { ?>
                                Unavailable
                            <?php } ?>
                            </div>
                        </td>
                        <td><a href="./shop/wishlist/remove/<?php echo \Dsc\ArrayHelper::get($item, 'hash'); ?>" class="btn btn-default custom-button"><i class="glyphicon glyphicon-remove"></i></a></td>
                    </tr>
                    <?php } ?>
                    
                    </tbody>
                </table>
            </div>
            
            </form>                
        </div>
    </div>
    
<?php } ?>

</div>