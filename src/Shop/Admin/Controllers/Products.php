<?php 
namespace Shop\Admin\Controllers;

class Products extends \Admin\Controllers\BaseAuth 
{
    public function index()
    {
        \Base::instance()->set('pagetitle', 'Products');
        \Base::instance()->set('subtitle', '');
        
        $model = new \Shop\Models\Products;
        $state = $model->populateState()->getState();
        \Base::instance()->set('state', $state );
        
        $paginated = $model->paginate();     
        \Base::instance()->set('paginated', $paginated );
                
        $view = \Dsc\System::instance()->get('theme');
        echo $view->render('Shop\Admin\Views::products/list.php');
    }
}