<?php $view_link = $SCHEME . '://' . $HOST . $BASE . '/shop/cart/'.(string)$cart->id.'?email=1&user_id='.(string)$user->id.'&idx='.$idx.'&auto_login_token='.$token; ?>
<p><?php echo trim( 'Hi ' . $user->fullName() ); ?>,</p>

<div>
<?php echo $notification['text']['html']; ?>
</div>

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

<p>To complete your purchase, click this link:<br/><a href="<?php echo $view_link; ?>"><?php echo $view_link; ?></a>.</p>

<p>Thanks!</p>