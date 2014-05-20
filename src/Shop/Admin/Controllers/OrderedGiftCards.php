<?php 
namespace Shop\Admin\Controllers;

class OrderedGiftCards extends \Admin\Controllers\BaseAuth 
{
    use \Dsc\Traits\Controllers\AdminList;
    
    protected $list_route = '/admin/shop/orders/giftcards';
    protected $create_item_route = '/admin/shop/orders/giftcard/create';
    protected $get_item_route = '/admin/shop/orders/giftcard/read/{id}';
    protected $edit_item_route = '/admin/shop/orders/giftcard/edit/{id}';

    protected function getModel()
    {
        $model = new \Shop\Models\OrderedGiftCards;
        return $model;
    }
    
    public function index()
    {
        $model = $this->getModel();
        
        $state = $model->emptyState()->populateState()->getState();
        \Base::instance()->set('state', $state );
        
        $paginated = $model->paginate();
        \Base::instance()->set('paginated', $paginated );
        
        $this->app->set('meta.title', 'Ordered Gift Cards | Shop');
        
        $view = \Dsc\System::instance()->get('theme');
        echo $view->render('Shop/Admin/Views::orderedgiftcards/list.php');
    }
}