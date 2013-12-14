<?php 
$f3 = \Base::instance();
$global_app_name = $f3->get('APP_NAME');

switch ($global_app_name) 
{
    case "admin":
        // register event listener
        \Dsc\System::instance()->getDispatcher()->addListener(\Tienda\Listener::instance());
        
        // register all the routes
        $f3->route('GET|POST /admin/tienda/products', '\Tienda\Admin\Controllers\Products->display');
        $f3->route('GET|POST /admin/tienda/products/@page', '\Tienda\Admin\Controllers\Products->display');
        $f3->route('GET|POST /admin/tienda/products/delete', '\Tienda\Admin\Controllers\Products->delete');
        $f3->route('GET /admin/tienda/product', '\Tienda\Admin\Controllers\Product->create');
        $f3->route('POST /admin/tienda/product', '\Tienda\Admin\Controllers\Product->add');
        $f3->route('GET /admin/tienda/product/@id', '\Tienda\Admin\Controllers\Product->read');
        $f3->route('GET /admin/tienda/product/@id/edit', '\Tienda\Admin\Controllers\Product->edit');
        $f3->route('POST /admin/tienda/product/@id', '\Tienda\Admin\Controllers\Product->update');
        $f3->route('DELETE /admin/tienda/product/@id', '\Tienda\Admin\Controllers\Product->delete');
        $f3->route('GET /admin/tienda/product/@id/delete', '\Tienda\Admin\Controllers\Product->delete');
        
        $f3->route('GET /admin/tienda/categories [ajax]','\Tienda\Admin\Controllers\Categories->getDatatable');
        $f3->route('GET /admin/tienda/categories/all [ajax]','\Tienda\Admin\Controllers\Categories->getAll');
        $f3->route('GET|POST /admin/tienda/categories/checkboxes [ajax]','\Tienda\Admin\Controllers\Categories->getCheckboxes');
        $f3->route('GET|POST /admin/tienda/categories', '\Tienda\Admin\Controllers\Categories->display');
        $f3->route('GET|POST /admin/tienda/categories/@page', '\Tienda\Admin\Controllers\Categories->display');
        $f3->route('GET|POST /admin/tienda/categories/delete', '\Tienda\Admin\Controllers\Categories->delete');
        $f3->route('GET /admin/tienda/category', '\Tienda\Admin\Controllers\Category->create');
        $f3->route('POST /admin/tienda/category', '\Tienda\Admin\Controllers\Category->add');
        $f3->route('GET /admin/tienda/category/@id', '\Tienda\Admin\Controllers\Category->read');
        $f3->route('GET /admin/tienda/category/@id/edit', '\Tienda\Admin\Controllers\Category->edit');
        $f3->route('POST /admin/tienda/category/@id', '\Tienda\Admin\Controllers\Category->update');
        $f3->route('DELETE /admin/tienda/category/@id', '\Tienda\Admin\Controllers\Category->delete');
        $f3->route('GET /admin/tienda/category/@id/delete', '\Tienda\Admin\Controllers\Category->delete');        
        
        // append this app's UI folder to the path
        $ui = $f3->get('UI');
        $ui .= ";" . $f3->get('PATH_ROOT') . "vendor/dioscouri/f3-tienda/src/Tienda/Admin/Views/";
        $f3->set('UI', $ui);
        
        // TODO set some app-specific settings, if desired
                
        break;
    case "site":
        // TODO register all the routes
        
        // append this app's UI folder to the path
                
        // TODO set some app-specific settings, if desired
        break;
}
?>