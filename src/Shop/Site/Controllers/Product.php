<?php 
namespace Shop\Site\Controllers;

class Product extends \Dsc\Controller 
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
    
    public function read()
    {
    	$slug = $this->inputfilter->clean( $this->app->get('PARAMS.slug'), 'cmd' );
    	
    	try {
    		$model = $this->model('products')
    		->setState('filter.slug', $slug);

    		$preview = $this->input->get( "preview", 0, 'int' );
    		if( $preview ){
    			$this->canPreview(false, "Shop\Models\Products" );
    		} else {
    			$model->setState('filter.published_today', true)
    			->setState('filter.publication_status', 'published')
    			->setState('filter.inventory_status', 'in_stock');
    		}
    		$item = $model->getItem();
    		    		
    		if (empty($item->id)) {
    			throw new \Exception;
    		}
    	} catch ( \Exception $e ) {
    		\Dsc\System::instance()->addMessage( "Invalid Item", 'error');
    		$this->app->error( '404' );
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

    	$this->app->set('meta.title', $item->seoTitle() . ' | Shop');
    	$this->app->set('meta.description', $item->seoDescription() );

        \Shop\Models\Activities::track('Viewed Potential Purchase', array(
            'SKU' => $item->{'tracking.sku'},
            'Product Name' => $item->title,            
        ));
    	
    	$view = \Dsc\System::instance()->get('theme');
    	echo $view->render('Shop/Site/Views::product/detail.php');
    	 
    }
}