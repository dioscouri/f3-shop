<?php $item->url = '/shop/product/' . $item->{'slug'}; ?>
<?php $images = $item->images(); ?>

<script>
jQuery(document).ready(function(){
   jQuery('.add-to-wishlist').on('click', function(ev){
       ev.preventDefault();
       var el = jQuery(this);
       var variant_id = el.closest('form').find('.variant_id').val();
       console.log('ok, variant: ' + variant_id);
       if (variant_id) {
	        var request = jQuery.ajax({
	            type: 'get', 
	            url: '/shop/wishlist/add?variant_id='+variant_id
	        }).done(function(data){
	            var response = jQuery.parseJSON( JSON.stringify(data), false);
	            if (response.result) {
	                el.replaceWith("<a href='javascript:void(0);'><i class='glyphicon glyphicon-heart'></i> In your wishlist</a>");
	            }
	        });
       } 
   }); 
});
</script>

<div class="container">
    <div class="row">
        <div class="col-sm-6">
            <div class="product-slider-small">
                <?php if (count($images) > 1) { ?>       
                <ul class="zoom-thumbs">
                    <?php foreach ($images as $key=>$image) { ?>
                    <li id="<?php echo $image; ?>">
                        <a rel="{gallery: 'zoom-gallery', smallimage: '/asset/thumb/<?php echo $image; ?>', largeimage: '/asset/<?php echo $image; ?>'}" href="javascript:void(0);">
                            <img src="/asset/thumb/<?php echo $image; ?>" title="<?php echo htmlspecialchars_decode( $item->title ); ?>"> 
                        </a>                        
                    </li>
                    <?php } ?>
                </ul>
                <?php } ?>
            </div>
            
            <div class="product-image product-image-big">
                <a class="zoom" rel="zoom-gallery" href="/asset/<?php echo $item->{'featured_image.slug'}; ?>" title="<?php echo htmlspecialchars_decode( $item->title ); ?>" data-large-url="/asset/<?php echo $item->{'featured_image.slug'}; ?>">
    	            <img class="zoomable" src="/asset/thumb/<?php echo $item->{'featured_image.slug'}; ?>" title="<?php echo htmlspecialchars_decode( $item->title ); ?>" />
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
                <form action="/shop/cart/add" method="post">
                    <div id="validation-cart-add" class="validation-message"></div>
                    
                    <div class="buttons">
                        <div class="row">
                            <?php if (!empty($item->variants) && count($item->variants) > 1) { ?>
                            <div class="col-sm-8">
                                
                                <select name="variant_id" class="chosen-select select-variant variant_id" data-callback="Shop.selectVariant">
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
                                <input type="hidden" name="variant_id" value="<?php echo $item->{'variants.0.id'}; ?>" class="variant_id" />
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
                        
                        <div class="small-buttons">
                            <div class="add-to-wishlist-container">
                                <?php 
                                if (!\Shop\Models\Wishlists::hasAddedProduct($item->{'id'}, (string) $this->auth->getIdentity()->id))
                                {
                                    ?>
                                    <a class='add-to-wishlist' href='javascript:void(0);'><i class='glyphicon glyphicon-heart'></i> Add to wishlist</a>
                                    <?php
                                } else {
                                	?>
                                	<a href='javascript:void(0);'><i class='glyphicon glyphicon-heart'></i> In your wishlist</a>
                                	<?php
                                }                                
                                ?>                                
                            </div>
                        </div>

                    </div>
                </form>

            </div>
        </div>
    </div>

</div>

<script>
jQuery(document).ready(function(){
	var select = jQuery('select.select-variant');
	if (select.length) {
		var selected = select.find("option:selected");
        var variant = jQuery.parseJSON( selected.attr('data-variant') );
        if (variant.image) {
        	Shop.selectVariant(variant);
        }		
	}
});
</script>

