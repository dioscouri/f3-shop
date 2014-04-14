<?php 
namespace Shop\Site\Controllers;

class Category extends \Dsc\Controller 
{    
    protected function model($type=null) 
    {
        switch (strtolower($type)) 
        {
        	case "products":
        	case "product":
        	    $model = new \Shop\Models\Products;
        	    break;
        	default:
        	    $model = new \Shop\Models\Categories;
        	    break;
        }
        
        return $model; 
    }
    
    public function index()
    {
    	// TODO Check ACL against both category and item.
    	
    	$f3 = \Base::instance();
    	$url_params = $f3->get('PARAMS');
    	    	
    	$path = $this->inputfilter->clean( $f3->get('PARAMS.1'), 'string' );
    	$products_model = $this->model('products');

    	try {
    	    $category = $this->model('categories')->setState('filter.path', $path)->getItem();
    	    if (empty($category->id)) {
    	    	throw new \Exception;
    	    }
    		$paginated = $products_model->populateState()
    		      ->setState('filter.category.id', $category->id)
    		      ->setState('filter.publication_status', 'published')
    		      ->setState('filter.published_today', true)
    		      ->setState('filter.inventory_status', 'in_stock')
    		      ->paginate();
    	} catch ( \Exception $e ) {
    		\Dsc\System::instance()->addMessage( 'Invalid category', 'error');
    		$f3->error('404');
    		return;
    	}
    	
    	\Base::instance()->set('category', $category );
    	
    	\Base::instance()->set('pagetitle', $category->{'title'});
    	\Base::instance()->set('subtitle', '');
    	
    	$state = $products_model->getState();
    	\Base::instance()->set('state', $state );
    	
    	\Base::instance()->set('paginated', $paginated );
    	
    	$view = \Dsc\System::instance()->get('theme');
    	echo $view->render('Shop/Site/Views::category/index.php');
    }
}