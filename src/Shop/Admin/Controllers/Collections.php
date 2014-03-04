<?php 
namespace Shop\Admin\Controllers;

class Collections extends \Admin\Controllers\BaseAuth 
{
    use \Dsc\Traits\Controllers\AdminList;
    
    protected $list_route = '/admin/shop/collections';

    protected function getModel()
    {
        $model = new \Shop\Models\Collections;
        return $model;
    }
    
    public function index()
    {
        \Base::instance()->set('pagetitle', 'Collections');
        \Base::instance()->set('subtitle', '');
        
        $model = $this->getModel();
        
        $state = $model->emptyState()->populateState()->getState();
        \Base::instance()->set('state', $state );
        
        $paginated = $model->paginate();
        \Base::instance()->set('paginated', $paginated );
        
        \Base::instance()->set('selected', 'null' );
        
        $view = \Dsc\System::instance()->get('theme');
        echo $view->render('Shop/Admin/Views::collections/list.php');
    }
}