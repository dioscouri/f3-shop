<div class="table-responsive checkout-cart">
    <table class="table">
        <thead>
            <tr>
                <th colspan="3">Items</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($cart->items as $key=>$item) { ?>
            <tr>
                <td class="checkout-cart-image">
                    <figure>
                        <?php if (\Dsc\ArrayHelper::get($item, 'image')) { ?>
                        <img class="img-responsive" src="./asset/thumb/<?php echo \Dsc\ArrayHelper::get($item, 'image'); ?>" alt="" />
                        <?php } ?>
                    </figure>
                </td>
                <td class="checkout-cart-product">
                    <h4>
                        <?php echo \Dsc\ArrayHelper::get($item, 'product.title'); ?>
                        <?php if (\Dsc\ArrayHelper::get($item, 'attribute_title')) { ?>
                        <div>
                            <small><?php echo \Dsc\ArrayHelper::get($item, 'attribute_title'); ?></small>
                        </div>
                        <?php } ?>                        
                    </h4>
                    <div class="details">

                    </div>
                    <div>
                        <span class="quantity"><?php echo \Dsc\ArrayHelper::get($item, 'quantity'); ?></span>
                        x
                        <span class="price">$<?php echo \Dsc\ArrayHelper::get($item, 'price'); ?></span> 
                    </div>
                    
                </td>
                <td>
                    <div class="subtotal">$<?php echo $cart->calcItemSubtotal( $item ); ?></div>
                </td>
            </tr>
        <?php } ?>
        
        </tbody>
    </table>
</div>

<div class="total-box">
    <div class="table-responsive">
        <table class="table">
            <tbody>
                <tr>
                    <td><div class="strong">Subtotal:</div></td>
                    <td><div class="price">$<?php echo $cart->subtotal(); ?></div></td>
                </tr>
                <tr>
                    <td><div class="strong">
                            Shipping <small>(est)</small>:
                        </div></td>
                    <td><div class="price">$<?php echo $cart->shipping_estimate(); ?></div></td>
                </tr>
                <tr>
                    <td><div class="strong">
                            Tax <small>(est)</small>:
                        </div></td>
                    <td><div class="price">$<?php echo $cart->tax_estimate(); ?></div></td>
                </tr>
            </tbody>
            <tfoot>
                <td><div class="strong">
                        Total <small>(est)</small>:
                    </div></td>
                <td><div class="price">$<?php echo $cart->total(); ?></div></td>
            </tfoot>
        </table>
    </div>
</div>