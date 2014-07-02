<?php 
namespace Shop\Admin\Controllers;

class OrderFailure extends \Admin\Controllers\BaseAuth 
{
    use \Dsc\Traits\Controllers\CrudItemCollection;

    protected $list_route = '/admin/shop/orderfailures';
    protected $create_item_route = '/admin/shop/orderfailure/create';
    protected $get_item_route = '/admin/shop/orderfailure/read/{id}';    
    protected $edit_item_route = '/admin/shop/orderfailure/edit/{id}';
    
    protected function getModel() 
    {
        $model = new \Shop\Models\OrderFailures;
        return $model; 
    }
    
    protected function getItem() 
    {
        $f3 = \Base::instance();
        $id = $this->inputfilter->clean( $f3->get('PARAMS.id'), 'alnum' );
        $model = $this->getModel()
            ->setState('filter.id', $id);

        try {
            $item = $model->getItem();
        } catch ( \Exception $e ) {
            \Dsc\System::instance()->addMessage( "Invalid Item: " . $e->getMessage(), 'error');
            $f3->reroute( $this->list_route );
            return;
        }

        return $item;
    }
    
    protected function displayCreate() 
    {
        $f3 = \Base::instance();

        $model = new \Shop\Models\Orders;
        
        $this->app->set('meta.title', 'Create Order | Shop');
        
        $view = \Dsc\System::instance()->get('theme');
        $view->event = $view->trigger( 'onDisplayShopOrdersEdit', array( 'item' => $this->getItem(), 'tabs' => array(), 'content' => array() ) );
        echo $view->render('Shop/Admin/Views::orderfailures/create.php');        
    }
    
    protected function displayEdit()
    {
        $f3 = \Base::instance();

        $model = new \Shop\Models\Orders;
        
        $flash = \Dsc\Flash::instance();
        
        $this->app->set('meta.title', 'Edit Order | Shop');
        
        $view = \Dsc\System::instance()->get('theme');
        $view->event = $view->trigger( 'onDisplayShopOrdersEdit', array( 'item' => $this->getItem(), 'tabs' => array(), 'content' => array() ) );        
        echo $view->render('Shop/Admin/Views::orderfailures/edit.php');
    }
    
    /**
     * This controller doesn't allow reading, only editing, so redirect to the edit method
     */
    protected function doRead(array $data, $key=null) 
    {
        $f3 = \Base::instance();
        $id = $this->getItem()->get( $this->getItemKey() );
        $route = str_replace('{id}', $id, $this->edit_item_route );
        $f3->reroute( $route );
    }
    
    protected function displayRead() {}
    
    protected function doDelete(array $data, $key=null)
    {
        $this->setRedirect( $this->list_route );
        
        return $this;
    }
    
    public function fulfill()
    {
        $data = \Base::instance()->get('REQUEST');
        if (!$this->canUpdate($data, $this->getItemKey())) {
            throw new \Exception('Not allowed to update record');
        }
    
        try {
            $item = $this->getItem();
            if (empty($item->id)) {
                throw new \Exception;
            }
            $item->fulfill();
        }
        catch(\Exception $e) {
            \Dsc\System::instance()->addMessage('Fulfillment failed with the following errors:', 'error');
            \Dsc\System::instance()->addMessage($e->getMessage(), 'error');
            if (\Base::instance()->get('DEBUG')) {
                \Dsc\System::instance()->addMessage($e->getTraceAsString(), 'error');
            }
        }
    
        $f3 = \Base::instance();
        $id = $this->inputfilter->clean( $f3->get('PARAMS.id'), 'alnum' );
    
        $custom_redirect = \Dsc\System::instance()->get( 'session' )->get( 'order.fulfill.redirect' );
        $redirect = $custom_redirect ? $custom_redirect : '/admin/shop/orderfailure/edit/' . $id;
        \Dsc\System::instance()->get( 'session' )->set( 'order.fulfill.redirect', null );
        $f3->reroute( $redirect );
    }
    
    public function fulfillGiftCards()
    {
        $data = \Base::instance()->get('REQUEST');
        if (!$this->canUpdate($data, $this->getItemKey())) {
            throw new \Exception('Not allowed to update record');
        }
        
        try {
            $item = $this->getItem();
            if (empty($item->id)) {
            	throw new \Exception;
            }
            $item->fulfillGiftCards();
        } 
        catch(\Exception $e) {
            \Dsc\System::instance()->addMessage('Fulfillment failed with the following errors:', 'error');
            \Dsc\System::instance()->addMessage($e->getMessage(), 'error');
            if (\Base::instance()->get('DEBUG')) {
                \Dsc\System::instance()->addMessage($e->getTraceAsString(), 'error');
            }
        }
        
        $f3 = \Base::instance();
        $id = $this->inputfilter->clean( $f3->get('PARAMS.id'), 'alnum' );
        
        $custom_redirect = \Dsc\System::instance()->get( 'session' )->get( 'order.fulfill.redirect' );
        $redirect = $custom_redirect ? $custom_redirect : '/admin/shop/orderfailure/edit/' . $id;
        \Dsc\System::instance()->get( 'session' )->set( 'order.fulfill.redirect', null );
        $f3->reroute( $redirect );
    }
    
    public function close()
    {
        $data = \Base::instance()->get('REQUEST');
        if (!$this->canUpdate($data, $this->getItemKey())) {
            throw new \Exception('Not allowed to update record');
        }
    
        try {
            $item = $this->getItem();
            if (empty($item->id)) {
                throw new \Exception;
            }
            $item->close();
        }
        catch(\Exception $e) {
            \Dsc\System::instance()->addMessage('Fulfillment failed with the following errors:', 'error');
            \Dsc\System::instance()->addMessage($e->getMessage(), 'error');
            if (\Base::instance()->get('DEBUG')) {
                \Dsc\System::instance()->addMessage($e->getTraceAsString(), 'error');
            }
        }
    
        $f3 = \Base::instance();
        $id = $this->inputfilter->clean( $f3->get('PARAMS.id'), 'alnum' );
    
        $custom_redirect = \Dsc\System::instance()->get( 'session' )->get( 'order.fulfill.redirect' );
        $redirect = $custom_redirect ? $custom_redirect : '/admin/shop/orderfailure/edit/' . $id;
        \Dsc\System::instance()->get( 'session' )->set( 'order.fulfill.redirect', null );
        $f3->reroute( $redirect );
    }
    
    public function cancel()
    {
        $data = \Base::instance()->get('REQUEST');
        if (!$this->canUpdate($data, $this->getItemKey())) {
            throw new \Exception('Not allowed to update record');
        }
    
        try {
            $item = $this->getItem();
            if (empty($item->id)) {
                throw new \Exception;
            }
            $item->cancel();
        }
        catch(\Exception $e) {
            \Dsc\System::instance()->addMessage('Fulfillment failed with the following errors:', 'error');
            \Dsc\System::instance()->addMessage($e->getMessage(), 'error');
            if (\Base::instance()->get('DEBUG')) {
                \Dsc\System::instance()->addMessage($e->getTraceAsString(), 'error');
            }
        }
    
        $f3 = \Base::instance();
        $id = $this->inputfilter->clean( $f3->get('PARAMS.id'), 'alnum' );
    
        $custom_redirect = \Dsc\System::instance()->get( 'session' )->get( 'order.fulfill.redirect' );
        $redirect = $custom_redirect ? $custom_redirect : '/admin/shop/orderfailure/edit/' . $id;
        \Dsc\System::instance()->get( 'session' )->set( 'order.fulfill.redirect', null );
        $f3->reroute( $redirect );
    }
    
    public function open()
    {
        $data = \Base::instance()->get('REQUEST');
        if (!$this->canUpdate($data, $this->getItemKey())) {
            throw new \Exception('Not allowed to update record');
        }
    
        try {
            $item = $this->getItem();
            if (empty($item->id)) {
                throw new \Exception;
            }
            $item->open();
        }
        catch(\Exception $e) {
            \Dsc\System::instance()->addMessage('Fulfillment failed with the following errors:', 'error');
            \Dsc\System::instance()->addMessage($e->getMessage(), 'error');
            if (\Base::instance()->get('DEBUG')) {
                \Dsc\System::instance()->addMessage($e->getTraceAsString(), 'error');
            }
        }
    
        $f3 = \Base::instance();
        $id = $this->inputfilter->clean( $f3->get('PARAMS.id'), 'alnum' );
    
        $custom_redirect = \Dsc\System::instance()->get( 'session' )->get( 'order.fulfill.redirect' );
        $redirect = $custom_redirect ? $custom_redirect : '/admin/shop/orderfailure/edit/' . $id;
        \Dsc\System::instance()->get( 'session' )->set( 'order.fulfill.redirect', null );
        $f3->reroute( $redirect );
    }
}