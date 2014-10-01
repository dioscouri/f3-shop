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
    	    if ($this->app->get('AJAX'))
    	    {
    	        return $this->outputJson($this->getJsonResponse(array(
    	            'result' => false,
    	            'error' => true,
    	            'message' => 'Invalid Product'
    	        )));
    	    }
    	    else
    	    {
    	        $this->app->error( '404', 'Invalid Product' );
    	        return;
    	    }
    	}

    	$redirect = '/shop/product/' . $item->slug;
    	if ($custom_redirect = \Dsc\System::instance()->get('session')->get('shop.product_review.redirect'))
    	{
    	    $redirect = $custom_redirect;
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
    	    
    	    $post = $this->app->get('POST');
    	    $post['description'] = !empty($post['description']) ? nl2br($post['description']) : null;
    	    
    	    $review = (new \Shop\Models\ProductReviews($post))
    	    ->set('product_id', $item->id)
    	    ->set('user_id', $user->id)
    	    ->set('user_name', $user->first_name)
    	    ->set('publication.status', 'draft')
    	    ->save();
    	    
    	    // Add images, using a model method
    	    $review->addImages($this->app->get('FILES'));
    	    
    	    $successMessage = 'Thanks for the review! It will be published following review by our moderators.';
    	    
    	    if ($this->app->get('AJAX'))
    	    {
    	        return $this->outputJson($this->getJsonResponse(array(
    	            'result' => true,
    	            'message' => $successMessage 
    	        )));
    	    }
    	    else
    	    {
    	        \Dsc\System::addMessage( $successMessage, 'success' );
    	        $this->app->reroute($redirect);
    	        return;
    	    }
    	    
    	}
        catch ( \Exception $e ) 
    	{
    	    if ($this->app->get('AJAX'))
    	    {
    	        return $this->outputJson($this->getJsonResponse(array(
    	            'result' => false,
    	            'error' => true,
    	            'message' => $e->getMessage()
    	        )));
    	    }
    	    else
    	    {
    	        \Dsc\System::addMessage( $e->getMessage(), 'error');
    	        $this->app->reroute($redirect);
    	        return;
    	    }
    	}
    }
    
    public function customerImage()
    {
        try
        {
            $slug = $this->inputfilter->clean( $this->app->get('PARAMS.slug'), 'cmd' );
             
            $product = $this->model('products')
            ->setState('filter.slug', $slug)
            ->setState('filter.published_today', true)
            ->setState('filter.publication_status', 'published')
            ->setState('filter.inventory_status', 'in_stock')
            ->getItem();
        
            if (empty($product->id))
            {
                throw new \Exception;
            }
        }
        catch ( \Exception $e )
        {
            $this->app->error( '404', 'Invalid Product' );
            return;
        }
        
        $offset = abs( (int) $this->app->get('PARAMS.skip') );
        $image_count = \Shop\Models\ProductReviews::forProduct( $product, 'image_count' );
        
        if (empty($image_count) || $offset > $image_count-1) 
        {
            $this->app->error( '404', 'Invalid image offset' );
            return;            
        }
        
        $reviews = (new \Shop\Models\ProductReviews)->setState('filter.product_id', $product->id)
        ->setState('filter.published_today', true)
        ->setState('filter.publication_status', 'published')
        ->setState('filter.has_image', true)
        ->setState('list.limit', 1)
        ->setParam('skip', $offset)
        ->getItems();
        
        if (empty($reviews[0])) 
        {
            $this->app->error( '404', 'Invalid image' );
            return;            
        }
        
        $review = $reviews[0];
        
        $next = ($offset + 1 < $image_count) ? $offset + 1 : null;
        $prev = ($offset - 1 >= 0) ? $offset - 1 : null;
        
        $this->app->set('review', $review);
        $this->app->set('product', $product);
        $this->app->set('image_count', $image_count);
        $this->app->set('current', $offset);
        $this->app->set('next', $next);
        $this->app->set('prev', $prev);
        
        $html = $this->theme->renderView('Shop/Site/Views::product/review_image.php');
        
        echo $html;
    }
}