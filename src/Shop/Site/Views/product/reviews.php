<?php foreach ($paginated->items as $key=>$review) { ?>

<div class="review" itemprop="review" itemscope itemtype="http://schema.org/Review">
    <div class="row">
    
        <div class="col-sm-2 col-md-2">
        
            <div class="review-author" itemprop="author"><?php echo $review->user_name; ?></div>
            
            <meta itemprop="datePublished" content="<?php echo date('Y-m-d', $review->{'publication.start.time'} ); ?>">
            <div class="review-date"><small class="help-block"><?php echo date('M j, Y', $review->{'publication.start.time'}); ?></small></div>

        </div>
        <div class="col-sm-6 col-md-7">
        
            <div class="review-metadata">
                <div class="review-rating" itemprop="reviewRating" itemscope itemtype="http://schema.org/Rating">
                    <meta itemprop="worstRating" content="1" />
                    <meta itemprop="bestRating" content="5" />
                    <input itemprop="ratingValue" class="rating" data-size="xs" data-disabled="true" data-readonly="true" data-show-clear="false" data-show-caption="false" value="<?php echo (int) $review->rating; ?>" >                            
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

<hr/>

<?php } ?>