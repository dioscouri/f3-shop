<?php 
namespace Shop\Site\Controllers;

class Orders extends \Dsc\Controller 
{    
    public function index()
    {
    	$view = \Dsc\System::instance()->get('theme');
    	echo $view->render('Shop/Site/Views::orders/index.php');
    }
}