<h2>
    Checkout <small>Step 2 of 2</small>
</h2>

<form action="./shop/checkout/update" method="post">

    <div id="checkout-shipping-summary" class="well well-sm">
        <p>Summary of everything in previous step.</p>
        <a href="./shop/checkout">Edit</a>
        <p>// TODO Validate that it's all present, and if not, redirect to /shop/checkout</p> 
    </div>

    <div id="checkout-billing-method" class="well well-sm">Multiple payment methods configured?  If so, display select list that displays additional form on change.  If not, just display the form of the one payment method available.</div>

    <div class="input-group form-group">
        <button type="submit" class="btn btn-default custom-button btn-lg">Submit Order</button>
        <?php \Dsc\System::instance()->get('session')->set('site.shop.checkout.redirect', '/shop/checkout/complete'); ?>
    </div>

</form>
