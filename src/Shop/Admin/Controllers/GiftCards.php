<?php 
namespace Shop\Admin\Controllers;

class GiftCards extends \Shop\Admin\Controllers\Products 
{
	protected $list_route = '/admin/shop/giftcards';
	
	protected function getModel()
	{
		$model = new \Shop\Models\GiftCards;
		return $model;
	}
	
	public function index()
    {
        $model = $this->getModel();
        $state = $model->populateState()->getState();
        \Base::instance()->set('state', $state );
        
        $paginated = $model->paginate();     
        \Base::instance()->set('paginated', $paginated );
        
        $this->app->set('meta.title', 'Gift Cards | Shop');
                
        $view = \Dsc\System::instance()->get('theme');
        echo $view->render('Shop\Admin\Views::giftcards/list.php');
    }
    
    public function forSelection()
    {
        $term = $this->input->get('q', null, 'default');
        $key =  new \MongoRegex('/'. $term .'/i');
        $results = \Shop\Models\GiftCards::forSelection(array('title'=>$key));
    
        $response = new \stdClass;
        $response->more = false;
        $response->term = $term;
        $response->results = $results;
    
        return $this->outputJson($response);
    }
}