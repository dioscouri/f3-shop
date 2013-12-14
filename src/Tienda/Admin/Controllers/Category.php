<?php 
namespace Tienda\Admin\Controllers;

class Category extends \Admin\Controllers\BaseAuth 
{
    use \Dsc\Traits\Controllers\CrudItem;

    protected $list_route = '/admin/tienda/categories';
    protected $create_item_route = '/admin/tienda/category';
    protected $get_item_route = '/admin/tienda/category/{id}';    
    protected $edit_item_route = '/admin/tienda/category/{id}/edit';
    
    protected function getModel() 
    {
        $model = new \Tienda\Admin\Models\Categories;
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
        $f3->set('pagetitle', 'Edit Category');

        $model = new \Tienda\Admin\Models\Categories;
        $all = $model->emptyState()->getList();
        \Base::instance()->set('all', $all );
        
        \Base::instance()->set('selected', null );
        
        $view = new \Dsc\Template;
        echo $view->render('categories/create.php');        
    }
    
    protected function displayEdit()
    {
        $f3 = \Base::instance();
        $f3->set('pagetitle', 'Edit Category');

        $model = new \Tienda\Admin\Models\Categories;
        $categories = $model->emptyState()->getList();
        \Base::instance()->set('categories', $categories );
        
        $flash = \Dsc\Flash::instance();
        $selected = $flash->old('parent');
        \Base::instance()->set('selected', $selected );
        
        $view = new \Dsc\Template;
        echo $view->render('categories/edit.php');
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
}