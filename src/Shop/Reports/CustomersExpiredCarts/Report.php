<?php 
namespace Shop\Reports\CustomersExpiredCarts;

class Report extends \Shop\Abstracts\Report 
{
    public function bootstrap()
    {
        $this->theme->registerViewPath( __dir__ . '/Views/', 'Shop/Reports/CustomersExpiredCarts/Views' );
        
        // Register any custom routes that the report needs
        $this->app->route( 'GET /admin/shop/reports/'.$this->slug().'/purge-expired', '\\' . __CLASS__ . '->purgeExpired' );
        $this->app->route( 'GET /admin/shop/reports/'.$this->slug().'/deleteCart/@cart_id', '\\' . __CLASS__ . '->deleteCart' );
        
        return parent::bootstrap();
    }
    
    /**
     * Primary entry-point for the report.
     * Supports GET & POST
     */
    public function index()
    {
        $model = (new \Shop\Models\Carts)->emptyState()->populateState();
        
        try {
            $paginated = $model->paginate();
        } catch ( \Exception $e ) {
            \Dsc\System::addMessage( $e->getMessage(), 'error');
            $this->app->reroute( '/admin/shop/reports/' . $this->slug() );
            return;
        }
        
        $this->app->set('state', $model->getState());
        $this->app->set('paginated', $paginated);
        
        echo $this->theme->render('Shop/Reports/CustomersExpiredCarts/Views::index.php');
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
    
    /**
     * Purge expired carts
     *
     */
    public function deleteCart()
    {
        $cart_id = $this->app->get('PARAMS.cart_id');
        
        $item = (new \Shop\Models\Carts)->setState('filter.id', $cart_id)->getItem();
        if (!empty($item->id)) 
        {
            try {
                $item->remove();
                \Dsc\System::addMessage( 'Removed cart', 'success' );
            }
            catch(\Exception $e) {
                \Dsc\System::addMessage( 'Could not remove cart', 'error' );
                \Dsc\System::addMessage( $e->getMessage(), 'error' );
            }
        }
         
        else 
        {
            \Dsc\System::addMessage( 'Invalid Cart ID', 'error' );
        }

    
        $this->app->reroute( '/admin/shop/reports/' . $this->slug() );
    }    
}