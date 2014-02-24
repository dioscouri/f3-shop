<?php 
namespace Shop\Admin\Controllers;

class Products extends \Admin\Controllers\BaseAuth 
{
    public function display()
    {
        \Base::instance()->set('pagetitle', 'Products');
        \Base::instance()->set('subtitle', '');
        
        $model = new \Shop\Admin\Models\Products;
        $state = $model->populateState()->getState();
        \Base::instance()->set('state', $state );
        
        $list = $model->paginate();
        \Base::instance()->set('list', $list );
        
        $pagination = new \Dsc\Pagination($list['total'], $list['limit']);       
        \Base::instance()->set('pagination', $pagination );
        
        $view = \Dsc\System::instance()->get('theme');
        echo $view->render('Shop\Admin\Views::products/list.php');
    }
}