<?php 
namespace Shop\Admin\Controllers;

class Campaign extends \Admin\Controllers\BaseAuth 
{
    use \Dsc\Traits\Controllers\CrudItemCollection;

    protected $list_route = '/admin/shop/campaigns';
    protected $create_item_route = '/admin/shop/campaign/create';
    protected $get_item_route = '/admin/shop/campaign/read/{id}';    
    protected $edit_item_route = '/admin/shop/campaign/edit/{id}';
    
    protected function getModel() 
    {
        $model = new \Shop\Models\Campaigns;
        return $model; 
    }
    
    protected function getItem() 
    {
        $id = $this->inputfilter->clean( $this->app->get('PARAMS.id'), 'alnum' );
        $model = $this->getModel()
            ->setState('filter.id', $id);

        try {
            $item = $model->getItem();
        } catch ( \Exception $e ) {
            \Dsc\System::instance()->addMessage( "Invalid Item: " . $e->getMessage(), 'error');
            $this->app->reroute( $this->list_route );
            return;
        }

        return $item;
    }
    
    protected function displayCreate() 
    {
        $this->app->set('meta.title', 'Create Campaign | Shop');
        
        $this->theme->event = $this->theme->trigger( 'onDisplayShopCampaignsEdit', array( 'item' => $this->getItem(), 'tabs' => array(), 'content' => array() ) );
        echo $this->theme->render('Shop/Admin/Views::campaigns/create.php');        
    }
    
    protected function displayEdit()
    {
        $this->app->set('meta.title', 'Edit Campaign | Shop');
        
        $this->theme->event = $this->theme->trigger( 'onDisplayShopCampaignsEdit', array( 'item' => $this->getItem(), 'tabs' => array(), 'content' => array() ) );        
        echo $this->theme->render('Shop/Admin/Views::campaigns/edit.php');
    }
    
    protected function displayRead() 
    {
        $this->app->set('meta.title', 'Campaign | Shop');
        
        $this->theme->event = $this->theme->trigger( 'onDisplayShopCampaigns', array( 'item' => $this->getItem(), 'tabs' => array(), 'content' => array() ) );
        echo $this->theme->render('Shop/Admin/Views::campaigns/read.php');    	
    }
}