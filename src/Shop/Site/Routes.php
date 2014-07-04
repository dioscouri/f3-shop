<?php
namespace Shop\Site;

/**
 * Group class is used to keep track of a group of routes with similar aspects (the same controller, the same f3-app and etc)
 */
class Routes extends \Dsc\Routes\Group
{

    /**
     * Initializes all routes for this group
     * NOTE: This method should be overriden by every group
     */
    public function initialize()
    {
        $f3 = \Base::instance();
        
        $this->setDefaults( array(
            'namespace' => '\Shop\Site\Controllers',
            'url_prefix' => '/shop' 
        ) );
        
        $this->add( '', 'GET', array(
            'controller' => 'Home',
            'action' => 'index' 
        ) );
        
        $this->add( '/page/@page', 'GET', array(
            'controller' => 'Home',
            'action' => 'index' 
        ) );
        
        $this->add( '/product/@slug', 'GET', array(
            'controller' => 'Product',
            'action' => 'read' 
        ) );
        $this->add( '/category/*', 'GET|POST', array(
            'controller' => 'Category',
            'action' => 'index' 
        ) );
        
        $this->add( '/category/*/page/@page', 'GET|POST', array(
            'controller' => 'Category',
            'action' => 'index' 
        ) );        
        
        $this->add( '/collection/@slug', 'GET|POST', array(
            'controller' => 'Collection',
            'action' => 'index' 
        ) );
        
        $this->add( '/collection/@slug/page/@page', 'GET|POST', array(
            'controller' => 'Collection',
            'action' => 'index' 
        ) );
        
        $this->add( '/cart', 'GET', array(
            'controller' => 'Cart',
            'action' => 'read' 
        ) );
        
        $this->add( '/cart/add', 'POST', array(
            'controller' => 'Cart',
            'action' => 'add' 
        ) );
        
        $this->add( '/cart/remove/@cartitem_hash', 'GET|POST', array(
            'controller' => 'Cart',
            'action' => 'remove' 
        ) );
        
        $this->add( '/cart/updateQuantities', 'POST', array(
            'controller' => 'Cart',
            'action' => 'updateQuantities' 
        ) );
        
        $this->add( '/cart/addCoupon', 'POST', array(
            'controller' => 'Cart',
            'action' => 'addCoupon'
        ) );        
        
        $this->add( '/cart/removeCoupon/@code', 'GET|POST', array(
            'controller' => 'Cart',
            'action' => 'removeCoupon'
        ) );
        
        $this->add( '/cart/addGiftCard', 'POST', array(
            'controller' => 'Cart',
            'action' => 'addGiftCard'
        ) );
        
        $this->add( '/cart/removeGiftCard/@code', 'GET|POST', array(
            'controller' => 'Cart',
            'action' => 'removeGiftCard'
        ) );
        
        $this->add( '/wishlists', 'GET|POST', array(
            'controller' => 'Wishlist',
            'action' => 'index' 
        ) );
        
        $this->add( '/wishlists/page/@page', 'GET|POST', array(
            'controller' => 'Wishlist',
            'action' => 'index' 
        ) );
        
        $this->add( '/wishlist', 'GET', array(
            'controller' => 'Wishlist',
            'action' => 'primary' 
        ) );
        
        $this->add( '/wishlist/@id', 'GET', array(
            'controller' => 'Wishlist',
            'action' => 'read' 
        ) );
        
        $this->add( '/wishlist/@id/cart/@hash', 'GET', array(
            'controller' => 'Wishlist',
            'action' => 'moveToCart' 
        ) );
        
        $this->add( '/wishlist/add', 'GET|POST', array(
            'controller' => 'Wishlist',
            'action' => 'add' 
        ) );
        
        $this->add( '/wishlist/added/@variant_id [ajax]', 'GET', array(
            'controller' => 'Wishlist',
            'action' => 'added' 
        ) );
        
        $this->add( '/wishlist/remove/@wishlistitem_hash', 'GET|POST', array(
            'controller' => 'Wishlist',
            'action' => 'remove' 
        ) );
        
        $this->add( '/checkout', 'GET', array(
            'controller' => 'Checkout',
            'action' => 'index' 
        ) );
        
        $this->add( '/checkout/register', 'POST', array(
            'controller' => 'Checkout',
            'action' => 'register'
        ) );
        
        $this->add( '/checkout/payment', 'GET', array(
            'controller' => 'Checkout',
            'action' => 'payment' 
        ) );
        
        $this->add( '/checkout/shipping-methods [ajax]', 'GET', array(
            'controller' => 'Checkout',
            'action' => 'shippingMethods' 
        ) );
        
        $this->add( '/checkout/payment-methods [ajax]', 'GET', array(
            'controller' => 'Checkout',
            'action' => 'paymentMethods' 
        ) );
        
        $this->add( '/checkout/update', 'POST', array(
            'controller' => 'Checkout',
            'action' => 'update' 
        ) );
        
        $this->add( '/checkout/submit', 'POST', array(
            'controller' => 'Checkout',
            'action' => 'submit' 
        ) );
        
        $this->add( '/checkout/confirmation', 'GET', array(
            'controller' => 'Checkout',
            'action' => 'confirmation' 
        ) );
        
        $this->add( '/address/countries [ajax]', 'GET', array(
            'controller' => 'Address',
            'action' => 'countries' 
        ) );
        
        $this->add( '/address/regions/@country_isocode_2 [ajax]', 'GET', array(
            'controller' => 'Address',
            'action' => 'regions' 
        ) );
        
        $this->add( '/address/validate [ajax]', 'GET|POST', array(
            'controller' => 'Address',
            'action' => 'validate' 
        ) );
        
        // $this->add( '/checkout/gateway/@gateway_id/authorize', 'GET', array(
        // 'controller' => 'Gateway',
        // 'action' => 'getCreateAuthorization'
        // ) );
        
        // $this->add( '/checkout/gateway/@gateway_id/authorize', 'POST', array(
        // 'controller' => 'Gateway',
        // 'action' => 'submitAuthorization'
        // ) );
        
        // $this->add( '/checkout/gateway/@gateway_id/capture', 'GET', array(
        // 'controller' => 'Gateway',
        // 'action' => 'getCreateCapture'
        // ) );
        
        // $this->add( '/checkout/gateway/@gateway_id/capture', 'POST', array(
        // 'controller' => 'Gateway',
        // 'action' => 'submitCapture'
        // ) );
        
        // $this->add( '/checkout/gateway/@gateway_id/purchase', 'GET', array(
        // 'controller' => 'Gateway',
        // 'action' => 'getCreatePurchase'
        // ) );
        
        // $this->add( '/checkout/gateway/@gateway_id/purchase', 'POST', array(
        // 'controller' => 'Gateway',
        // 'action' => 'submitPurchase'
        // ) );
        
        // $this->add( '/checkout/gateway/@gateway_id/completePurchase', 'GET|POST', array(
        // 'controller' => 'Gateway',
        // 'action' => 'completePurchase'
        // ) );
        
        // $this->add( '/checkout/gateway/@gateway_id/create-card', 'GET', array(
        // 'controller' => 'Gateway',
        // 'action' => 'getCreateCard'
        // ) );
        
        // $this->add( '/checkout/gateway/@gateway_id/create-card', 'POST', array(
        // 'controller' => 'Gateway',
        // 'action' => 'submitCreateCard'
        // ) );
        
        // $this->add( '/checkout/gateway/@gateway_id/update-card', 'GET', array(
        // 'controller' => 'Gateway',
        // 'action' => 'getUpdateCard'
        // ) );
        
        // $this->add( '/checkout/gateway/@gateway_id/update-card', 'POST', array(
        // 'controller' => 'Gateway',
        // 'action' => 'submitUpdateCard'
        // ) );
        
        // $this->add( '/checkout/gateway/@gateway_id/delete-card', 'GET', array(
        // 'controller' => 'Gateway',
        // 'action' => 'getDeleteCard'
        // ) );
        
        // $this->add( '/checkout/gateway/@gateway_id/delete-card', 'POST', array(
        // 'controller' => 'Gateway',
        // 'action' => 'submitDeleteCard'
        // ) );
        
        $this->add( '/orders', 'GET|POST', array(
            'controller' => 'Order',
            'action' => 'index' 
        ) );
        
        $this->add( '/orders/page/@page', 'GET', array(
            'controller' => 'Order',
            'action' => 'index' 
        ) );
        
        $this->add( '/order/@id', 'GET', array(
            'controller' => 'Order',
            'action' => 'read' 
        ) );
        
        $f3->route( 'GET /shop/order/print/@id', function ( $f3 )
        {
            $f3->set( 'print', true );
            (new \Shop\Site\Controllers\Order())->read();
        } );
        
        $this->add( '/account', 'GET', array(
            'controller' => 'Account',
            'action' => 'index' 
        ) );
        
        $this->add( '/account/addresses', 'GET|POST', array(
            'controller' => 'Address',
            'action' => 'index'
        ) );

        $this->add( '/account/addresses/page/@page', 'GET', array(
            'controller' => 'Address',
            'action' => 'index'
        ) );
        
        $this->addCrudItem('Address', array(
            'namespace' => '\Shop\Site\Controllers',
            'url_prefix' => '/account/addresses'
        ));
        
        $this->add( '/account/address/setprimarybilling/@id', 'GET', array(
            'controller' => 'Address',
            'action' => 'setPrimaryBilling'
        ) );

        $this->add( '/account/address/setprimaryshipping/@id', 'GET', array(
            'controller' => 'Address',
            'action' => 'setPrimaryShipping'
        ) );
        
        $this->add( '/giftcard/@id/@token', 'GET', array(
            'controller' => 'OrderedGiftCard',
            'action' => 'read'
        ) );
        
        $f3->route( 'GET /shop/giftcard/print/@id/@token', function ( $f3 )
        {
            $f3->set( 'print', true );
            (new \Shop\Site\Controllers\OrderedGiftCard())->read();
        } );        

        $this->add( '/giftcard/email/@id/@token', 'POST', array(
            'controller' => 'OrderedGiftCard',
            'action' => 'email'
        ) );
        
        $this->add( '/customer/check-campaigns', 'GET', array(
            'controller' => 'Customer',
            'action' => 'checkCampaigns'
        ) );
        
        $this->add( '/google-merchant/products.xml', 'GET', array(
            'controller' => 'GoogleMerchant',
            'action' => 'productsXml'
        ) );
    }
}