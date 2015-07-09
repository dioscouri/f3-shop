<?php 
namespace Shop\Admin\Controllers;

class Categories extends \Admin\Controllers\BaseAuth 
{
    use \Dsc\Traits\Controllers\AdminList;
    use \Dsc\Traits\Controllers\SupportPreview;
    
    protected $list_route = '/admin/shop/categories';

    protected function getModel()
    {
        $model = new \Shop\Models\Categories;
        return $model;
    }
    
    public function index()
    {
        $model = $this->getModel();
        $state = $model->emptyState()->populateState()->getState();
        $this->app->set('state', $state );
        $paginated = $model->paginate();
        $this->app->set('paginated', $paginated );
        $this->app->set('selected', 'null' );
        
        $this->app->set('meta.title', 'Categories | Shop');
        $this->app->set( 'allow_preview', $this->canPreview( true ) );
        
        $view = \Dsc\System::instance()->get('theme');
        echo $view->render('Shop/Admin/Views::categories/list.php');
    }
    
    public function getDatatable()
    {
        $model = $this->getModel();
        
        $state = $model->populateState()->getState();
        $this->app->set('state', $state );
        
        $paginated = $model->paginate();
        $this->app->set('paginated', $paginated );
    
        $view = \Dsc\System::instance()->get('theme');
        $html = $view->renderLayout('Shop/Admin/Views::categories/list_datatable.php');
        
        return $this->outputJson( $this->getJsonResponse( array(
                'result' => $html
        ) ) );
    
    }
    
    public function getAll()
    {
        $model = $this->getModel();
        $categories = $model->getList();
        $this->app->set('categories', $categories );

        $this->app->set('selected', 'null' );
        
        $view = \Dsc\System::instance()->get('theme');
        $html = $view->renderLayout('Shop/Admin/Views::categories/list_parents.php');
        
        return $this->outputJson( $this->getJsonResponse( array(
                'result' => $html
        ) ) );
    
    }
    
    public function getCheckboxes()
    {
        $model = $this->getModel();
        $categories = $model->getList();
        $this->app->set('categories', $categories );
    
        $selected = array();
        $data = \Base::instance()->get('REQUEST');
        
        $input = $data['category_ids'];
        foreach ($input as $id) 
        {
            $id = $this->inputfilter->clean( $id, 'alnum' );
            $selected[] = array('id' => $id);
        }

        $flash = \Dsc\Flash::instance();
        $flash->store( array( 'metadata'=>array('categories'=>$selected) ) );
        $this->app->set('flash', $flash );
        
        $view = \Dsc\System::instance()->get('theme');
        $html = $view->renderLayout('Shop/Admin/Views::categories/checkboxes.php');
    
        return $this->outputJson( $this->getJsonResponse( array(
                'result' => $html
        ) ) );
    
    }
    
    public function selectList( $selected=null )
    {
        $model = $this->getModel();
        $categories = $model->getList();
        $this->app->set('categories', $categories );
        $this->app->set('selected', $selected );
         
        $view = \Dsc\System::instance()->get('theme');
        echo $view->renderLayout('Shop/Admin/Views::categories/list_parents.php');
    }
    
    public function gmTaxonomyForSelection()
    {
        $term = $this->input->get('q', null, 'default');
        $results = \Shop\Models\GoogleMerchantTaxonomy::forSelection($term);
    
        $response = new \stdClass;
        $response->more = false;
        $response->term = $term;
        $response->results = $results;
    
        return $this->outputJson($response);
    }
 
    public function forSelection()
    {
        $term = $this->input->get('q', null, 'default');

        $key =  new \MongoRegex('/'. $term .'/i');
        $results = \Shop\Models\Categories::forSelection(array('title'=>$key));
    
        $response = new \stdClass;
        $response->more = false;
        $response->term = $term;
        $response->results = $results;
    
        return $this->outputJson($response);
    }
}