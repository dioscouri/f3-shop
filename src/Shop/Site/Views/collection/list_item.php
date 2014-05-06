<?php $item = $this->item; ?>

<article id="product-<?php echo $item->id; ?>" class="list-item product-<?php echo $item->id; ?>">

    <?php if ($item->{'featured_image.slug'}) { ?>
    <div class="product_thumb dsc-wrap">
        <div class="dsc-wrap product_listimage">
            <a href="<?php echo $item->_url; ?>">
                <img src="./asset/thumb/<?php echo $item->{'featured_image.slug'}; ?>" title="<?php echo $item->{'title'}; ?>" alt="<?php echo $item->{'title'}; ?>">
            </a>
        </div>
    </div>
    <?php } ?>
    
    <div class="dsc-wrap product-info">
        <h3 class="dsc-wrap product_name">
            <a href="<?php echo $item->_url; ?>"> <?php echo $item->{'title'}; ?> </a>
        </h3>    

        <div class="dsc-wrap product-price-wrapper">
            <span id="product-price-<?php echo $item->id; ?>" class="product-price">[cur][price]</span>
        </div>
    </div>

</article>