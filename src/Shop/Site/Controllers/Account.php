<?php 
namespace Shop\Site\Controllers;

class Account extends \Dsc\Controller 
{
    public function beforeRoute()
    {
        $this->requireIdentity();
    }
    
    public function index()
    {
        $identity = $this->getIdentity();
        
        $model = new \Shop\Models\Orders;
        $model->emptyState()->populateState()
            ->setParam('sort', $model->defaultSort())
            ->setState('filter.user', (string) $identity->id )
            ->setState('filter.status_excludes', \Shop\Constants\OrderStatus::cancelled)
        ;
        $state = $model->getState();

        try {
            $order = $model->getItem();
            $this->app->set('order', $order);
        } catch ( \Exception $e ) {

        }
        
        $this->app->set('meta.title', 'My Account');
        
    	$view = \Dsc\System::instance()->get('theme');
    	echo $view->render('Shop/Site/Views::account/index.php');
    }
}