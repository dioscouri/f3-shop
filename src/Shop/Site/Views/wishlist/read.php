
<?php if (empty($wishlist->items)) { ?>
    <div class="container">
        <h2>Your wishlist is empty! <a href="./shop"><small>Go Shopping</small></a></h2>
    </div>
<?php } ?>

<?php if (!empty($wishlist->items)) { ?>
<div class="container">
    <div class="row">
        <div class="col-sm-12">
            <form method="post">
            <div class="table-responsive shopping-wishlist">
                <table class="table">
                    <thead>
                    <tr>
                        <th width="41%"><div class="title-wrap">Product</div></th>
                        <th width="14%"><div class="title-wrap">Unit Price</div></th>
                        <th width="14%"><div class="title-wrap"></div></th>
                        <th width="3%"><div class="title-wrap"><i class="glyphicon glyphicon-remove"></i></div></th>
                    </tr>
                    </thead>
                    <tbody>
                    
                    <?php foreach ($wishlist->items as $key=>$item) { ?>
                    <tr>
                        <td>
                            <div class="wishlist-product">
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
                        <td><div class="price">$<?php echo \Dsc\ArrayHelper::get($item, 'price'); ?></div></td>

                        <td>[add to cart]</td>
                        <td><a href="./shop/wishlist/remove/<?php echo \Dsc\ArrayHelper::get($item, 'hash'); ?>" class="btn btn-default custom-button"><i class="glyphicon glyphicon-remove"></i></a></td>
                    </tr>
                    <?php } ?>
                    
                    </tbody>
                </table>
            </div>
            
            </form>                
        </div>
    </div>
    
</div>
<?php } ?>
