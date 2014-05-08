<?php 
namespace Shop\Site\Controllers;

class Product extends \Dsc\Controller 
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
    
    public function read()
    {
    	$f3 = \Base::instance();
    	$slug = $this->inputfilter->clean( $f3->get('PARAMS.slug'), 'cmd' );
    	
    	try {
    		$item = $this->model('products')->setState('filter.slug', $slug)->getItem();
    	} catch ( \Exception $e ) {
    	    // TODO Change to a normal 404 error
    		\Dsc\System::instance()->addMessage( "Invalid Item: " . $e->getMessage(), 'error');
    		$f3->reroute( '/' );
    		return;
    	}
    	
    	// get the previous query_params for the products model from the state history
    	$model = $this->model('products');
    	if ($params = $this->session->lastState( get_class( $model ) )) 
    	{
    	    $surrounding = $model->surrounding($item->id, $params);
    	    \Base::instance()->set('surrounding', $surrounding );
    	}
    	\Base::instance()->set('lastUrl', $this->session->lastUrl() );
    	
    	\Base::instance()->set('item', $item );
    	
    	$view = \Dsc\System::instance()->get('theme');
    	echo $view->render('Shop/Site/Views::product/detail.php');
    	 
    }
}