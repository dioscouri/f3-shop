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
                        <span class="price"><?php echo \Shop\Models\Currency::format( \Dsc\ArrayHelper::get($item, 'price') ); ?></span> 
                    </div>
                    
                </td>
                <td>
                    <div class="subtotal"><?php echo \Shop\Models\Currency::format( $cart->calcItemSubtotal( $item ) ); ?></div>
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
                    <td><div class="price"><?php echo \Shop\Models\Currency::format( $cart->subtotal() ); ?></div></td>
                </tr>
                <tr>
                    <td><div class="strong">
                            Shipping:
                        </div></td>
                    <td><div class="price">
                        <?php if (!$shippingMethod = $cart->shippingMethod()) {
                            echo \Shop\Models\Currency::format( $cart->shippingEstimate() );
                            echo ' <small>(est)</small>';
                        } else {
                        	echo \Shop\Models\Currency::format( $shippingMethod->total() );
                        }
                        ?>
                    </div></td>
                </tr>
                <tr>
                    <td><div class="strong">
                            Tax:
                        </div></td>
                    <td><div class="price">
                        <?php if (!$shippingMethod = $cart->shippingMethod()) {
                            echo \Shop\Models\Currency::format( $cart->taxEstimate() );
                            echo ' <small>(est)</small>';
                        } else {
                        	echo \Shop\Models\Currency::format( $cart->taxTotal() );
                        }
                        ?>                    
                    </div></td>
                </tr>
            </tbody>
            <tfoot>
                <td><div class="strong">
                        Total<?php if (!$shippingMethod = $cart->shippingMethod()) { ?> <small>(est)</small> <?php } ?>:
                    </div></td>
                <td><div class="price"><?php echo \Shop\Models\Currency::format( $cart->total() ); ?></div></td>
            </tfoot>
        </table>
    </div>
</div>