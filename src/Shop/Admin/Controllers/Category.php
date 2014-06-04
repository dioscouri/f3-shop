<?php 
namespace Shop\Admin\Controllers;

class Category extends \Admin\Controllers\BaseAuth 
{
    use \Dsc\Traits\Controllers\CrudItemCollection;
    use \Dsc\Traits\Controllers\SupportPreview;
    
    protected $list_route = '/admin/shop/categories';
    protected $create_item_route = '/admin/shop/category/create';
    protected $get_item_route = '/admin/shop/category/read/{id}';    
    protected $edit_item_route = '/admin/shop/category/edit/{id}';
    
    protected function getModel() 
    {
        $model = new \Shop\Models\Categories;
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
        $this->app->set('pagetitle', 'Edit Category');

        $model = new \Shop\Models\Categories;
        $all = $model->emptyState()->getList();
        $this->app->set('all', $all );
        
        $this->app->set('selected', null );
        
        $view = \Dsc\System::instance()->get('theme');
        $view->event = $view->trigger( 'onDisplayShopCategoriesEdit', array( 'item' => $this->getItem(), 'tabs' => array(), 'content' => array() ) );
        
        $this->app->set('meta.title', 'Create Category | Shop');
        
        echo $view->render('Shop/Admin/Views::categories/create.php');        
    }
    
    protected function displayEdit()
    {
        $this->app->set('pagetitle', 'Edit Category');

        $model = new \Shop\Models\Categories;
        $categories = $model->emptyState()->getList();
        \Base::instance()->set('categories', $categories );
        
        $flash = \Dsc\Flash::instance();
        $selected = $flash->old('parent');
        \Base::instance()->set('selected', $selected );
        $this->app->set( 'allow_preview', $this->canPreview( true ) );
        
        $view = \Dsc\System::instance()->get('theme');
        $view->event = $view->trigger( 'onDisplayShopCategoriesEdit', array( 'item' => $this->getItem(), 'tabs' => array(), 'content' => array() ) );
        
        $this->app->set('meta.title', 'Edit Category | Shop');
        
        echo $view->render('Shop/Admin/Views::categories/edit.php');
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
    
    public function quickadd()
    {
    	$model = $this->getModel();
    	$categories = $model->getList();
    	$this->app->set('categories', $categories );
    	 
    	$view = \Dsc\System::instance()->get('theme');
    	echo $view->renderLayout('Shop/Admin/Views::categories/quickadd.php');
    }
}