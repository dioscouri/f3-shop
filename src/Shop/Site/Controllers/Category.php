<?php 
namespace Shop\Site\Controllers;

class Category extends \Dsc\Controller 
{ 
	use \Dsc\Traits\Controllers\SupportPreview;
	
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
    	$url_params = $this->app->get('PARAMS');
    	    	
    	$param = $this->inputfilter->clean( $this->app->get('PARAMS.1'), 'string' );
    	$pieces = explode('?', $param);
    	$path = $pieces[0];
    	$products_model = $this->model('products');

    	try {
    	    $category = $this->model('categories')->setState('filter.path', $path)->getItem();
    	    if (empty($category->id)) {
    	    	throw new \Exception;
    	    }
    		$paginated = $products_model->populateState()
    		      ->setState('filter.category.id', $category->id);
    		      
    		
    		$preview = $this->input->get( "preview", 0, 'int' );
    		if( $preview ){
    			$this->canPreview(false, "Shop\Models\Categories");
    		} else {
    			$products_model->setState('filter.published_today', true)
    			->setState('filter.inventory_status', 'in_stock')
    			->setState('filter.publication_status', 'published');
    		}
    		
    		$paginated = $products_model->paginate();
    		
    		
    	} catch ( \Exception $e ) {
    		\Dsc\System::instance()->addMessage( 'Invalid category', 'error');
    		\Dsc\System::instance()->addMessage( $path, 'error');
    		$this->app->reroute( '/shop' ); // $f3->error('404');
    		return;
    	}

    	// push the current query_params into the state history
    	$this->session->trackState( get_class( $products_model ), $products_model->getParam() )->clearUrls();
    	foreach ($category->ancestors() as $ancestor) {
    		$this->session->trackUrl( $ancestor->title, '/shop/category' . $ancestor->path );
    	}
    	$this->session->trackUrl( $category->{'title'} );

    	$state = $products_model->getState();
    	$this->app->set('state', $state );
    	$this->app->set('paginated', $paginated );
    	
    	$this->app->set('category', $category );
    	$this->app->set('meta.title', $category->{'title'} . ' | Shop');

    	$view = \Dsc\System::instance()->get('theme');
    	echo $view->render('Shop/Site/Views::category/index.php');
    }
}