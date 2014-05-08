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
    	$model = $this->getModel()->populateState();
    	
    	try {
    	    $collection = (new \Shop\Models\Collections)->setState('filter.slug', $slug)->getItem();
    	    $conditions = \Shop\Models\Collections::getProductQueryConditions($collection->id);
    	    
    	    if ($filter_tags = (array) $model->getState('filter.tags')) 
    	    {
    	        if ($tags = array_filter( array_values( $filter_tags ) )) 
    	        {
    	            if (!empty($conditions['tags']))
    	            {
    	                // Add this to an $and clause
    	                if (empty($conditions['$and']))
    	                {
    	                    $conditions['$and'] = array();
    	                }
    	                $conditions['$and'][] = array('tags' => array( '$in' => $tags ) );
    	                 
    	            }
    	            // we're only filtering by this set of tags
    	            else
    	            {
    	                $conditions['tags'] = array( '$in' => $tags );
    	            }
    	        }
    	    }
    		$paginated = $model->setParam('conditions', $conditions)->paginate();
    		
    	} 
    	catch ( \Exception $e ) 
    	{
    	    // TODO Change to a normal 404 error
    		\Dsc\System::instance()->addMessage( "Invalid Items: " . (string) $e, 'error');
    		$f3->reroute( '/shop' );
    		return;
    	}
    	
    	// push the current query_params into the state history
    	$this->session->trackState( get_class( $model ), $model->getParam() )->clearUrls()->trackUrl( $collection->{'title'} );
    	
    	$state = $model->getState();
    	\Base::instance()->set('state', $state );
    	\Base::instance()->set('paginated', $paginated );
    	 
    	\Base::instance()->set('collection', $collection );    	
    	\Base::instance()->set('pagetitle', $collection->{'title'});
    	\Base::instance()->set('subtitle', '');    	
    	
    	$view = \Dsc\System::instance()->get('theme');
    	echo $view->render('Shop/Site/Views::collection/index.php');
    	 
    }
}