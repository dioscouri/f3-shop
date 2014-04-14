<?php 
namespace Shop\Admin\Controllers;

class Regions extends \Admin\Controllers\BaseAuth 
{
    use \Dsc\Traits\Controllers\AdminList;
    
    protected $list_route = '/admin/shop/regions';

    protected function getModel()
    {
        $model = new \Shop\Models\Regions;
        return $model;
    }
    
    public function index()
    {
        $model = $this->getModel();
        
        $state = $model->emptyState()->populateState()->getState();
        \Base::instance()->set('state', $state );
        
        $paginated = $model->paginate();
        \Base::instance()->set('paginated', $paginated );
        
        $view = \Dsc\System::instance()->get('theme');
        echo $view->render('Shop/Admin/Views::regions/list.php');
    }
    
    public function forSelection()
    {
        $term = $this->input->get('q', null, 'default');
        $key =  new \MongoRegex('/'. $term .'/i');
        $results = \Shop\Models\Regions::forSelection(array('name'=>$key));
        
        $response = new \stdClass;
        $response->more = false;
        $response->term = $term;
        $response->results = $results;
        
        return $this->outputJson($response);
    }
}