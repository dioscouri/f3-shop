<?php 
namespace Shop\Admin\Controllers;

class Credits extends \Admin\Controllers\BaseAuth 
{
    use \Dsc\Traits\Controllers\AdminList;
    
    protected $list_route = '/admin/shop/credits';
    protected $create_item_route = '/admin/shop/credit/create';
    protected $get_item_route = '/admin/shop/credit/read/{id}';
    protected $edit_item_route = '/admin/shop/credit/edit/{id}';

    protected function getModel()
    {
        $model = new \Shop\Models\Credits;
        return $model;
    }
    
    public function index()
    {
        $model = $this->getModel();
        
        $state = $model->emptyState()->populateState()->getState();
        \Base::instance()->set('state', $state );
        
        $paginated = $model->paginate();
        \Base::instance()->set('paginated', $paginated );
        
        $this->app->set('meta.title', 'Credits | Shop');
        
        $view = \Dsc\System::instance()->get('theme');
        echo $view->render('Shop/Admin/Views::credits/list.php');
    }
}