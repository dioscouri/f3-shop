<?php 
namespace Shop\Admin\Controllers;

class OrderedGiftCard extends \Admin\Controllers\BaseAuth 
{
    use \Dsc\Traits\Controllers\CrudItemCollection;

    protected $list_route = '/admin/shop/orders/giftcards';
    protected $create_item_route = '/admin/shop/orders/giftcard/create';
    protected $get_item_route = '/admin/shop/orders/giftcard/read/{id}';
    protected $edit_item_route = '/admin/shop/orders/giftcard/edit/{id}';
    
    protected function getModel() 
    {
        $model = new \Shop\Models\OrderedGiftCards;
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

        $model = new \Shop\Models\Coupons;
        
        $this->app->set('meta.title', 'Issue a Gift Card | Shop');
        
        $view = \Dsc\System::instance()->get('theme');
        $view->event = $view->trigger( 'onDisplayShopOrderedGiftCardsEdit', array( 'item' => $this->getItem(), 'tabs' => array(), 'content' => array() ) );
        echo $view->render('Shop/Admin/Views::orderedgiftcards/create.php');        
    }
    
    protected function displayEdit()
    {
        $f3 = \Base::instance();

        $model = new \Shop\Models\Coupons;
        
        $flash = \Dsc\Flash::instance();

        $this->app->set('meta.title', 'Edit an Issued Gift Card | Shop');
        
        $view = \Dsc\System::instance()->get('theme');
        $view->event = $view->trigger( 'onDisplayShopOrderedGiftCardsEdit', array( 'item' => $this->getItem(), 'tabs' => array(), 'content' => array() ) );        
        echo $view->render('Shop/Admin/Views::orderedgiftcards/edit.php');
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
    
    /**
     * Target for POST to create new record
     */
    public function add()
    {
        $f3 = \Base::instance();
        $flash = \Dsc\Flash::instance();
        
        $data = \Base::instance()->get('REQUEST');

        //\Dsc\System::addMessage( \Dsc\Debug::dump($data) );        

        if (!$this->canCreate($data)) {
            throw new \Exception('Not allowed to add record');
        }
        
        $__customers = explode( ",", \Dsc\ArrayHelper::get($data, '__customers') );
        $__emails = explode( ",", \Dsc\ArrayHelper::get($data, '__emails') );
        
        $emails = array_filter( array_unique( array_merge(array(), $__customers, $__emails) ) );
        
        if (!empty($emails)) 
        {
        	try {
        	    $this->getModel()->issueToEmails( $data, $emails );
        	    
        	    switch ($data['submitType'])
        	    {
        	    	case "save_new":
        	    	    $route = $this->create_item_route;
        	    	    break;
        	    	case "save_close":
        	    	default:
        	    	    $route = $this->list_route;
        	    	    break;
        	    }
        	    
        	    $this->setRedirect( $route );

        	}
        	catch (\Exception $e) 
        	{
        	    \Dsc\System::instance()->addMessage('Save failed with the following errors:', 'error');
        	    \Dsc\System::instance()->addMessage($e->getMessage(), 'error');
        	    if (\Base::instance()->get('DEBUG')) {
        	        \Dsc\System::instance()->addMessage($e->getTraceAsString(), 'error');
        	    }
        	     
        	    // redirect back to the create form with the fields pre-populated
        	    \Dsc\System::instance()->setUserState('use_flash.' . $this->create_item_route, true);
        	    $flash->store($data);
        	    
        	    $this->setRedirect( $this->create_item_route );
        	}
        	
        }
        else {
        	// create just a single gift card
            $this->doAdd($data);
        }
    
        \Dsc\System::addMessage('Gift cards issued');
            
        if ($route = $this->getRedirect()) {
            \Base::instance()->reroute( $route );
        }
    
        return;
    }
}