<?php 
namespace Shop\Site\Controllers;

class Collection extends \Dsc\Controller 
{    
    protected function getModel() 
    {
        $model = new \Shop\Models\Products;
        return $model; 
    }
    
    public function index()
    {
    	// TODO get the slug param.  lookup the category.  Check ACL against both category.
    	// get paginated list of blog posts associated with this category
    	// only posts that are published as of now
    	
    	$f3 = \Base::instance();
    	$slug = $this->inputfilter->clean( $f3->get('PARAMS.slug'), 'cmd' );
    	$model = $this->getModel()->populateState()
            ->setState('filter.category.slug', $slug);
    	
    	try {
    	    $collection = (new \Shop\Models\Collections)->setState('filter.slug', $slug)->getItem();
    	    $conditions = \Shop\Models\Collections::getProductQueryConditions($collection->id);
    		$paginated = $model->setParam('conditions', $conditions)->paginate();
    	} catch ( \Exception $e ) {
    	    // TODO Change to a normal 404 error
    		\Dsc\System::instance()->addMessage( "Invalid Items: " . (string) $e, 'error');
    		$f3->reroute( '/' );
    		return;
    	}
    	
    	\Base::instance()->set('collection', $collection );
    	
    	\Base::instance()->set('pagetitle', $collection->{'title'});
    	\Base::instance()->set('subtitle', '');
    	
    	$state = $model->getState();
    	\Base::instance()->set('state', $state );
    	
    	\Base::instance()->set('paginated', $paginated );
    	
    	$view = \Dsc\System::instance()->get('theme');
    	echo $view->render('Shop/Site/Views::collection/index.php');
    	 
    }
}