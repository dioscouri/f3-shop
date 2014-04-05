<?php 
namespace Shop\Site\Controllers;

class Account extends \Dsc\Controller 
{    
    public function index()
    {
    	$view = \Dsc\System::instance()->get('theme');
    	echo $view->render('Shop/Site/Views::account/index.php');
    }
}