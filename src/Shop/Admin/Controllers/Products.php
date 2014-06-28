<?php 
namespace Shop\Admin\Controllers;

class Products extends \Admin\Controllers\BaseAuth 
{
	use \Dsc\Traits\Controllers\AdminList;
	use \Dsc\Traits\Controllers\SupportPreview;
	
	protected $list_route = '/admin/shop/products';
	
	protected function getModel()
	{
		$model = new \Shop\Models\Products;
		return $model;
	}
	
	public function index()
    {
        $model = $this->getModel();
        $state = $model->emptyState()->populateState()->getState();
        $this->app->set('state', $state );
        
        $paginated = $model->paginate();     
        $this->app->set('paginated', $paginated );
        
        $this->app->set('meta.title', 'Products | Shop');
        $this->app->set( 'allow_preview', $this->canPreview( true ) );
        
        $view = \Dsc\System::instance()->get('theme');
        echo $view->render('Shop\Admin\Views::products/list.php');
    }
    
    public function forSelection()
    {
        $term = $this->input->get('q', null, 'default');
        $key =  new \MongoRegex('/'. $term .'/i');
        $results = \Shop\Models\Products::forSelection(array('title'=>$key));
    
        $response = new \stdClass;
        $response->more = false;
        $response->term = $term;
        $response->results = $results;
    
        return $this->outputJson($response);
    }
}