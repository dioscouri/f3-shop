<?php $item->url = './shop/product/' . $item->{'slug'}; ?>
<?php $images = $item->images(); ?>


<div class="container">
    <div class="row">
        <div class="col-sm-6">
            <div class="product-slider-small">
                <?php if (count($images) > 1) { ?>       
                <ul class="zoom-thumbs">
                    <?php foreach ($images as $key=>$image) { ?>
                    <li id="<?php echo $image; ?>">
                        <a rel="{gallery: 'zoom-gallery', smallimage: './asset/thumb/<?php echo $image; ?>', largeimage: './asset/<?php echo $image; ?>'}" href="javascript:void(0);">
                            <img src="./asset/thumb/<?php echo $image; ?>" title="<?php echo htmlspecialchars_decode( $item->title ); ?>"> 
                        </a>                        
                    </li>
                    <?php } ?>
                </ul>
                <?php } ?>
            </div>
            
            <div class="product-image product-image-big">
                <a class="zoom" rel="zoom-gallery" href="./asset/<?php echo $item->{'featured_image.slug'}; ?>" title="<?php echo htmlspecialchars_decode( $item->title ); ?>" data-large-url="./asset/<?php echo $item->{'featured_image.slug'}; ?>">
    	            <img class="zoomable" src="./asset/thumb/<?php echo $item->{'featured_image.slug'}; ?>" title="<?php echo htmlspecialchars_decode( $item->title ); ?>" />
                </a>
            </div>
                            
        </div>
        <div class="col-sm-6">
            <div class="product-details">
                <h1><?php echo $item->{'title'}; ?></h1>

                <hr />
                <div class="details">
                    <span class="detail-line"><strong>Product Code:</strong> <?php echo $item->{'tracking.sku'}; ?></span>
                </div>
                <div class="description">
                    <?php echo $item->{'copy'}; ?>
                </div>
                <form action="./shop/cart/add" method="post">
                    <div id="validation-cart-add" class="validation-message"></div>
                    
                    <div class="buttons">
                        <div class="row">
                            <?php if (!empty($item->variants) && count($item->variants) > 1) { ?>
                            <div class="col-sm-8">
                                
                                <select name="variant_id" class="chosen-select select-variant" data-callback="Shop.selectVariant">
                                    <?php foreach ($item->variantsInStock() as $key=>$variant) { ?>
                                        <option value="<?php echo $variant['id']; ?>" data-variant='<?php echo htmlspecialchars( json_encode( array(
                                            'id' => $variant['id'],
                                            'key' => $variant['key'],
                                        	'image' => $variant['image'],
                                            'quantity' => $variant['quantity'],
                                        ) ) ); ?>'><?php echo $variant['attribute_title'] ? $variant['attribute_title'] : $item->title; ?> </option>
                                    <?php } ?>
                                </select>
                            </div>
                            <?php } elseif (count($item->variants) == 1) { ?>
                                <input type="hidden" name="variant_id" value="<?php echo $item->{'variants.0.id'}; ?>" />
                            <?php } ?> 
                        
                            <div class="col-sm-4">
                                <div class="input-group">
                                    <input type="text" class="form-control" value="1" placeholder="Quantity" name="quantity" />
                                    <span class="input-group-addon">
                                        #
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="price-line">
                        <div class="price">$<?php echo $item->price(); ?></div>
                        <button class="btn btn-default custom-button custom-button-inverted">Add to bag</button>

                    </div>
                </form>

            </div>
        </div>
    </div>

</div>

