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
    	    	
    	$param = $this->inputfilter->clean( $f3->get('PARAMS.1'), 'string' );
    	$pieces = explode('?', $param);
    	$path = $pieces[0];
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
    		\Dsc\System::instance()->addMessage( $path, 'error');
    		$f3->reroute( '/shop' ); // $f3->error('404');
    		return;
    	}

    	// push the current query_params into the state history
    	$this->session->trackState( get_class( $products_model ), $products_model->getParam() )->clearUrls();
    	foreach ($category->ancestors() as $ancestor) {
    		$this->session->trackUrl( $ancestor->title, '/shop/category' . $ancestor->path );
    	}
    	$this->session->trackUrl( $category->{'title'} );

    	$state = $products_model->getState();
    	\Base::instance()->set('state', $state );
    	\Base::instance()->set('paginated', $paginated );
    	
    	\Base::instance()->set('category', $category );
    	\Base::instance()->set('pagetitle', $category->{'title'});
    	\Base::instance()->set('subtitle', '');

    	$view = \Dsc\System::instance()->get('theme');
    	echo $view->render('Shop/Site/Views::category/index.php');
    }
}