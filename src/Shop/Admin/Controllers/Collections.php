<?php 
namespace Shop\Admin\Controllers;

class Collections extends \Admin\Controllers\BaseAuth 
{
    use \Dsc\Traits\Controllers\AdminList;
    
    protected $list_route = '/admin/shop/collections';

    protected function getModel()
    {
        $model = new \Shop\Admin\Models\Collections;
        return $model;
    }
    
    public function display()
    {
        \Base::instance()->set('pagetitle', 'Collections');
        \Base::instance()->set('subtitle', '');
        
        $model = $this->getModel();
        
        $state = $model->emptyState()->populateState()->getState();
        \Base::instance()->set('state', $state );
        
        $list = $model->paginate();
        \Base::instance()->set('list', $list );
        
        $pagination = new \Dsc\Pagination($list['total'], $list['limit']);       
        \Base::instance()->set('pagination', $pagination );
        
        \Base::instance()->set('selected', 'null' );
        
        $view = new \Dsc\Template;
        echo $view->render('Shop/Admin/Views::collections/list.php');
    }
    
    public function getDatatable()
    {
        $model = $this->getModel();
        
        $state = $model->populateState()->getState();
        \Base::instance()->set('state', $state );
        
        $list = $model->paginate();
        \Base::instance()->set('list', $list );
        
        $pagination = new \Dsc\Pagination($list['total'], $list['limit']);
        \Base::instance()->set('pagination', $pagination );
    
        $view = new \Dsc\Template;
        $html = $view->renderLayout('Shop/Admin/Views::collections/list_datatable.php');
        
        return $this->outputJson( $this->getJsonResponse( array(
                'result' => $html
        ) ) );
    
    }
    
    public function getAll()
    {
        $model = $this->getModel();
        $collections = $model->getList();
        \Base::instance()->set('collections', $collections );

        \Base::instance()->set('selected', 'null' );
        
        $view = new \Dsc\Template;
        $html = $view->renderLayout('Shop/Admin/Views::collections/list_parents.php');
        
        return $this->outputJson( $this->getJsonResponse( array(
                'result' => $html
        ) ) );
    
    }
    
    public function getCheckboxes()
    {
        $model = $this->getModel();
        $collections = $model->getList();
        \Base::instance()->set('collections', $collections );
    
        $selected = array();
        $data = \Base::instance()->get('REQUEST');
        
        $input = $data['collection_ids'];
        foreach ($input as $id) 
        {
            $id = $this->inputfilter->clean( $id, 'alnum' );
            $selected[] = array('id' => $id);
        }

        $flash = \Dsc\Flash::instance();
        $flash->store( array( 'metadata'=>array('collections'=>$selected) ) );
        \Base::instance()->set('flash', $flash );
        
        $view = new \Dsc\Template;
        $html = $view->renderLayout('Shop/Admin/Views::collections/checkboxes.php');
    
        return $this->outputJson( $this->getJsonResponse( array(
                'result' => $html
        ) ) );
    
    }
    
    public function selectList( $selected=null )
    {
        $model = $this->getModel();
        $collections = $model->getList();
        \Base::instance()->set('collections', $collections );
        \Base::instance()->set('selected', $selected );
         
        $view = new \Dsc\Template;
        echo $view->renderLayout('Shop/Admin/Views::collections/list_parents.php');
    }
}