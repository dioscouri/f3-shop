<?php 
namespace Shop\Admin\Controllers;

class Customers extends \Admin\Controllers\BaseAuth 
{
    use \Dsc\Traits\Controllers\AdminList;
    
    protected $list_route = '/admin/shop/customers';
    protected $create_item_route = '/admin/shop/customer/create';
    protected $get_item_route = '/admin/shop/customer/read/{id}';
    protected $edit_item_route = '/admin/shop/customer/edit/{id}';
    
    protected function getModel()
    {
        $model = new \Shop\Models\Customers;
        return $model;
    }
    
    public function index()
    {
        $model = $this->getModel();
    
        $state = $model->emptyState()->populateState()->getState();
        \Base::instance()->set('state', $state );
    
        $paginated = $model->paginate();
        \Base::instance()->set('paginated', $paginated );
    
        $this->app->set('meta.title', 'Customers | Shop');
    
        $view = \Dsc\System::instance()->get('theme');
        echo $view->render('Shop/Admin/Views::customers/list.php');
    }
        
    public function forSelection()
    {
        $field = $this->input->get('value', '_id', 'default');
        $term = $this->input->get('q', null, 'default');
        $key =  new \MongoRegex('/'. $term .'/i');
        
        $where = array();
        $where[] = array(
            'username' => $key
        );
        $where[] = array(
            'email' => $key
        );
        $where[] = array(
            'first_name' => $key
        );
        $where[] = array(
            'last_name' => $key
        );
        
        $results = \Shop\Models\Customers::forSelection(array('$or'=>$where), $field);
    
        $response = new \stdClass;
        $response->more = false;
        $response->term = $term;
        $response->results = $results;
    
        return $this->outputJson($response);
    }
}