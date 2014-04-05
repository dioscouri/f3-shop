<?php 
namespace Shop\Site\Controllers;

class Orders extends \Dsc\Controller 
{    
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
        
    	$view = \Dsc\System::instance()->get('theme');
    	echo $view->render('Shop/Site/Views::orders/index.php');
    }
}