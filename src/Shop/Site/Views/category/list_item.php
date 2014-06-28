<?php $item = $this->item; ?>
<?php $item->url = './shop/product/' . $item->{'slug'}; ?>

<article id="product-<?php echo $item->id; ?>" class="list-item product-<?php echo $item->id; ?>">

    <?php if ($item->{'details.featured_image.slug'}) { ?>
    <div class="product_thumb">
        <div class="product_listimage">
            <a href="<?php echo $item->url; ?>">
                <img class="img-responsive" src="./asset/thumb/<?php echo $item->{'details.featured_image.slug'}; ?>" title="<?php echo $item->{'metadata.title'}; ?>" alt="<?php echo $item->{'metadata.title'}; ?>">
            </a>
        </div>
    </div>
    <?php } ?>
    
    <div class="text">
        <h2><a href="<?php echo $item->url; ?>"><?php echo $item->title; ?></a></h2>
        
        <div class="price">
            <?php if ($item->{'policies.hide_price'}) { ?>
                <p>Call for price.</p>
            <?php } else { ?>
                <?php if (((int) $item->get('prices.list') > 0) && (float) $item->get('prices.list') != (float) $item->price() ) { ?>
                    <span class="list-price"><strike><?php echo \Shop\Models\Currency::format( $item->{'prices.list'} ); ?></strike></span>
                <?php } ?>
                &nbsp;                
                <a href="<?php echo $item->url; ?>">
                    <span class="new-price"><?php echo \Shop\Models\Currency::format( $item->price() ); ?></span>
                </a>
            <?php } ?>        
        </div>
    </div>

</article>