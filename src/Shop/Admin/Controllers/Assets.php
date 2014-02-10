<?php 
namespace Shop\Admin\Controllers;

class Assets extends \Admin\Controllers\BaseAuth 
{
    public function display()
    {
        \Base::instance()->set('pagetitle', 'Assets');
        \Base::instance()->set('subtitle', '');
        
        $model = new \Shop\Admin\Models\Assets;
        $state = $model->populateState()->getState();
        \Base::instance()->set('state', $state );

        $list = $model->paginate();
        \Base::instance()->set('list', $list );
        
        $pagination = new \Dsc\Pagination($list['total'], $list['limit']);       
        \Base::instance()->set('pagination', $pagination );
        
        $view = new \Dsc\Template;
        echo $view->render('Shop\Admin\Views::assets/list.php');
    }
}