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

    	\Shop\Models\Activities::track('Viewed Collection', array(
    	    'Collection Name' => $collection->seoTitle(),
    	    'collection_id' => (string) $collection->id,
    	    'page_number' => $paginated->current_page
    	));
    	
    	$view = \Dsc\System::instance()->get('theme');
    	
    	$view_file = 'index.php';
    	if ($collection->{'display.view'} && $view->findViewFile( 'Shop/Site/Views::collection/index/' . $collection->{'display.view'} )) {
    	    $view_file = 'index/' . $collection->{'display.view'};
    	}
    	
    	echo $view->renderTheme('Shop/Site/Views::collection/' . $view_file);
    }
    
    public function viewAll()
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
            	
            $model->setState('list.limit', 300);
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
        
        \Shop\Models\Activities::track('Viewed Collection', array(
            'Collection Name' => $collection->seoTitle(),
            'collection_id' => (string) $collection->id,
            'page_number' => 'view_all'
        ));        
         
        $view = \Dsc\System::instance()->get('theme');
        
        $view_file = 'all.php';
        if ($collection->{'display.view'} && $view->findViewFile( 'Shop/Site/Views::collection/all/' . $collection->{'display.view'} )) {
            $view_file = 'all/' . $collection->{'display.view'};
        }
         
        echo $view->renderTheme('Shop/Site/Views::collection/' . $view_file);            
    }    
    
    public function viewAllPaginate()
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
             
            $model->setState('list.limit', 300);
            $paginated = $model->setParam('conditions', $conditions)->paginate();
        }
        catch ( \Exception $e )
        {
            return;
        }
        
        $this->app->set('paginated', $paginated );
         
        $view = \Dsc\System::instance()->get('theme');
        
        $response = new \stdClass;
        $response->more = 0;
        
        if (!empty($paginated->total_items)) {
            if ($paginated->total_items > ($paginated->items_per_page * $paginated->current_page))
            {
                $response->next_page = $paginated->next_page;
            }
            
            $view_file = 'all_grid.php';
            if ($collection->{'display.view'} && $view->findViewFile( 'Shop/Site/Views::collection/all_grid/' . $collection->{'display.view'} )) {
                $view_file = 'all_grid/' . $collection->{'display.view'};
            }
            $response->result = $view->renderView('Shop/Site/Views::collection/' . $view_file);
                        
            //$response->result = $view->renderView('Shop/Site/Views::collection/all_grid.php');
        }
        
        $this->outputJson($response);        
    }    
}