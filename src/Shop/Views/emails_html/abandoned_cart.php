<?php $view_link = $SCHEME . '://' . $HOST . $BASE . '/shop/cart?email=1'; ?>

Hi <?php echo $user->fullName(); ?>

<?php echo $notification['html']; ?>

<h3>Your cart contains</h3>
<table style="width: 100%;">
    <?php foreach ($cart->items as $item) { ?>
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
                <div>
                    <small>
                    <span class="quantity"><?php echo $quantity = \Dsc\ArrayHelper::get($item, 'quantity'); ?></span>
                    x
                    <span class="price"><?php echo \Shop\Models\Currency::format( $price = \Dsc\ArrayHelper::get($item, 'price') ); ?></span>
                    </small> 
                </div>
            </h4>
        </td>
        <td style="vertical-align: top; text-align: right;">
            <h4>
                <?php echo \Shop\Models\Currency::format( $quantity * $price ); ?>
            </h4>
        </td>
    </tr>        
    <?php } ?>
</table>

If you want to see your cart, click on the following <a href="<?php echo $view_link; ?>">link</a>.