<?php 
namespace Shop\Site\Controllers;

class Order extends \Dsc\Controller 
{
    /**
     * List a user's orders
     *
     */
    public function index()
    {
        $identity = $this->getIdentity();
        if (empty($identity->id)) 
        {
            \Dsc\System::instance()->get('session')->set('site.login.redirect', '/shop/orders');
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
        
        $this->app->set('meta.title', 'My Orders');
        
    	$view = \Dsc\System::instance()->get('theme');
    	echo $view->render('Shop/Site/Views::orders/index.php');
    }
    
    /**
     * Display a single order
     */
    public function read()
    {
        $f3 = \Base::instance();
        $id = $this->inputfilter->clean( $f3->get('PARAMS.id'), 'alnum' );
                
        $identity = $this->getIdentity();
        if (empty($identity->id))
        {
            \Dsc\System::instance()->get('session')->set('site.login.redirect', '/shop/order/' . $id );
            \Base::instance()->reroute('/sign-in');
            return;
        }
    	
    	try {
    		$item = (new \Shop\Models\Orders)->setState('filter.id', $id)->getItem();
    		if (empty($item->id)) {
    			throw new \Exception;
    		}
    		if ((string) $item->user_id != (string) $identity->id) {
    		    throw new \Exception;
    		}    		
    	} catch ( \Exception $e ) {
    	    // TODO Change to a normal 404 error
    		\Dsc\System::instance()->addMessage( "Invalid Order", 'error');
    		\Dsc\System::instance()->addMessage( $e->getMessage(), 'error');
    		$f3->reroute( '/shop/orders' );
    		return;
    	}
    	
    	\Base::instance()->set('order', $item );
    	
    	if ($f3->get('print')) {
    	    $this->app->set('meta.title', 'Print | Order Detail');
    	    $view = \Dsc\System::instance()->get('theme');
    	    echo $view->renderView('Shop/Site/Views::order/print.php');
    	    return;    		
    	}
    	
    	$this->app->set('meta.title', 'Order Detail');
    	
    	$view = \Dsc\System::instance()->get('theme');
    	echo $view->render('Shop/Site/Views::order/detail.php');
    }
}