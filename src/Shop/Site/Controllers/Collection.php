<?php 
namespace Shop\Site\Controllers;

class Collection extends \Dsc\Controller 
{    
	
	use \Dsc\Traits\Controllers\SupportPreview;
	
    protected function getModel() 
    {
        $model = new \Shop\Models\Products;
        return $model; 
    }
    
    public function index()
    {
    	$slug = $this->inputfilter->clean( $this->app->get('PARAMS.slug'), 'cmd' );
    	$model = $this->getModel()->populateState();
    	
    	try {
    	    $collection = (new \Shop\Models\Collections)->setState('filter.slug', $slug)->getItem();
    	    if (empty($collection->id)) {
    	    	throw new \Exception('Invalid Collection');
    	    }
    	    $model->setState('filter.collection', $collection->id);
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
    	    
    	    switch ($model->getState('sort_by')) 
    	    {
    	        // only use the collection's $collection->sort_by if the user hasn't set their own state, 
    	        // which is populated in the model by populateState()
    	    	case "collection-default":
    	    	case "":
    	    	    switch ($collection->sort_by) 
    	    	    {
    	    	    	case "ordering-asc":
    	    	    	    
    	    	    	    $model->setState('list.sort', array(
    	    	    	        array( 'collections.'. $collection->id .'.ordering' => 1 )
    	    	    	    ));
    	    	    	    	
    	    	    	    break;
    	    	    	default:
    	    	    	    $model->handleSortBy($collection->sort_by);
    	    	    	    break;
    	    	    }
    	    	    break;
    	    }
    	    
    		$paginated = $model->setParam('conditions', $conditions)->paginate();
    	} 
    	catch ( \Exception $e ) 
    	{
    	    \Dsc\System::instance()->addMessage( $e->getMessage(), 'error');
    	    $this->app->error( '404' );    	    
    		return;
    	}
    	
    	// push the current query_params into the state history
    	$this->session->trackState( get_class( $model ), $model->getParam() )->clearUrls()->trackUrl( $collection->{'title'} );
    	
    	$state = $model->getState();
    	$this->app->set('state', $state );
    	$this->app->set('paginated', $paginated );
    	 
    	$this->app->set('collection', $collection );    	

    	$this->app->set('meta.title', $collection->seoTitle() . ' | Shop');
    	$this->app->set('meta.description', $collection->seoDescription() );    	
    	
    	$view = \Dsc\System::instance()->get('theme');
    	echo $view->render('Shop/Site/Views::collection/index.php');
    	 
    }
}