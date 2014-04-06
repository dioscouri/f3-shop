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
        $identity = $this->getIdentity();
        if (empty($identity->id))
        {
            \Dsc\System::instance()->get('session')->set('site.login.redirect', '/shop/wishlists');
            \Base::instance()->reroute('/sign-in');
            return;
        }
        
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
        
        $view = \Dsc\System::instance()->get('theme');
        echo $view->render('Shop/Site/Views::wishlist/index.php');        
    }
    
    /**
     * Display a user's wishlist
     */
    public function read()
    {
        $wishlist = \Shop\Models\Wishlists::fetch();
        // Update product fields stored in wishlist
        foreach ($wishlist->validateProducts() as $change) {
        	\Dsc\System::addMessage($change);
        }
        \Base::instance()->set('wishlist', $wishlist);
        
        $view = \Dsc\System::instance()->get('theme');
        echo $view->renderTheme('Shop/Site/Views::wishlist/read.php');        
    }
    
    /**
     * Finds a user's primary wishlist and redirects to its real URL (./shop/wishlist/@id).
     * Is really just a vanity URL.
     */
    public function primary() 
    {
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

    	$identity = $this->getIdentity();
    	if (empty($identity->id))
    	{
    	    // return a false message
    	    return $this->outputJson( $this->getJsonResponse( array(
    	    	
    	    ) ) );
    	}
    	    	
    	$count = (new \Shop\Models\Wishlists)->getCollection()->count(
            array( 'variants.id' => $variant_id, 'user_id' => new \MongoId( (string) $identity->id )
    	    )
    	);
    	
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
        // TODO validate the POST values
            // min: variant_id
        $variant_id = $this->input->get('variant_id');            
        
        // TODO load the product
        try {
            $product = (new \Shop\Models\Variants)->getById($variant_id);
        } catch (\Exception $e) {
            // TODO respond appropriately with failure message
            // return;
        }
        
        // TODO get the appropriate price using the user's groups, quantity added, and date
        // TODO check the quantity restrictions
        
        // TODO if validation fails, respond appropriately
        if ($f3->get('AJAX')) {
        
        } else {
            
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
        	// TODO respond appropriately with failure message
        	// return;
        }
        
        //echo \Dsc\Debug::dump( $wishlist );
                
        // TODO respond appropriately
            // ajax?  send response object
            // otherwise redirect to wishlist
        
        if ($f3->get('AJAX')) {

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
}