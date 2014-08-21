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

    <?php foreach ($wishlist->items as $key=>$item) { ?>
        <div class="list-group-item">
            <div class="row">
                <div class="col-xs-6 col-sm-8 col-md-8">
                    <div class="row">
                        <div class="col-xs-4 col-sm-3 col-md-3">
                            <figure>
                                <?php if (\Dsc\ArrayHelper::get($item, 'image')) { ?>
                                <a href="./shop/product/<?php echo \Dsc\ArrayHelper::get($item, 'product.slug'); ?>">
                                    <img class="img-responsive" src="./asset/thumb/<?php echo \Dsc\ArrayHelper::get($item, 'image'); ?>" alt=""/>
                                </a>
                                <?php } ?>
                            </figure>                
                        </div>
                        <div class="col-xs-8 col-sm-9 col-md-9">
                            <div class="text">
                                <h4>
                                    <a href="./shop/product/<?php echo \Dsc\ArrayHelper::get($item, 'product.slug'); ?>"><?php echo \Dsc\ArrayHelper::get($item, 'product.title'); ?></a>
                                    <?php if (\Dsc\ArrayHelper::get($item, 'attribute_title')) { ?>
                                    <div><small><?php echo \Dsc\ArrayHelper::get($item, 'attribute_title'); ?></small></div>
                                    <?php } ?>                                            
                                </h4>
                                <div class="details">
                                    <?php if (\Dsc\ArrayHelper::get($item, 'sku')) { ?>
                                    <span class="detail-line">
                                        <strong>SKU:</strong> <?php echo \Dsc\ArrayHelper::get($item, 'sku'); ?>
                                    </span>
                                    <?php } ?>                            
                                </div>
                                
                                <?php $product = $wishlist->product( $item ); ?>
                                
                                <?php if (((int) $product->get('prices.list') > 0) && (float) $product->get('prices.list') != (float) $product->price() ) { ?>
                                    <span class="list-price"><strike><?php echo \Shop\Models\Currency::format( $product->{'prices.list'} ); ?></strike></span>
                                    &nbsp;
                                <?php } ?>                                
                                <div class="price"><?php echo \Shop\Models\Currency::format( $product->price() ); ?></div>
                                                                
                            </div>                
                        </div>
                    </div>
                </div>
            
                <div class="col-xs-4 col-sm-3 col-md-3">
                    <div class="price text-center">
                    <?php if (\Shop\Models\Variants::quantity(\Dsc\ArrayHelper::get($item, 'variant_id'))) { ?>
                        <a class="btn btn-default" data-product-sku="<?php echo  \Dsc\ArrayHelper::get($item, 'sku'); ?>" data-product-variant="<?php echo \Dsc\ArrayHelper::get($item, 'attribute_title'); ?>" data-product-name="<?php echo \Dsc\ArrayHelper::get($item, 'product.title'); ?>" href="./shop/wishlist/<?php echo $wishlist->id; ?>/cart/<?php echo \Dsc\ArrayHelper::get($item, 'hash'); ?>" data-button="add-to-cart">Add to Cart</a>
                    <?php } else { ?>
                        Unavailable
                    <?php } ?>
                    </div>
                </div>
                
                <div class="col-xs-2 col-sm-1 col-md-1">
                    <a href="./shop/wishlist/remove/<?php echo \Dsc\ArrayHelper::get($item, 'hash'); ?>" class="btn btn-default custom-button"><i class="glyphicon glyphicon-remove"></i></a>
                </div>
            </div>
        </div>
    <?php } ?>
    
<?php } ?>

</div>