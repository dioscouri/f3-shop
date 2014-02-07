<?php 
$f3 = \Base::instance();
$global_app_name = $f3->get('APP_NAME');

switch ($global_app_name) 
{
    case "admin":
        // register event listener
        \Dsc\System::instance()->getDispatcher()->addListener(\Shop\Listener::instance());

        // register the modules path
        \Modules\Factory::registerPath( $f3->get('PATH_ROOT') . "vendor/dioscouri/f3-shop/src/Shop/Modules/" );
        
        // register all the routes
        $f3->route('GET|POST /admin/shop/products', '\Shop\Admin\Controllers\Products->display');
        $f3->route('GET|POST /admin/shop/products/page/@page', '\Shop\Admin\Controllers\Products->display');
        $f3->route('GET|POST /admin/shop/products/delete', '\Shop\Admin\Controllers\Products->delete');
        
        $f3->route('GET /admin/shop/product/create', '\Shop\Admin\Controllers\Product->create');
        $f3->route('POST /admin/shop/product/add', '\Shop\Admin\Controllers\Product->add');
        $f3->route('GET /admin/shop/product/read/@id', '\Shop\Admin\Controllers\Product->read');
        $f3->route('GET /admin/shop/product/edit/@id', '\Shop\Admin\Controllers\Product->edit');
        $f3->route('POST /admin/shop/product/update/@id', '\Shop\Admin\Controllers\Product->update');
        $f3->route('GET|DELETE /admin/shop/product/delete/@id', '\Shop\Admin\Controllers\Product->delete');
        
        $f3->route('GET /admin/shop/categories [ajax]','\Shop\Admin\Controllers\Categories->getDatatable');
        $f3->route('GET /admin/shop/categories/all [ajax]','\Shop\Admin\Controllers\Categories->getAll');
        $f3->route('GET|POST /admin/shop/categories/checkboxes [ajax]','\Shop\Admin\Controllers\Categories->getCheckboxes');
        
        $f3->route('GET|POST /admin/shop/categories', '\Shop\Admin\Controllers\Categories->display');
        $f3->route('GET|POST /admin/shop/categories/page/@page', '\Shop\Admin\Controllers\Categories->display');
        $f3->route('GET|POST /admin/shop/categories/delete', '\Shop\Admin\Controllers\Categories->delete');
        
        $f3->route('GET /admin/shop/category/create', '\Shop\Admin\Controllers\Category->create');
        $f3->route('POST /admin/shop/category/add', '\Shop\Admin\Controllers\Category->add');
        $f3->route('GET /admin/shop/category/read/@id', '\Shop\Admin\Controllers\Category->read');
        $f3->route('GET /admin/shop/category/edit/@id', '\Shop\Admin\Controllers\Category->edit');
        $f3->route('POST /admin/shop/category/update/@id', '\Shop\Admin\Controllers\Category->update');
        $f3->route('GET|DELETE /admin/shop/category/delete/@id', '\Shop\Admin\Controllers\Category->delete');
        
        $f3->route('GET|POST /admin/shop/manufacturers', '\Shop\Admin\Controllers\Manufacturers->display');
        $f3->route('GET|POST /admin/shop/manufacturers/page/@page', '\Shop\Admin\Controllers\Manufacturers->display');
        $f3->route('GET|POST /admin/shop/manufacturers/delete', '\Shop\Admin\Controllers\Manufacturers->delete');
        
        $f3->route('GET /admin/shop/manufacturer/create', '\Shop\Admin\Controllers\Manufacturer->create');
        $f3->route('POST /admin/shop/manufacturer/add', '\Shop\Admin\Controllers\Manufacturer->add');
        $f3->route('GET /admin/shop/manufacturer/read/@id', '\Shop\Admin\Controllers\Manufacturer->read');
        $f3->route('GET /admin/shop/manufacturer/edit/@id', '\Shop\Admin\Controllers\Manufacturer->edit');
        $f3->route('POST /admin/shop/manufacturer/update/@id', '\Shop\Admin\Controllers\Manufacturer->update');
        $f3->route('GET|DELETE /admin/shop/manufacturer/delete/@id', '\Shop\Admin\Controllers\Manufacturer->delete');

        $f3->route('GET|POST /admin/shop/assets', '\Shop\Admin\Controllers\Assets->display');
        $f3->route('GET|POST /admin/shop/assets/page/@page', '\Shop\Admin\Controllers\Assets->display');
        $f3->route('GET|POST /admin/shop/assets/delete', '\Shop\Admin\Controllers\Assets->delete');
        
        // append this app's UI folder to the path
        $ui = $f3->get('UI');
        $ui .= ";" . $f3->get('PATH_ROOT') . "vendor/dioscouri/f3-shop/src/Shop/Admin/Views/";
        $f3->set('UI', $ui);
        
        // TODO set some app-specific settings, if desired
                
        break;
    case "site":
        // TODO register all the routes
        $f3->route('GET /shop/product/@slug', '\Shop\Site\Controllers\Product->read');
        $f3->route('GET /shop/category/@slug', '\Shop\Site\Controllers\Category->index');
        $f3->route('GET /shop/category/@slug/@page', '\Shop\Site\Controllers\Category->index');

                
        // append this app's UI folder to the path
        $ui = $f3->get('UI');
        $ui .= ";" . $f3->get('PATH_ROOT') . "vendor/dioscouri/f3-shop/src/Shop/Site/Views/";
        $f3->set('UI', $ui);
        
        break;
}
?>