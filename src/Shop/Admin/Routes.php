<?php
namespace Shop\Admin;

class Routes extends \Dsc\Routes\Group
{

    public function initialize()
    {
        $f3 = \Base::instance();
        
        $this->setDefaults(array(
            'namespace' => '\Shop\Admin\Controllers',
            'url_prefix' => '/admin/shop'
        ));
        
        $this->addSettingsRoutes();
        
        $this->addCrudGroup('Orders', 'Order');
        
        $this->add('/order/fulfill/@id', 'GET', array(
            'controller' => 'Order',
            'action' => 'fulfill'
        ));
        
        $this->add('/order/close/@id', 'GET', array(
            'controller' => 'Order',
            'action' => 'close'
        ));
        
        $this->add('/order/cancel/@id', 'GET', array(
            'controller' => 'Order',
            'action' => 'cancel'
        ));
        
        $this->add('/order/open/@id', 'GET', array(
            'controller' => 'Order',
            'action' => 'open'
        ));
        
        $this->add('/order/fulfill-giftcards/@id', 'GET', array(
            'controller' => 'Order',
            'action' => 'fulfillGiftCards'
        ));
        
        $this->addCrudGroup('Products', 'Product');
        $this->add('/products/forSelection [ajax]', 'GET|POST', array(
            'controller' => 'Products',
            'action' => 'forSelection'
        ));
        
        $this->addCrudGroup('Categories', 'Category', array(
            'datatable_links' => true,
            'get_parent_link' => true
        ));
        
        $this->addCrudGroup('Manufacturers', 'Manufacturer', array(
            'datatable_links' => true,
            'get_parent_link' => true
        ));
        
        $this->addCrudGroup('Collections', 'Collection', array(
            'datatable_links' => true,
            'get_parent_link' => true
        ));
        $this->add('/collections/forSelection [ajax]', 'GET|POST', array(
        		'controller' => 'Collections',
        		'action' => 'forSelection'
        ));
        
        
        $this->add('/categories/checkboxes [ajax]', array(
            'GET',
            'POST'
        ), array(
            'controller' => 'Categories',
            'action' => 'getCheckboxes'
        ));
        
        $this->add('/manufacturers/checkboxes [ajax]', array(
            'GET',
            'POST'
        ), array(
            'controller' => 'Manufacturers',
            'action' => 'getCheckboxes'
        ));
        
        $this->addCrudGroup('Countries', 'Country');
        $this->add('/countries/forSelection [ajax]', 'GET|POST', array(
            'controller' => 'Countries',
            'action' => 'forSelection'
        ));

        $this->add('/countries/moveUp/@id', 'GET', array(
        		'controller' => 'Countries',
        		'action' => 'MoveUp'
        ));

        $this->add('/countries/moveDown/@id', 'GET', array(
        		'controller' => 'Countries',
        		'action' => 'MoveDown'
        ));

		$this->addChangeStateListRoutes( 'Countries', '/countries' );
        
        $this->addCrudGroup('Regions', 'Region');
        $this->add('/regions/forSelection [ajax]', 'GET|POST', array(
            'controller' => 'Regions',
            'action' => 'forSelection'
        ));
        
        $this->addCrudGroup('Coupons', 'Coupon');
        
        $this->addCrudGroup('Tags', 'Tag');
        
        $this->addCrudGroup('GiftCards', 'GiftCard');
            
        $this->addCrudGroup('OrderedGiftCards', 'OrderedGiftCard', array(
        	'url_prefix' => '/orders/giftcards'
        ), array(
            'url_prefix' => '/orders/giftcard'
        ));
        
        $f3->route('GET /admin/shop/uniqueid', function(){
        	echo (string) new \MongoId;
        });
        
        $this->add('/customers/forSelection [ajax]', 'GET|POST', array(
            'controller' => 'Customers',
            'action' => 'forSelection'
        ));
        
        $this->add('/coupons/forSelection [ajax]', 'GET|POST', array(
            'controller' => 'Coupons',
            'action' => 'forSelection'
        ));
        
        $this->addCrudGroup('Credits', 'Credit');

        $this->add('/credit/issue/@id', 'GET', array(
            'controller' => 'Credit',
            'action' => 'issue'
        ));
        
        $this->add('/credit/revoke/@id', 'GET', array(
            'controller' => 'Credit',
            'action' => 'revoke'
        ));
        
        $this->add('/collection/@id/products', 'GET|POST', array(
            'controller' => 'Collection',
            'action' => 'products'
        ));
        
        $this->add('/collection/@id/products/page/@page', 'GET', array(
            'controller' => 'Collection',
            'action' => 'products'
        ));
        
        $this->add('/collection/@id/products/order', 'POST', array(
            'controller' => 'Collection',
            'action' => 'saveProductsOrder'
        ));

        $this->add('/reports', 'GET', array(
            'controller' => 'Reports',
            'action' => 'index'
        ));
        
        $this->add('/reports/@slug', 'GET|POST', array(
            'controller' => 'Reports',
            'action' => 'read'
        ));
        
        $this->add('/reports/@slug/page/@page', 'GET|POST', array(
            'controller' => 'Reports',
            'action' => 'read'
        ));
        
        $this->addCrudGroup('Carts', 'Cart');
    }
}