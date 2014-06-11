<?php 
namespace Shop\Reports\OrdersByCouponCode;

class Report extends \Shop\Abstracts\Report 
{
    public function bootstrap()
    {
        $this->theme->registerViewPath( __dir__ . '/Views/', 'Shop/Reports/OrdersByCouponCode/Views' );
        
        return parent::bootstrap();
    }
    
    /**
     * Primary entry-point for the report.
     * Supports GET & POST
     */
    public function index()
    {
        $model = (new \Shop\Models\Coupons)->emptyState()->populateState();
        
        try {
            $paginated = $model->paginate();
        } catch ( \Exception $e ) {
            \Dsc\System::addMessage( $e->getMessage(), 'error');
            $this->app->reroute( '/admin/shop/reports/' . $this->slug() );
            return;
        }
        
        $this->app->set('state', $model->getState());
        $this->app->set('paginated', $paginated);
        
        echo $this->theme->render('Shop/Reports/OrdersByCouponCode/Views::index.php');
    }

    /**
     * Purge expired carts
     * 
     */
    public function purgeExpired()
    {
        // TODO Push this into the carts model with an input for date
        $count = 0;
        if ($items = (new \Shop\Models\Carts)->emptyState()->setState('filter.cart_type', 'session')->setState('filter.last_modified_before', date('Y-m-d', strtotime('yesterday')))->getItems()) 
        {
        	foreach ($items as $item) 
        	{
        		try {
        		    $item->remove();
        		    $count++;
        		}
        		catch(\Exception $e) {
        		    
        		}
        	}
        }
        
        \Dsc\System::addMessage( 'Purged ' . $count . ' expired session carts', 'success' );
        
        $this->app->reroute( '/admin/shop/reports/' . $this->slug() );
    }
}