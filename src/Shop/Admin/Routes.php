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
        
        $this->addCrudGroup('Regions', 'Region');
        $this->add('/regions/forSelection [ajax]', 'GET|POST', array(
            'controller' => 'Regions',
            'action' => 'forSelection'
        ));
        
        $f3->route('GET /admin/shop/testing/@task', '\Shop\Admin\Controllers\Testing->@task');
        
        $this->addCrudGroup('Coupons', 'Coupon');
        
        $this->addCrudGroup('Tags', 'Tag');
        
        $this->addCrudGroup('GiftCards', 'GiftCard');
        
        $f3->route('GET /admin/shop/uniqueid', function(){
        	echo (string) new \MongoId;
        });
        
    }
}