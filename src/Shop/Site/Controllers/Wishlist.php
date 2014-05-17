<?php 
namespace Shop\Site\Controllers;

class Wishlist extends \Dsc\Controller 
{
    /**
     * List a user's wishlists 
     * 
     */
    public function index()
    {
        $this->requireIdentity();
        
        $model = new \Shop\Models\Orders;
        $model->emptyState()->populateState()
            ->setState('list.limit', 10 )
            ->setState('filter.user', (string) $identity->id );
        $state = $model->getState();
        
        try {
            $paginated = $model->paginate();
        } catch ( \Exception $e ) {
            // TODO Change to a normal 404 error
            \Dsc\System::instance()->addMessage( $e->getMessage(), 'error');
            $f3->reroute( '/' );
            return;
        }
        
        \Base::instance()->set('state', $state );
        \Base::instance()->set('paginated', $paginated );
        
        $this->app->set('meta.title', 'My Wishlists');
        
        $view = \Dsc\System::instance()->get('theme');
        echo $view->render('Shop/Site/Views::wishlist/index.php');        
    }
    
    /**
     * Display a user's wishlist
     */
    public function read()
    {
    	$this->requireIdentity();
    	
        $wishlist = \Shop\Models\Wishlists::fetch();
        
        // Update product fields stored in wishlist
        foreach ($wishlist->validateProducts() as $change) {
        	\Dsc\System::addMessage($change);
        }
        \Base::instance()->set('wishlist', $wishlist);
        
        $this->app->set('meta.title', 'My Wishlist');
        
        $view = \Dsc\System::instance()->get('theme');
        echo $view->renderTheme('Shop/Site/Views::wishlist/read.php');        
    }
    
    /**
     * Finds a user's primary wishlist and redirects to its real URL (./shop/wishlist/@id).
     * Is really just a vanity URL.
     */
    public function primary() 
    {
        $this->requireIdentity();
        
        $wishlist = \Shop\Models\Wishlists::fetch();
        \Base::instance()->reroute('/shop/wishlist/' . $wishlist->id );
    }
    
    /**
     * Checks if a variant is in any of the user's wishlists
     * and responds with a json object.  
     * Responds whether or not the user is logged in
     * 
     */
    public function added()
    {
        $f3 = \Base::instance();
    	$variant_id = $this->inputfilter->clean( $f3->get('PARAMS.variant_id'), 'alnum' );
    	$result = false;
    	
    	$identity = $this->getIdentity();
    	if (empty($identity->id))
    	{
    	    // return a false message
    	    return $this->outputJson( $this->getJsonResponse( array(
    	    	'result'=>$result
    	    ) ) );
    	}
    	    	
    	if ($count = \Shop\Models\Wishlists::hasAddedVariant($variant_id, (string) $identity->id)) 
    	{
    	    $result = true;
    	}
    	
    	return $this->outputJson( $this->getJsonResponse( array(
            'result'=>$result
    	) ) );
    }
        
    /**
     * Add an item to a wishlist
     */
    public function add()
    {
        $f3 = \Base::instance();
        
        // -----------------------------------------------------
        // Start: validation
        // -----------------------------------------------------
        $variant_id = $this->input->get('variant_id');            
        
        try {
            $product = (new \Shop\Models\Variants)->getById($variant_id);
        } catch (\Exception $e) {
            if ($f3->get('AJAX')) {
                return $this->outputJson( $this->getJsonResponse( array(
                    'result'=>false
                ) ) );            
            } else {
                \Dsc\System::addMessage('Item not added to wishlist - Invalid product', 'error');
                $f3->reroute('/shop/wishlist');
                return;            
            }
        }
        // -----------------------------------------------------
        // End: validation
        // -----------------------------------------------------
        
        // get the current user's wishlist, either based on session_id (visitor) or user_id (logged-in)
        $wishlist = \Shop\Models\Wishlists::fetch();
        
        // add the item
        try {
            $wishlist->addItem( $variant_id, $product, $f3->get('POST') );
        } catch (\Exception $e) {
            if ($f3->get('AJAX')) {
                return $this->outputJson( $this->getJsonResponse( array(
                    'result'=>false
                ) ) );
            } else {
                \Dsc\System::addMessage('Item not added to wishlist', 'error');
                \Dsc\System::addMessage($e->getMessage(), 'error');
                $f3->reroute('/shop/wishlist');
                return;
            }        	
        }
        
        if ($f3->get('AJAX')) {
            return $this->outputJson( $this->getJsonResponse( array(
                'result'=>true
            ) ) );
        } else {
            \Dsc\System::addMessage('Item added to wishlist');
        	$f3->reroute('/shop/wishlist');
        }
    }
    
    /**
     * Remove an item from the wishlist
     */
    public function remove()
    {
        $f3 = \Base::instance();
        
        // -----------------------------------------------------
        // Start: validation
        // -----------------------------------------------------
        // TODO validate the POST values
            // min: wishlistitem_hash
        $wishlistitem_hash = $this->inputfilter->clean( $f3->get('PARAMS.wishlistitem_hash'), 'cmd' );
        
        // TODO if validation fails, respond appropriately
        if ($f3->get('AJAX')) {
        
        } else {
        
        }
        // -----------------------------------------------------
        // End: validation
        // -----------------------------------------------------
        
        // get the current user's wishlist, either based on session_id (visitor) or user_id (logged-in)
        $wishlist = \Shop\Models\Wishlists::fetch();
        
        // remove the item
        try {
            $wishlist->removeItem( $wishlistitem_hash );
        } catch (\Exception $e) {
            // TODO respond appropriately with failure message
            // return;
        }
        
        //echo \Dsc\Debug::dump( $wishlist );
        
        // TODO respond appropriately
            // ajax?  send response object
            // otherwise redirect to wishlist
        
        if ($f3->get('AJAX')) {
        
        } else {
            \Dsc\System::addMessage('Item removed from wishlist');
            $f3->reroute('/shop/wishlist');
        }
    }
    
    /**
     * 
     */
    public function moveToCart()
    {
        $f3 = \Base::instance();
        
        $wishlist_id = $this->inputfilter->clean( $f3->get('PARAMS.id'), 'alnum' );
        $wishlistitem_hash = $this->inputfilter->clean( $f3->get('PARAMS.hash'), 'cmd' );

        $identity = \Dsc\System::instance()->get( 'auth' )->getIdentity();
        $session_id = \Dsc\System::instance()->get( 'session' )->id();
        $wishlist = (new \Shop\Models\Wishlists)->load( array(
            '_id' => new \MongoId( (string) $wishlist_id )
        ) );
        
        if (empty($wishlist->id)) 
        {
            if ($f3->get('AJAX')) {
                return $this->outputJson( $this->getJsonResponse( array(
                    'result'=>false,
                    'message'=>'Invalid wishlist'
                ) ) );
            } else {
                \Dsc\System::addMessage('Invalid Wishlist', 'error');
                $f3->reroute('/shop/wishlist');
                return;
            }
        }
        
        // Validate that this wishlist belongs to the current user
        if ($identity->id != $wishlist->user_id && $session_id != $wishlist->session_id) 
        {
            if ($f3->get('AJAX')) {
                return $this->outputJson( $this->getJsonResponse( array(
                    'result'=>false,
                    'message'=>'Not your wishlist'
                ) ) );
            } else {
                \Dsc\System::addMessage('Not your wishlist', 'error');
                $f3->reroute('/shop/wishlist');
                return;
            }        	
        }
        
        $cart = \Shop\Models\Carts::fetch();
        
        try {
            $wishlist->moveToCart( $wishlistitem_hash, $cart );
        } catch (\Exception $e) {
            if ($f3->get('AJAX')) {
                return $this->outputJson( $this->getJsonResponse( array(
                    'result'=>false,
                    'message'=>'Item could not be moved to cart'
                ) ) );
            } else {
                \Dsc\System::addMessage('Item could not be moved to cart', 'error');
                \Dsc\System::addMessage($e->getMessage(), 'error');
                $f3->reroute('/shop/wishlist/' . $wishlist->id );
                return;
            }
        }
        
        if ($f3->get('AJAX')) {
            return $this->outputJson( $this->getJsonResponse( array(
                'result'=>true,
                'message'=>'Item moved to cart'
            ) ) );
        } else {
            \Dsc\System::addMessage('Item moved to cart');
            $f3->reroute('/shop/wishlist/' . $wishlist->id );
        }
        
    }
}