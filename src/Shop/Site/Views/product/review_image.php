<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">
        <span aria-hidden="true">&times;</span>
        <span class="sr-only">Close</span>
    </button>
    <button id="review-modal-prev" type="button" class="btn btn-default review-modal-button" <?php if (is_null($prev)) { echo 'disabled'; } ?> data-value="<?php echo $prev; ?>">Prev</button>
    <button id="review-modal-next" type="button" class="btn btn-primary review-modal-button" <?php if (is_null($next)) { echo 'disabled'; } ?> data-value="<?php echo $next; ?>">Next</button>
</div>
<div class="modal-body">
    <?php if (!empty($review->images[0])) { ?>
        <img class="img-responsive" src="./asset/thumb/<?php echo $review->images[0]; ?>" />
    <?php } ?>
</div>
<div class="modal-footer" style="text-align: left;">
    <div class="row">
        <div class="col-xs-6">
            <div class="review-metadata">
                <div class="review-rating" itemprop="reviewRating" itemscope itemtype="http://schema.org/Rating">
                    <input itemprop="ratingValue" class="rating" data-size="xs" data-disabled="true" data-readonly="true" data-show-clear="false" data-show-caption="false" value="<?php echo (int) $review->rating; ?>" >                            
                </div>
            </div>        
        </div>
        <div class="col-xs-6">
            <div class="pull-right" style="text-align: right">
                <div class="review-author" itemprop="author"><?php echo $review->user_name; ?></div>
                <div class="review-date"><small class="help-block"><?php echo date('M j, Y', $review->{'publication.start.time'}); ?></small></div>
            </div>        
        </div>
    </div>
    <h4 class="modal-title"><?php echo $review->title; ?></h4>
    <?php echo $review->description; ?>
</div>

<script>
jQuery(document).ready(function(){
    jQuery('.rating').rating();
    
    jQuery('.review-modal-button').on('click', function (e) {
        var el = jQuery(this);
        if (el.attr('disabled')) {
            return;
        } 
        
        jQuery('.modal-content').load('./shop/product/<?php echo $product->slug; ?>/reviews/images/' + el.attr('data-value'), function(result){
    	    
    	});                            
    });                        
});
</script>