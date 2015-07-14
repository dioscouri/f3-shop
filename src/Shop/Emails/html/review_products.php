<p><?php echo trim( 'Hi ' . $user->fullName() ); ?>,</p>

<p>Thank you for your recent order!</p>
<p>Please take a moment to let us know what you think of your purchases.</p>

<hr/> 

<table style="width: 100%;">
    <?php foreach ($products as $item) { ?>
    <tr>
        <td style="width: 75px;">
            <?php if (\Dsc\ArrayHelper::get($item, 'image')) { ?>
            <img style="width: 100%;" src="<?php echo $SCHEME . '://' . $HOST . $BASE; ?>/asset/thumb/<?php echo \Dsc\ArrayHelper::get($item, 'image'); ?>" alt="" />
            <?php } ?>
        </td>
        <td style="vertical-align: top;">
            <h4>
                <?php echo \Dsc\ArrayHelper::get($item, 'product.title'); ?>
                <?php if (\Dsc\ArrayHelper::get($item, 'attribute_title')) { ?>
                <div>
                    <small><?php echo \Dsc\ArrayHelper::get($item, 'attribute_title'); ?></small>
                </div>
                <?php } ?>
            </h4>
        </td>
        <td style="vertical-align: middle;">
            <span style="border: thin solid #ccc; padding: 10px;">
            <a class="btn btn-link" href="<?php echo $SCHEME . '://' . $HOST . $BASE; ?>/shop/account/product-reviews?filter[keyword]=<?php echo \Dsc\ArrayHelper::get($item, 'product.title'); ?>">Write a review</a>
            </span>
        </td>
    </tr>        
    <?php } ?>
</table>

<hr/>

<p>Check out <a class="btn btn-link" href="<?php echo $SCHEME . '://' . $HOST . $BASE; ?>/shop/account/product-reviews?filter[keyword]="><?php echo $SCHEME . '://' . $HOST . $BASE; ?>/shop/account/product-reviews</a> to find past purchases to review.</p>

<p>Thanks!</p>