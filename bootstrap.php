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
        \Dsc\System::instance()->get('router')->mount( new \Shop\Admin\Routes );
        
        // append this app's UI folder to the path
        // new way
        \Dsc\System::instance()->get('theme')->registerViewPath( __dir__ . '/src/Shop/Admin/Views/', 'Shop/Admin/Views' );
        // old way
        $ui = $f3->get('UI');
        $ui .= ";" . $f3->get('PATH_ROOT') . "vendor/dioscouri/f3-shop/src/Shop/Admin/Views/";
        $f3->set('UI', $ui);
        
        
        // TODO set some app-specific settings, if desired
                
        break;
    case "site":
        // register the view path
        // new way
        \Dsc\System::instance()->get('theme')->registerViewPath( __dir__ . '/src/Shop/Site/Views/', 'Shop/Site/Views' );
        // old way
        $ui = $f3->get('UI');
        $ui .= ";" . $f3->get('PATH_ROOT') . "vendor/dioscouri/f3-shop/src/Shop/Site/Views/";
        $f3->set('UI', $ui);

        // register all the routes
        \Dsc\System::instance()->get('router')->mount( new \Shop\Site\Routes );        
        
        break;
}
?>