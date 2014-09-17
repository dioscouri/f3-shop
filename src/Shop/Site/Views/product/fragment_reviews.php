<div class="reviews-container">

    <hr/>
    
    <div class="reviews-header">
        <div class="row">
            <div class="col-md-6">
                <h3 class="reviews-count">
                    # Reviews
                    <div class="reviews-average"><small>xxxx star avg</small></div>
                </h3>                
            </div>
            <div class="col-md-6">
                <?php // Photos in reviews shortcut? ?>
            </div>
        </div>    
    </div>    
    
    <hr/>
    
    <?php // can this user make the review? ?>
    <?php echo $this->renderView('Shop/Site/Views::product/fragment_reviews_create.php'); ?>
    
    <?php // are there reviews to display? ?>
    
    <?php $reviews = array(0, 1); ?>
    <div class="reviews-content">
    <?php foreach ($reviews as $review) { ?>
    
        <div class="review" itemprop="review" itemscope itemtype="http://schema.org/Review">
            <div class="row">
                <div class="col-sm-8 col-md-9">
                
                    <div class="review-metadata">
                        <div class="review-rating" itemprop="reviewRating" itemscope itemtype="http://schema.org/Rating">
                            <meta itemprop="worstRating" content="1" />
                            <span itemprop="ratingValue">4</span>
                            /
                            <span itemprop="bestRating">5</span>
                            stars
                        </div>
                                    
                        <meta itemprop="datePublished" content="<?php echo date('Y-m-d'); ?>">
                        <div class="review-date"><?php echo date('M j, Y'); ?></div>
                        <div class="review-author" itemprop="author">Rafael</span>
                    </div>
                    <div class="review-title" itemprop="name">Title of Review</div>
                    <div class="review-text" itemprop="description">text of review</div>
                    
                </div>
                <div class="col-sm-4 col-md-3">
                    
                    image
                    
                </div>
            </div>

        </div>
    <?php } ?>
    </div>

</div>