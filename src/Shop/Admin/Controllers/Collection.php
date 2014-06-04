<?php 
namespace Shop\Admin\Controllers;

class Collection extends \Admin\Controllers\BaseAuth 
{
    use \Dsc\Traits\Controllers\CrudItemCollection;
    use \Dsc\Traits\Controllers\SupportPreview;
    
    protected $list_route = '/admin/shop/collections';
    protected $create_item_route = '/admin/shop/collection/create';
    protected $get_item_route = '/admin/shop/collection/read/{id}';    
    protected $edit_item_route = '/admin/shop/collection/edit/{id}';
    
    protected function getModel() 
    {
        $model = new \Shop\Models\Collections;
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
        $model = new \Shop\Models\Collections;
        
        $view = \Dsc\System::instance()->get('theme');
        $view->event = $view->trigger( 'onDisplayShopCollectionsEdit', array( 'item' => $this->getItem(), 'tabs' => array(), 'content' => array() ) );
        
        $this->app->set('meta.title', 'Create Collection | Shop');
        
        echo $view->render('Shop/Admin/Views::collections/create.php');        
    }
    
    protected function displayEdit()
    {
        $model = new \Shop\Models\Collections;
        
        $flash = \Dsc\Flash::instance();
        $this->app->set( 'allow_preview', $this->canPreview( true ) );
        
        $view = \Dsc\System::instance()->get('theme');
        $view->event = $view->trigger( 'onDisplayShopCollectionsEdit', array( 'item' => $this->getItem(), 'tabs' => array(), 'content' => array() ) );

        $this->app->set('meta.title', 'Edit Collection | Shop');
        $this->app->set( 'allow_preview', $this->canPreview( true ) );
        
        echo $view->render('Shop/Admin/Views::collections/edit.php');
    }
    
    /**
     * This controller doesn't allow reading, only editing, so redirect to the edit method
     */
    protected function doRead(array $data, $key=null) 
    {
        $id = $this->getItem()->get( $this->getItemKey() );
        $route = str_replace('{id}', $id, $this->edit_item_route );
        $this->app->reroute( $route );
    }
    
    protected function displayRead() {}
    
    public function products() 
    {
        $model = (new \Shop\Models\Products)->populateState();
        $id = $this->inputfilter->clean( $this->app->get('PARAMS.id'), 'alnum' );
        
        try {
            $collection = (new \Shop\Models\Collections)->setState('filter.id', $id)->getItem();
            if (empty($collection->id)) {
                throw new \Exception('Invalid Collection');
            }            
            $conditions = \Shop\Models\Collections::getProductQueryConditions($collection->id);
            
            if (!$model->getState('list.limit')) {
                $model->setState('list.limit', '100');
            }

            $paginated = $model->setParam('conditions', $conditions)->setState('list.sort', array(
            	array( 'collections.'. $id .'.ordering' => 1 )
            ))->paginate();
            
            $this->app->set('paginated', $paginated);
            $this->app->set('collection', $collection);
            $this->app->set('state', $model->getState());
        }
        catch ( \Exception $e )
        {
            \Dsc\System::addMessage( (string) $e, 'error');
            $this->app->reroute( '/admin/shop/collections' );
        }        
        
        $this->app->set('meta.title', 'Manually Sort Products in Collection | Shop');
        
        echo $this->theme->renderTheme('Shop/Admin/Views::collections/products.php');    	
    }
    
    public function saveProductsOrder()
    {
        $collection_id = $this->inputfilter->clean( $this->app->get('PARAMS.id'), 'alnum' );
        $products_ordering = $this->inputfilter->clean( $this->app->get('REQUEST.ordering'), 'array' );
        
        // Loop thru the ordering array from the POST
        // key = id of product, value = ordering position
        // update the product's document, setting $product->{'collections.' . $collection->id . '.ordering'} = value
        // return to the ordering page
        
        foreach ($products_ordering as $product_id=>$ordering) 
        {
        	$product = (new \Shop\Models\Products)->setState('filter.id', $product_id)->getItem();
        	if (!empty($product->id)) 
        	{
        		$product->update(array(
        		    'collections.' . $collection_id . '.ordering' => (int) $ordering
        		), array(
        		    'overwrite' => false
        		));
        	}
        }
        
        $redirect = $this->session->get('collections.products.current_page') ? '/admin/shop/collection/' . $collection_id . '/products/page/' . $this->session->get('collections.products.current_page') : '/admin/shop/collection/' . $collection_id . '/products';
        $this->app->reroute( $redirect );
        
    }
}