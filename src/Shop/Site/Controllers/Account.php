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
        
    	echo $this->theme->render('Shop/Site/Views::account/index.php');
    }
    
    public function productReviews()
    {
        $user = $this->getIdentity();
        
        $model = new \Shop\Models\Orders;
        $state = $model->populateState()->getState();
        
        $is_reviewed = null;
        if (strlen($state->get('filter.is_reviewed'))) {
            if ($state->get('filter.is_reviewed')) {
                $is_reviewed = true;
            }
            else {
                $is_reviewed = false;
            }
        }
                
        try {
            $paginated = \Shop\Models\Customers::purchasedProducts( $user, array(
                'offset' => $state->get('list.offset'),
                'keyword' => $state->get('filter.keyword'),
                'is_reviewed' => $is_reviewed
            ) );
        } catch ( \Exception $e ) {
        
        }
        
        $this->app->set('meta.title', 'My Reviews');
        $this->app->set('paginated', $paginated);
        $this->app->set('state', $state);
        
        echo $this->theme->render('Shop/Site/Views::account/product_reviews.php');        
    }
}