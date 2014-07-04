<?php 
namespace Shop\Admin\Controllers;

class Coupons extends \Admin\Controllers\BaseAuth 
{
    use \Dsc\Traits\Controllers\AdminList;
    
    protected $list_route = '/admin/shop/coupons';

    protected function getModel()
    {
        $model = new \Shop\Models\Coupons;
        return $model;
    }
    
    public function index()
    {
        $model = $this->getModel();
        $state = $model->emptyState()->populateState()->getState();
        
        if (!$state->exists('filter.publication_status')) {
            $model->setState('filter.publication_status', 'published');
            $state->set('filter.publication_status', 'published');
        }
        
        \Base::instance()->set('state', $state );
        $paginated = $model->paginate();
        \Base::instance()->set('paginated', $paginated );
        \Base::instance()->set('selected', 'null' );
        
        $this->app->set('meta.title', 'Coupons | Shop');
        
        $view = \Dsc\System::instance()->get('theme');
        echo $view->render('Shop/Admin/Views::coupons/list.php');
    }
    
    public function forSelection()
    {
        $term = $this->input->get('q', null, 'default');
        $key =  new \MongoRegex('/'. $term .'/i');
        $results = \Shop\Models\Coupons::forSelection(array('title'=>$key));
    
        $response = new \stdClass;
        $response->more = false;
        $response->term = $term;
        $response->results = $results;
    
        return $this->outputJson($response);
    }
}