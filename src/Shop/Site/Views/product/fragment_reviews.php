<a name="reviews">&nbsp;</a>
<div class="reviews-container">
    <hr />
    <div class="reviews-header">
        <div class="row">
            <div class="col-sm-6">
                <div itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">
                    <h3 class="reviews-count">
                        Reviews (
                        <span itemprop="reviewCount"><?php echo \Shop\Models\ProductReviews::forProduct($item, 'count'); ?></span>
                        )
                    </h3>
                    <div class="reviews-average">
                        <?php $avg_rating = \Shop\Models\ProductReviews::forProduct($item, 'avg_rating'); ?>
                        <meta itemprop="ratingValue" content="<?php echo $avg_rating; ?>">
                        <input class="rating" data-size="sm" data-disabled="true" data-readonly="true" data-show-clear="false" data-show-caption="false" value="<?php echo $avg_rating; ?>">
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="pull-right">
                    <?php // Photos in reviews shortcut? ?>
                    <?php if ($image_count = \Shop\Models\ProductReviews::forProduct( $item, 'image_count' )) { ?>
                    
                    <!-- Button to trigger modal -->
                    <button class="margin-top btn btn-default btn-lg" data-toggle="modal" data-target="#product-review-images-modal">View Customer Photos (<?php echo $image_count; ?>)</button>
                    
                    <!-- Modal -->
                    <div id="product-review-images-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-body">
                                    <p>Loading...</p>
                                    <div class="progress">
                                        <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%;">
                                            <span class="sr-only">Loading...</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <script>
                    jQuery(document).ready(function(){
                        jQuery('#product-review-images-modal').on('shown.bs.modal', function (e) {
                            $('.modal-content').load('./shop/product/<?php echo $item->slug; ?>/reviews/images/0', function(result){
                        	    
                        	});                            
                        });                        
                    });
                    </script>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <hr />
    
    <?php // can this user make the review? ?>
    <?php 
    if (\Shop\Models\ProductReviews::canUserReview($this->auth->getIdentity(), $item) === true) {
        $this->app->set('review_key', 0); 
        echo $this->renderView('Shop/Site/Views::product/fragment_reviews_create.php');
        ?><hr /><?php
    } 
    ?>
    
    <?php // are there reviews to display? ?>
    <?php if ($paginated = \Shop\Models\ProductReviews::forProduct($item)) { ?>
    
    <div class="reviews-content">
        <?php foreach ($paginated->items as $key=>$review) { ?>
    
        <div class="review" itemprop="review" itemscope itemtype="http://schema.org/Review">
            <div class="row">
                <div class="col-sm-2 col-md-2">
                    <div class="review-author" itemprop="author"><?php echo $review->user_name; ?></div>
                    <meta itemprop="datePublished" content="<?php echo date('Y-m-d', $review->{'publication.start.time'} ); ?>">
                    <div class="review-date">
                        <small class="help-block"><?php echo date('M j, Y', $review->{'publication.start.time'}); ?></small>
                    </div>
                </div>
                <div class="col-sm-6 col-md-7">
                    <div class="review-metadata">
                        <div class="review-rating" itemprop="reviewRating" itemscope itemtype="http://schema.org/Rating">
                            <meta itemprop="worstRating" content="1" />
                            <meta itemprop="bestRating" content="5" />
                            <input itemprop="ratingValue" class="rating" data-size="xs" data-disabled="true" data-readonly="true" data-show-clear="false" data-show-caption="false" value="<?php echo (int) $review->rating; ?>">
                        </div>
                    </div>
                    <h4 class="review-title" itemprop="name"><?php echo $review->title; ?></h4>
                    <div class="review-text" itemprop="description"><?php echo $review->description; ?></div>
                </div>
                <div class="col-sm-4 col-md-3">
                    <?php if (!empty($review->images[0])) { ?>
                        <img class="img-responsive" src="./asset/thumb/<?php echo $review->images[0]; ?>" />
                    <?php } ?>
                </div>
            </div>
        </div>
        <hr />
        
        <?php } ?>
            
    </div>
    
    <?php if (!empty($paginated->total_pages) && $paginated->total_pages > 1) { ?>
    <div class="reviews-pagination">
        <a id="load-more-reviews" class="btn btn-default btn-block">Load More</a>
    </div>    
    <?php } ?>
    
    <?php } ?>

</div>

<script>
jQuery(document).ready(function(){
    window.reviews_page = 2;
    
	function ShopGetReviews(page) {
        var request = jQuery.ajax({
            type: 'get', 
            url: './shop/product/<?php echo $item->slug; ?>/reviews/page/' + page
        }).done(function(data){
            var lr = jQuery.parseJSON( JSON.stringify(data), false);
            if (lr.html) {
                jQuery('.reviews-content').append(lr.html);
                jQuery('.rating').rating();
            }
            if (lr.next_page) {
            	window.reviews_page = lr.next_page;
            } else {
                jQuery('#load-more-reviews').off('click.reviews').text('No more reviews to load').addClass('disabled');
            }
        });				
	}

	jQuery('#load-more-reviews').on('click.reviews', function(){
	    ShopGetReviews(window.reviews_page);
	});

	jQuery('.review-text').readmore({
        moreLink: '<a href="#" class="btn btn-link">Read More</a>',
        lessLink: '<a href="#" class="btn btn-link">Read Less</a>',
        speed: 750,
	});
});
</script>