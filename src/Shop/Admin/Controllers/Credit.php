<?php 
namespace Shop\Admin\Controllers;

class Credit extends \Admin\Controllers\BaseAuth 
{
    use \Dsc\Traits\Controllers\CrudItemCollection;

    protected $list_route = '/admin/shop/credits';
    protected $create_item_route = '/admin/shop/credit/create';
    protected $get_item_route = '/admin/shop/credit/read/{id}';
    protected $edit_item_route = '/admin/shop/credit/edit/{id}';
    
    protected function getModel() 
    {
        $model = new \Shop\Models\Credits;
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
        
        $this->app->set('meta.title', 'Issue a Credit | Shop');
        
        $view = \Dsc\System::instance()->get('theme');
        $view->event = $view->trigger( 'onDisplayShopCreditsEdit', array( 'item' => $this->getItem(), 'tabs' => array(), 'content' => array() ) );
        echo $view->render('Shop/Admin/Views::credits/create.php');        
    }
    
    protected function displayEdit()
    {
        $f3 = \Base::instance();

        $model = new \Shop\Models\Coupons;
        
        $flash = \Dsc\Flash::instance();

        $this->app->set('meta.title', 'Edit a Credit | Shop');
        
        $view = \Dsc\System::instance()->get('theme');
        $view->event = $view->trigger( 'onDisplayShopCreditsEdit', array( 'item' => $this->getItem(), 'tabs' => array(), 'content' => array() ) );        
        echo $view->render('Shop/Admin/Views::credits/edit.php');
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
     * 
     * @throws \Exception
     */
    public function issue()
    {
        try {
            $item = $this->getItem();
            if (empty($item->id)) {
            	throw new \Exception('Invalid Item');
            }
            $item->issue();
            \Dsc\System::addMessage('Store credit issued', 'success');
        }
        catch(\Exception $e) {
            \Dsc\System::addMessage('Issuing failed.', 'error');
            \Dsc\System::addMessage($e->getMessage(), 'error');
        }
        
        $id = $this->inputfilter->clean( $this->app->get('PARAMS.id'), 'alnum' );
        $this->app->reroute('/admin/shop/credit/edit/' . $id);        
    }
    
    public function revoke()
    {
        try {
            $item = $this->getItem();
            if (empty($item->id)) {
                throw new \Exception('Invalid Item');
            }
            $item->revoke();
            \Dsc\System::addMessage('Store credit revoked', 'success');
        }
        catch(\Exception $e) {
            \Dsc\System::addMessage('Revoke failed.', 'error');
            \Dsc\System::addMessage($e->getMessage(), 'error');
        }
        
        $id = $this->inputfilter->clean( $this->app->get('PARAMS.id'), 'alnum' );
        $this->app->reroute('/admin/shop/credit/edit/' . $id);
        
    }
}