<?php 
namespace Shop\Admin\Controllers;

class Wishlists extends \Admin\Controllers\BaseAuth 
{
    use \Dsc\Traits\Controllers\AdminList;
    
    protected $list_route = '/admin/shop/wishlists';

    protected function getModel()
    {
        $model = new \Shop\Models\Wishlists;
        return $model;
    }
    
    public function index()
    {
        $model = $this->getModel();
        
        $state = $model->emptyState()->populateState()->getState();
        \Base::instance()->set('state', $state );
        
        $paginated = $model->paginate();
        \Base::instance()->set('paginated', $paginated );
        
        $this->app->set('meta.title', 'Wishlists | Shop');
        
        $view = \Dsc\System::instance()->get('theme');
        echo $view->render('Shop/Admin/Views::wishlists/list.php');
    }
}