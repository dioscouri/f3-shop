<?php 
namespace Shop\Admin\Controllers;

class OrderFailures extends \Admin\Controllers\BaseAuth 
{
    protected $list_route = '/admin/shop/orderfailures';

    protected function getModel()
    {
        $model = new \Shop\Models\OrderFailures;
        return $model;
    }
    
    public function index()
    {
        $model = $this->getModel();
        
        $state = $model->emptyState()->populateState()->getState();
        \Base::instance()->set('state', $state );
        
        $paginated = $model->paginate();
        \Base::instance()->set('paginated', $paginated );
        
        $this->app->set('meta.title', 'Failed Checkouts | Shop');
        
        $view = \Dsc\System::instance()->get('theme');
        echo $view->render('Shop/Admin/Views::orderfailures/list.php');
    }
}