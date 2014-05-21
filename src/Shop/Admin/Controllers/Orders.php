<?php 
namespace Shop\Admin\Controllers;

class Orders extends \Admin\Controllers\BaseAuth 
{
    use \Dsc\Traits\Controllers\AdminList;
    
    protected $list_route = '/admin/shop/orders';

    protected function getModel()
    {
        $model = new \Shop\Models\Orders;
        return $model;
    }
    
    public function index()
    {
        $model = $this->getModel();
        
        $state = $model->emptyState()->populateState()->getState();
        
        if (!$state->exists('filter.status')) {
            $open = \Shop\Constants\OrderStatus::open;
            $model->setState('filter.status', $open);
            $state->set('filter.status', $open);
        }
        
        \Base::instance()->set('state', $state );
        
        $paginated = $model->paginate();
        \Base::instance()->set('paginated', $paginated );
        
        $this->app->set('meta.title', 'Orders | Shop');
        
        $view = \Dsc\System::instance()->get('theme');
        echo $view->render('Shop/Admin/Views::orders/list.php');
    }
}