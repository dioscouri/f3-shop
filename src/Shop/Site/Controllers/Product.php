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
    		$item = $this->model('products')
    		->setState('filter.slug', $slug)
    		->setState('filter.publication_status', 'published')
    		->setState('filter.published_today', true)
    		->setState('filter.inventory_status', 'in_stock')    		
    		->getItem();
    		if (empty($item->id)) {
    			throw new \Exception;
    		}
    	} catch ( \Exception $e ) {
    		\Dsc\System::instance()->addMessage( "Invalid Item", 'error');
    		$f3->reroute( '/shop' );
    		return;
    	}
    	
    	// get the previous query_params for the products model from the state history
    	// but only if this product is in the last state
    	$model = $this->model('products');
    	if ($params = $this->session->lastState( get_class( $model ) )) 
    	{
    	    $surrounding = $model->surrounding($item->id, $params);
    	    if (!empty($surrounding['found'])) {
    	    	\Base::instance()->set('surrounding', $surrounding );
    	    }
    	}
    	
    	\Base::instance()->set('item', $item );
    	$this->app->set('meta.title', $item->{'title'} . ' | Shop');
    	
    	$view = \Dsc\System::instance()->get('theme');
    	echo $view->render('Shop/Site/Views::product/detail.php');
    	 
    }
}