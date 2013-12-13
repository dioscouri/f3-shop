<?php 
$f3 = \Base::instance();
$global_app_name = $f3->get('APP_NAME');

switch ($global_app_name) 
{
    case "admin":
        // register event listener
        \Dsc\System::instance()->getDispatcher()->addListener(\Tienda\Listener::instance());
        
        // register all the routes
        
        // append this app's UI folder to the path, e.g. UI=../apps/blog/admin/views/
        
        // TODO set some app-specific settings, if desired
                
        break;
    case "site":
        // TODO register all the routes
        
        // append this app's UI folder to the path, e.g. UI=../apps/blog/site/views/
                
        // TODO set some app-specific settings, if desired
        break;
}
?>