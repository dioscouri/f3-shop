<?php 
$f3 = \Base::instance();
$global_app_name = $f3->get('APP_NAME');

switch ($global_app_name) 
{
    case "admin":
        // register event listener
        \Dsc\System::instance()->getDispatcher()->addListener(\Tienda\Listener::instance());

        // register the modules path
        \Modules\Factory::registerPath( $f3->get('PATH_ROOT') . "vendor/dioscouri/f3-tienda/src/Tienda/Modules/" );
        
        // register all the routes
        $f3->route('GET|POST /admin/tienda/products', '\Tienda\Admin\Controllers\Products->display');
        $f3->route('GET|POST /admin/tienda/products/page/@page', '\Tienda\Admin\Controllers\Products->display');
        $f3->route('GET|POST /admin/tienda/products/delete', '\Tienda\Admin\Controllers\Products->delete');
        
        $f3->route('GET /admin/tienda/product/create', '\Tienda\Admin\Controllers\Product->create');
        $f3->route('POST /admin/tienda/product/add', '\Tienda\Admin\Controllers\Product->add');
        $f3->route('GET /admin/tienda/product/read/@id', '\Tienda\Admin\Controllers\Product->read');
        $f3->route('GET /admin/tienda/product/edit/@id', '\Tienda\Admin\Controllers\Product->edit');
        $f3->route('POST /admin/tienda/product/update/@id', '\Tienda\Admin\Controllers\Product->update');
        $f3->route('GET|DELETE /admin/tienda/product/delete/@id', '\Tienda\Admin\Controllers\Product->delete');
        
        $f3->route('GET /admin/tienda/categories [ajax]','\Tienda\Admin\Controllers\Categories->getDatatable');
        $f3->route('GET /admin/tienda/categories/all [ajax]','\Tienda\Admin\Controllers\Categories->getAll');
        $f3->route('GET|POST /admin/tienda/categories/checkboxes [ajax]','\Tienda\Admin\Controllers\Categories->getCheckboxes');
        
        $f3->route('GET|POST /admin/tienda/categories', '\Tienda\Admin\Controllers\Categories->display');
        $f3->route('GET|POST /admin/tienda/categories/page/@page', '\Tienda\Admin\Controllers\Categories->display');
        $f3->route('GET|POST /admin/tienda/categories/delete', '\Tienda\Admin\Controllers\Categories->delete');
        
        $f3->route('GET /admin/tienda/category/create', '\Tienda\Admin\Controllers\Category->create');
        $f3->route('POST /admin/tienda/category/add', '\Tienda\Admin\Controllers\Category->add');
        $f3->route('GET /admin/tienda/category/read/@id', '\Tienda\Admin\Controllers\Category->read');
        $f3->route('GET /admin/tienda/category/edit/@id', '\Tienda\Admin\Controllers\Category->edit');
        $f3->route('POST /admin/tienda/category/update/@id', '\Tienda\Admin\Controllers\Category->update');
        $f3->route('GET|DELETE /admin/tienda/category/delete/@id', '\Tienda\Admin\Controllers\Category->delete');
        
        $f3->route('GET|POST /admin/tienda/manufacturers', '\Tienda\Admin\Controllers\Manufacturers->display');
        $f3->route('GET|POST /admin/tienda/manufacturers/page/@page', '\Tienda\Admin\Controllers\Manufacturers->display');
        $f3->route('GET|POST /admin/tienda/manufacturers/delete', '\Tienda\Admin\Controllers\Manufacturers->delete');
        
        $f3->route('GET /admin/tienda/manufacturer/create', '\Tienda\Admin\Controllers\Manufacturer->create');
        $f3->route('POST /admin/tienda/manufacturer/add', '\Tienda\Admin\Controllers\Manufacturer->add');
        $f3->route('GET /admin/tienda/manufacturer/read/@id', '\Tienda\Admin\Controllers\Manufacturer->read');
        $f3->route('GET /admin/tienda/manufacturer/edit/@id', '\Tienda\Admin\Controllers\Manufacturer->edit');
        $f3->route('POST /admin/tienda/manufacturer/update/@id', '\Tienda\Admin\Controllers\Manufacturer->update');
        $f3->route('GET|DELETE /admin/tienda/manufacturer/delete/@id', '\Tienda\Admin\Controllers\Manufacturer->delete');
        
        // append this app's UI folder to the path
        $ui = $f3->get('UI');
        $ui .= ";" . $f3->get('PATH_ROOT') . "vendor/dioscouri/f3-tienda/src/Tienda/Admin/Views/";
        $f3->set('UI', $ui);
        
        // TODO set some app-specific settings, if desired
                
        break;
    case "site":
        // TODO register all the routes
        $f3->route('GET /tienda/product/@slug', '\Tienda\Site\Controllers\Product->read');
        $f3->route('GET /tienda/category/@slug', '\Tienda\Site\Controllers\Category->index');
        $f3->route('GET /tienda/category/@slug/@page', '\Tienda\Site\Controllers\Category->index');

                
        // append this app's UI folder to the path
        $ui = $f3->get('UI');
        $ui .= ";" . $f3->get('PATH_ROOT') . "vendor/dioscouri/f3-tienda/src/Tienda/Site/Views/";
        $f3->set('UI', $ui);
        
        break;
}
?>