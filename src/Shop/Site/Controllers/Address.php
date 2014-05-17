<?php
namespace Shop\Site\Controllers;

class Address extends \Dsc\Controller
{
    use \Dsc\Traits\Controllers\CrudItemCollection;
    
    protected $list_route = '/shop/account/addresses';
    protected $create_item_route = '/shop/account/addresses/create';
    protected $get_item_route = '/shop/account/addresses/read/{id}';
    protected $edit_item_route = '/shop/account/addresses/edit/{id}';
    
    protected function getModel()
    {
        $model = new \Shop\Models\CustomerAddresses;
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
        $identity = $this->getIdentity();
        if (empty($identity->id))
        {
            \Dsc\System::instance()->get('session')->set('site.login.redirect', '/shop/account/addresses/create' );
            \Base::instance()->reroute('/sign-in');
            return;
        }
    
        $flash = \Dsc\Flash::instance();
        $use_flash = \Dsc\System::instance()->getUserState('use_flash.' . $this->create_item_route);
        if (!$use_flash) {
            // this is a brand-new create, so store the prefab data
            $flash->store( $this->getModel()->cast() );
        }
    
        $this->app->set('meta.title', 'New Address');
        
        $view = \Dsc\System::instance()->get('theme');
        echo $view->render('Shop/Site/Views::addresses/create.php');
    }
    
    protected function displayEdit()
    {
        $identity = $this->getIdentity();
        if (empty($identity->id))
        {
            \Dsc\System::instance()->get('session')->set('site.login.redirect', '/shop/account/addresses/edit/' . $id );
            \Base::instance()->reroute('/sign-in');
            return;
        }
        
        $this->app->set('meta.title', 'Edit Address');
    
        $view = \Dsc\System::instance()->get('theme');
        echo $view->render('Shop/Site/Views::addresses/edit.php');
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
    
    
    protected function canCreate(array $data)
    {
        $f3 = \Base::instance();
        $id = $this->inputfilter->clean( $f3->get('PARAMS.id'), 'alnum' );
        
        $identity = $this->getIdentity();
        if (empty($identity->id))
        {
            \Dsc\System::instance()->get('session')->set('site.login.redirect', '/shop/account/addresses/create' );
            \Base::instance()->reroute('/sign-in');
            return;
        }
                
        return true;
    }
    
    protected function canRead(array $data, $key=null)
    {
        $f3 = \Base::instance();
        $id = $this->inputfilter->clean( $f3->get('PARAMS.id'), 'alnum' );
        
        $identity = $this->getIdentity();
        if (empty($identity->id))
        {
            \Dsc\System::instance()->get('session')->set('site.login.redirect', '/shop/account/addresses/edit/' . $id );
            \Base::instance()->reroute('/sign-in');
            return;
        }
        
        try {
            $item = $this->getItem();
            if (empty($item->id)) {
                throw new \Exception;
            }
            if ((string) $item->user_id != (string) $identity->id) {
                throw new \Exception;
            }
        } catch ( \Exception $e ) {
            \Dsc\System::instance()->addMessage( "Invalid Address", 'error');
            \Dsc\System::instance()->addMessage( $e->getMessage(), 'error');
            $f3->reroute( '/shop/account/addresses' );
            return;
        }
        
        return true;
    }
    
    protected function canUpdate(array $data, $key=null)
    {
        $f3 = \Base::instance();
        $id = $this->inputfilter->clean( $f3->get('PARAMS.id'), 'alnum' );
        
        $identity = $this->getIdentity();
        if (empty($identity->id))
        {
            \Dsc\System::instance()->get('session')->set('site.login.redirect', '/shop/account/addresses/edit/' . $id );
            \Base::instance()->reroute('/sign-in');
            return;
        }
        
        try {
            $item = $this->getItem();
            if (empty($item->id)) {
                throw new \Exception;
            }
            if ((string) $item->user_id != (string) $identity->id) {
                throw new \Exception;
            }
        } catch ( \Exception $e ) {
            \Dsc\System::instance()->addMessage( "Invalid Address", 'error');
            \Dsc\System::instance()->addMessage( $e->getMessage(), 'error');
            $f3->reroute( '/shop/account/addresses' );
            return;
        }
                
        return true;
    }
    
    protected function canDelete(array $data, $key=null)
    {
        $f3 = \Base::instance();
        $id = $this->inputfilter->clean( $f3->get('PARAMS.id'), 'alnum' );
        
        $identity = $this->getIdentity();
        if (empty($identity->id))
        {
            \Dsc\System::instance()->get('session')->set('site.login.redirect', '/shop/account/addresses/edit/' . $id );
            \Base::instance()->reroute('/sign-in');
            return;
        }
        
        try {
            $item = $this->getItem();
            if (empty($item->id)) {
                throw new \Exception;
            }
            if ((string) $item->user_id != (string) $identity->id) {
                throw new \Exception;
            }
        } catch ( \Exception $e ) {
            \Dsc\System::instance()->addMessage( "Invalid Address", 'error');
            \Dsc\System::instance()->addMessage( $e->getMessage(), 'error');
            $f3->reroute( '/shop/account/addresses' );
            return;
        }
        
        return true;
    }
    
    /**
     * List a user's addresses
     *
     */
    public function index()
    {
        $identity = $this->getIdentity();
        if (empty($identity->id))
        {
            \Dsc\System::instance()->get('session')->set('site.login.redirect', '/shop/account/addresses');
            \Base::instance()->reroute('/sign-in');
            return;
        }
    
        $model = new \Shop\Models\CustomerAddresses;
        $model->emptyState()->populateState()
        ->setState('list.limit', 40 )
        ->setState('filter.user', (string) $identity->id );
        $state = $model->getState();
    
        try {
            $paginated = $model->paginate();
        } catch ( \Exception $e ) {
            \Dsc\System::instance()->addMessage( $e->getMessage(), 'error');
            $f3->reroute( '/shop/account' );
            return;
        }
    
        $this->app->set('meta.title', 'Address Book');
        
        \Base::instance()->set('state', $state );
        \Base::instance()->set('paginated', $paginated );
    
        $view = \Dsc\System::instance()->get('theme');
        echo $view->render('Shop/Site/Views::addresses/index.php');
    }
        
    /**
     * Gets a list of countries
     */
    public function countries()
    {
        $result = \Shop\Models\Countries::find();
        
        return $this->outputJson( $this->getJsonResponse( array(
            'message' => \Dsc\System::instance()->renderMessages(),
            'result' => $result 
        ) ) );
    }

    /**
     * Gets a list of regions, filtered by a country isocode_2
     */
    public function regions()
    {
        $f3 = \Base::instance();
        $country_isocode_2 = $f3->get('PARAMS.country_isocode_2');
        
        $result = \Shop\Models\Regions::byCountry( $country_isocode_2 );
        
        return $this->outputJson( $this->getJsonResponse( array(
            'message' => \Dsc\System::instance()->renderMessages(),
            'result' => $result 
        ) ) );
    }

    /**
     * Validates an address
     */
    public function validate()
    {
        // TODO Validate using the address model
    }
}