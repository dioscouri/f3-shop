<?php 
namespace Shop\Admin\Controllers;

class Tag extends \Admin\Controllers\BaseAuth 
{
    use \Dsc\Traits\Controllers\CrudItemCollection;

    protected $list_route = '/admin/shop/tags';
    protected $create_item_route = '/admin/shop/tag/create';
    protected $get_item_route = '/admin/shop/tag/read/{id}';    
    protected $edit_item_route = '/admin/shop/tag/edit/{id}';
    
    protected function getModel() 
    {
        $model = new \Shop\Models\Tags;
        return $model; 
    }
    
    protected function getItem() 
    {
        $f3 = \Base::instance();
        $tag = strtolower( $this->inputfilter->clean( $f3->get('PARAMS.id'), 'string' ) );
        $model = new \Shop\Models\Tags( array('title'=>$tag) );

        return $model;
    }
    
    protected function displayCreate() 
    {
        $f3 = \Base::instance();
        
        $this->app->set('meta.title', 'Create Tag | Shop');
        
        $view = \Dsc\System::instance()->get('theme');
        $view->event = $view->trigger( 'onDisplayShopTagsEdit', array( 'item' => $this->getItem(), 'tabs' => array(), 'content' => array() ) );
        echo $view->render('Shop/Admin/Views::tags/create.php');        
    }
    
    protected function displayEdit()
    {
        $f3 = \Base::instance();
        
        $this->app->set('meta.title', 'Edit Tag | Shop');
        
        $view = \Dsc\System::instance()->get('theme');
        $view->event = $view->trigger( 'onDisplayShopTagsEdit', array( 'item' => $this->getItem(), 'tabs' => array(), 'content' => array() ) );
        echo $view->render('Shop/Admin/Views::tags/edit.php');
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
    
    public function quickadd()
    {
    	$model = $this->getModel();
    	$tags = $model->getList();
    	\Base::instance()->set('tags', $tags );
    	 
    	$view = \Dsc\System::instance()->get('theme');
    	echo $view->renderLayout('Shop/Admin/Views::tags/quickadd.php');
    }
}