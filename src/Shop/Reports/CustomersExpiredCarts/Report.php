<?php 
namespace Shop\Reports\CustomersExpiredCarts;

class Report extends \Dsc\Controller 
{
    public function __construct()
    {
        $this->theme->registerViewPath( __dir__ . '/Views/', 'Shop/Reports/CustomersExpiredCarts/Views' );
    
        // TODO Register any custom routes that the report needs
        // $this->app->route( '/admin/shop/reports/@slug/custom-route' );
    }
        
    public function index()
    {
        echo $this->theme->render('Shop/Reports/CustomersExpiredCarts/Views::index.php');
    }
}