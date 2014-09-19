<?php 
namespace Shop\Site\Controllers;

class ProductReviews extends Product 
{
    public function index()
    {
        try
        {
            $slug = $this->inputfilter->clean( $this->app->get('PARAMS.slug'), 'cmd' );
            	
            $item = $this->model('products')
            ->setState('filter.slug', $slug)
            ->setState('filter.published_today', true)
            ->setState('filter.publication_status', 'published')
            ->setState('filter.inventory_status', 'in_stock')
            ->getItem();
        
            if (empty($item->id))
            {
                throw new \Exception;
            }
        }
        catch ( \Exception $e )
        {
            $this->app->error( '404', 'Invalid Product' );
            return;
        }
        
        $paginated = (new \Shop\Models\ProductReviews)->populateState()
            ->setState('filter.product_id', $item->id)
            ->setState('filter.published_today', true)
            ->setState('filter.publication_status', 'published')
            ->setState('list.limit', 10)
            ->paginate();
        
        $this->app->set('paginated', $paginated);
        $html = $this->theme->renderView('Shop/Site/Views::product/reviews.php');
        
        return $this->outputJson(array(
            'current_page' => $paginated->current_page,
            'next_page' => $paginated->next_page,
            'total_pages' => $paginated->total_pages,
            'total_items' => $paginated->total_items,
            'html' => $html
        ));
        
    }
    
    public function create()
    {
        // load the product
        // is it valid?
        // is the user logged in?
        // can the user review this product?
        // try/catch the save
    	
    	try 
    	{
    	    $slug = $this->inputfilter->clean( $this->app->get('PARAMS.slug'), 'cmd' );
    	    
    		$item = $this->model('products')
    		->setState('filter.slug', $slug)
    		->setState('filter.published_today', true)
    		->setState('filter.publication_status', 'published')
    		->setState('filter.inventory_status', 'in_stock')
    		->getItem();
    		
    		if (empty($item->id)) 
    		{
    			throw new \Exception;
    		}
    	} 
    	catch ( \Exception $e ) 
    	{
    		$this->app->error( '404', 'Invalid Product' );
    		return;
    	}
    	
    	try 
    	{
    	    $user = $this->getIdentity();
    	    
    	    if (empty($user->id)) 
    	    {
    	        throw new \Exception('Must be logged in to post a review');
    	    }
    	    
    	    $canReview = \Shop\Models\ProductReviews::canUserReview($user, $item);
    	    if ($canReview !== true)
    	    {
    	        throw new \Exception( $canReview );
    	    }
    	    
    	    $review = (new \Shop\Models\ProductReviews($this->app->get('POST')))
    	    ->set('product_id', $item->id)
    	    ->set('user_id', $user->id)
    	    ->set('user_name', $user->first_name)
    	    ->set('publication.status', 'draft')
    	    ->save();
    	    
    	    // Add images, using a model method
    	    $review->addImages($this->app->get('FILES'));
    	    
    	    \Dsc\System::addMessage( 'Thanks for the review! It will be published following review by our moderators.', 'success' );
    	    $this->app->reroute( '/shop/product/' . $item->slug );
    	    
    	}
        catch ( \Exception $e ) 
    	{
    	    \Dsc\System::addMessage( $e->getMessage(), 'error');
    	    $this->app->reroute( '/shop/product/' . $item->slug );
    	    return;    	    
    	}
    }
}