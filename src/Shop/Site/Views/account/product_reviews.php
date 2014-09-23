<div class="container">

	<ol class="breadcrumb">
        <li>
            <a href="./shop/account">My Account</a>
        </li>
        <li class="active">My Reviews</li>
    </ol>

    <?php \Dsc\System::instance()->get('session')->set('shop.product_review.redirect', $this->app->get('PATH')); ?>

    <form action="./shop/account/product-reviews" method="post">
        <div class="form-group">
            <div class="input-group">
                <input type="text" class="form-control" placeholder="Search by order number or product name..." name="filter[keyword]" value="<?php echo $state->get('filter.keyword'); ?>" />
                <span class="input-group-btn">
                    <input class="btn btn-primary" type="submit" onclick="this.form.submit();" value="Search" />
                    <button class="btn btn-danger" type="button" onclick="Dsc.resetFormFilters(this.form);">Reset</button>                
                </span>
            </div>
        </div>
    </form>

    <?php if (empty($paginated->items)) { ?>
        
            <div class="list-group-item">
                No items found.
            </div>
    
    <?php } elseif (!empty($paginated->items)) { ?>
    
    <div class="list-group-item">
        <div class="row">
            <div class="col-sm-10">
                <?php if (!empty($paginated->total_pages) && $paginated->total_pages > 1) { ?>
                    <?php echo $paginated->serve(); ?>
                <?php } ?>
            </div>
            <div class="col-sm-2">
                <div class="pull-right">
                    <span class="pagination">
                        <?php echo (!empty($paginated->total_pages)) ? $paginated->getResultsCounter() : null; ?>
                    </span>
                </div>
            </div>
        </div>
    </div>
    
    <?php foreach ($paginated->items as $key=>$item) { ?>
        <div class="list-group-item">
            <div class="row">
                <div class="col-xs-12 col-sm-5 col-md-5">
                    <div class="row">
                        <div class="col-xs-4 col-sm-3 col-md-3">
                            <figure>
                                <?php if (\Dsc\ArrayHelper::get($item, 'order_item.image')) { ?>
                                <a href="./shop/product/<?php echo \Dsc\ArrayHelper::get($item, 'slug'); ?>">
                                    <img class="img-responsive" src="./asset/thumb/<?php echo \Dsc\ArrayHelper::get($item, 'order_item.image'); ?>" alt="<?php echo \Dsc\ArrayHelper::get($item, 'title'); ?>" >
                                </a>
                                <?php } ?>
                            </figure>                
                        </div>
                        <div class="col-xs-8 col-sm-9 col-md-9">
                            <?php //echo \Dsc\Debug::dump( $item ); ?>
                            <div class="text">
                                <h4>
                                    <a href="./shop/product/<?php echo \Dsc\ArrayHelper::get($item, 'slug'); ?>"><?php echo \Dsc\ArrayHelper::get($item, 'title'); ?></a>
                                    <?php if (\Dsc\ArrayHelper::get($item, 'order_item.attribute_title')) { ?>
                                    <div><small><?php echo \Dsc\ArrayHelper::get($item, 'order_item.attribute_title'); ?></small></div>
                                    <?php } ?>                                            
                                </h4>
                                <div class="details">
                                    <?php if (\Dsc\ArrayHelper::get($item, 'order_item.sku')) { ?>
                                    <p class="detail-line">
                                        SKU: <?php echo \Dsc\ArrayHelper::get($item, 'order_item.sku'); ?>
                                    </p>
                                    <?php } ?>
                                    
                                    <?php if (\Dsc\ArrayHelper::get($item, 'order_item.order_created.time')) { ?>
                                    <p class="detail-line">
                                        <strong>Ordered:</strong> <?php echo date( 'M j, Y', \Dsc\ArrayHelper::get($item, 'order_item.order_created.time') ); ?>
                                    </p>
                                    <?php } ?>
                                </div>
                            </div>                
                        </div>
                    </div>
                </div>
            
                <div class="col-xs-12 col-sm-7 col-md-7">
                    <?php if ($review = \Shop\Models\ProductReviews::hasUserReviewed( $this->auth->getIdentity(), $item)) { ?>
                        <div class="row">
                            <div class="col-sm-8">
                                <input itemprop="ratingValue" class="rating" data-size="xs" data-disabled="true" data-readonly="true" data-show-clear="false" data-show-caption="false" value="<?php echo (int) $review->rating; ?>" >
                            </div>
                            <div class="col-sm-4">
                                <?php 
                                switch ($review->{'publication.status'}) 
                                {
                                    case "published":
                                        echo '<span class="label label-success pull-right">Published</span>';
                                        break;                                    
                                    case "unpublished":
                                        echo '<span class="label label-danger pull-right">Unpublished</span>';
                                        break;
                                    case "draft":
                                        echo '<span class="label label-default pull-right">Under review</span>';
                                        break;
                                } ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <h4 class="review-title" itemprop="name"><?php echo $review->title; ?></h4>
                            <div class="review-text" itemprop="description"><?php echo $review->description; ?></div>
                        </div>
                        
                        <ul class="list-unstyled list-inline">
                            <?php foreach ($review->images as $image) { ?>
                                <li style="max-width: 150px;">
                                <img src="./asset/thumb/<?php echo $image; ?>" class="img-responsive" />
                                </li>
                            <?php } ?>
                        </ul>                                                
                        
                    <?php } else { ?>
                        <?php $this->app->set('item', $item); ?>
                        <?php $this->app->set('review_key', $key); ?>
                        <?php echo $this->renderView('Shop/Site/Views::product/fragment_reviews_create.php'); ?>
                    <?php } ?>
                    
                    
                </div>
            </div>
        </div>
    <?php } ?>
    
    <div class="list-group-item">
        <div class="row">
            <div class="col-sm-10">
                <?php if (!empty($paginated->total_pages) && $paginated->total_pages > 1) { ?>
                    <?php echo $paginated->serve(); ?>
                <?php } ?>
            </div>
            <div class="col-sm-2">
                <div class="pull-right">
                    <span class="pagination">
                        <?php echo (!empty($paginated->total_pages)) ? $paginated->getResultsCounter() : null; ?>
                    </span>
                </div>
            </div>
        </div>
    </div>    
    
    <?php } ?>

</div>