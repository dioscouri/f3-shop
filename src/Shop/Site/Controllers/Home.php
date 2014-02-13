<?php 
namespace Shop\Site\Controllers;

class Home extends \Dsc\Controller 
{    
    protected function model($type=null) 
    {
        switch (strtolower($type)) 
        {
        	case "products":
        	case "product":
        	    $model = new \Shop\Admin\Models\Products;
        	    break;
        	default:
        	    $model = new \Shop\Admin\Models\Categories;
        	    break;
        }
        
        return $model; 
    }
    
    public function index()
    {
    	// TODO get the slug param.  lookup the category.  Check ACL against both category.
    	// get paginated list of blog posts associated with this category
    	// only posts that are published as of now
    	
    	$f3 = \Base::instance();
    	$slug = $this->inputfilter->clean( $f3->get('PARAMS.slug'), 'cmd' );
    	$products_model = $this->model('products');
    	
    	try {
    	    $category = $this->model('categories')->getMapper();
    		$list = $products_model->populateState()->paginate();
    	} catch ( \Exception $e ) {
    	    // TODO Change to a normal 404 error
    		\Dsc\System::instance()->addMessage( "Invalid Items: " . $e->getMessage(), 'error');
    		$f3->reroute( '/' );
    		return;
    	}
    	
    	\Base::instance()->set('category', $category );
    	
    	\Base::instance()->set('pagetitle', '');
    	\Base::instance()->set('subtitle', '');
    	
    	$state = $products_model->getState();
    	\Base::instance()->set('state', $state );
    	
    	\Base::instance()->set('list', $list );
    	
    	$pagination = new \Dsc\Pagination($list['total'], $list['limit']);
    	\Base::instance()->set('pagination', $pagination );
    	
    	$view = new \Dsc\Template;
    	echo $view->render('Shop/Site/Views::home/index.php');
    	 
    }
}