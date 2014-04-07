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
    	// TODO get the slug param.  lookup the category.  Check ACL against both category and item.
    	// get paginated list of items associated with this category
    	
    	$f3 = \Base::instance();
    	$slug = $this->inputfilter->clean( $f3->get('PARAMS.slug'), 'cmd' );
    	$products_model = $this->model('products');
    	
    	try {
    	    $category = $this->model('categories')->setState('filter.slug', $slug)->getItem();
    		$paginated = $products_model->populateState()
    		      ->setState('filter.category.slug', $slug)
    		      ->setState('filter.publication_status', 'published')
    		      ->setState('filter.published_today', true)
    		      ->setState('filter.inventory_status', 'in_stock')
    		      ->paginate();
    	} catch ( \Exception $e ) {
    	    // TODO Change to a normal 404 error
    		\Dsc\System::instance()->addMessage( "Invalid Items: " . $e->getMessage(), 'error');
    		$f3->reroute( '/' );
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