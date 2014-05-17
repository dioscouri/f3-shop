<?php 
namespace Shop\Site\Controllers;

class Account extends \Dsc\Controller 
{
    public function beforeRoute()
    {
        $this->requireIdentity();
    }
    
    public function index()
    {
        $this->app->set('meta.title', 'My Account');
        
    	$view = \Dsc\System::instance()->get('theme');
    	echo $view->render('Shop/Site/Views::account/index.php');
    }
}