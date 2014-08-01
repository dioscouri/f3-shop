<?php 
namespace Shop\Admin\Controllers;

class PaymentMethods extends \Admin\Controllers\BaseAuth 
{
    public function index()
    {
        $items = (new \Shop\Models\PaymentMethods)->setState('filter.enabled', true)->getItems();
        $this->app->set('items', $items);
        
        $this->app->set('meta.title', 'Payment Methods | Shop');
        
        echo $this->theme->render('Shop/Admin/Views::paymentmethods/index.php');
    }
    
    public function select()
    {
        $items = (new \Shop\Models\PaymentMethods)->setState('filter.enabled', false)->getItems();
        $this->app->set('items', $items);
    
        $this->app->set('meta.title', 'Select | Payment Methods | Shop');
    
        echo $this->theme->render('Shop/Admin/Views::paymentmethods/select.php');
    }    
    
    public function read()
    {
        // load the report
        $slug = $this->inputfilter->clean( $this->app->get('PARAMS.slug'), 'cmd' );
        
        try {
            $item = (new \Shop\Models\PaymentMethods)->setState('filter.slug', $slug)->getItem();
            if (empty($item->id)) {
                throw new \Exception('Report not found');
            }
            
            $class = $item->getClass();

            
        } catch ( \Exception $e ) {

            \Dsc\System::instance()->addMessage( "Invalid Report", 'error');
            \Dsc\System::instance()->addMessage( $e->getMessage(), 'error');
            $this->app->reroute( '/admin/shop/reports' );
            return;
        }
        
        $this->app->set('report', $item);
        
        $this->app->set('meta.title', $item->title . ' | Reports | Shop');
        
        // display the report
        $class->index();
    }
    
    public function edit()
    {
        $id = $this->inputfilter->clean( $this->app->get('PARAMS.id'), 'cmd' );
        
        try {
            $item = (new \Shop\Models\PaymentMethods)->setState('filter.identifier', $id)->getItem();
            if (empty($item->id)) {
                throw new \Exception('Payment Method not found');
            }
        
            $class = $item->getClass();
        
        
        } catch ( \Exception $e ) {
        
            \Dsc\System::instance()->addMessage( "Invalid Payment Method", 'error');
            \Dsc\System::instance()->addMessage( $e->getMessage(), 'error');
            $this->app->reroute( '/admin/shop/payment-methods' );
            return;
        }
        
        $this->app->set('payment_method', $item);
        
        $this->app->set('meta.title', $item->title . ' | Payment Methods | Shop');
        
        $class->settings();        
    }
    
    public function update()
    {
        $id = $this->inputfilter->clean( $this->app->get('PARAMS.id'), 'cmd' );
        
        try {
            $item = (new \Shop\Models\PaymentMethods)->setState('filter.identifier', $id)->getItem();
            if (empty($item->id)) {
                throw new \Exception('Payment Method not found');
            }
        
            if ($settings_array = (array) $this->inputfilter->clean( $this->app->get('POST.settings'), 'array' )) 
            {
                $item->settings = $settings_array;
            }
        
            $item->save();
            
            \Dsc\System::addMessage( 'Settings updated', 'success' );
            
            $this->app->reroute( '/admin/shop/payment-method/edit/' . $id );
        
        } catch ( \Exception $e ) {
        
            \Dsc\System::addMessage( "Invalid Payment Method", 'error');
            \Dsc\System::addMessage( $e->getMessage(), 'error');
            $this->app->reroute( '/admin/shop/payment-methods' );
            return;
        }
    }
}