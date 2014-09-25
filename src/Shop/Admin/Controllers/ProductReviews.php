<?php 
namespace Shop\Admin\Controllers;

class ProductReviews extends \Admin\Controllers\BaseAuth 
{
	use \Dsc\Traits\Controllers\AdminList;
	
	protected $list_route = '/admin/shop/productreviews';
	
	protected function getModel()
	{
		$model = new \Shop\Models\ProductReviews;
		return $model;
	}
	
	public function index()
    {
        $model = $this->getModel();
        $state = $model->populateState()->getState();
        $this->app->set('state', $state );
        
        $paginated = $model->paginate();     
        $this->app->set('paginated', $paginated );
        
        $this->app->set('meta.title', 'Product Reviews | Shop');
        
        $view = \Dsc\System::instance()->get('theme');
        echo $view->render('Shop\Admin\Views::productreviews/index.php');
    }
}