<?php 
namespace Shop\Admin\Controllers;

class Settings extends \Admin\Controllers\BaseAuth 
{
	use \Dsc\Traits\Controllers\Settings;
	
	protected $layout_link = 'Shop/Admin/Views::settings/default.php';
	protected $settings_route = '/admin/shop/settings';
    
    protected function getModel()
    {
        $model = new \Shop\Models\Settings;
        return $model;
    }
    
    public function notifications()
    {
        $this->settings_route = '/admin/shop/settings/notifications';

        switch ($this->app->get('VERB')) {
        	case "POST":
        	case "post":
        	    
        	    $data = $this->app->get('REQUEST');
        	    
        	    $__emails = explode( ",", \Dsc\ArrayHelper::get($data, '__notifications_orders_emails') );
        	    $__emails = array_filter( array_unique( $__emails ) );
        	    $emails = array();
        	    foreach ($__emails as $__email) 
        	    {
        	        if (\Mailer\Abstracts\Sender::isEmailAddress( $__email )) 
        	        {
        	            $emails[] = $__email;
        	        }
        	    }
        	    $this->app->set('REQUEST.notifications.orders.emails', $emails);
        	     
        	    // do the save and redirect to $this->settings_route
        	    return $this->save();
        	    break;
        }
    
        $flash = \Dsc\Flash::instance();
        $this->app->set('flash', $flash );
    
        $settings = \Shop\Models\Settings::fetch();
        $flash->store( $settings->cast() );
    
        $this->app->set('meta.title', 'Notifications | Shop');
    
        echo $this->theme->render('Shop/Admin/Views::settings/notifications.php');
    }
    
    public function shippingMethods()
    {
    	$this->settings_route = '/admin/shop/shipping-methods';
    	 
    	$f3 = \Base::instance();
    	switch ($f3->get('VERB')) {
    		case "POST":
    		case "post":
    			// do the save and redirect to $this->settings_route
    			return $this->save();
    			break;
    	}
    
    	$flash = \Dsc\Flash::instance();
    	$f3->set('flash', $flash );
    
    	$settings = \Shop\Models\Settings::fetch();
    	$flash->store( $settings->cast() );
    
    	$this->app->set('meta.title', 'Shipping Methods | Shop');
    
    	$view = \Dsc\System::instance()->get('theme');
    	echo $view->renderTheme('Shop/Admin/Views::settings/shipping.php');
    }
    
    public function paymentMethods()
    {
    	$this->settings_route = '/admin/shop/payment-methods';
    	    
    	$f3 = \Base::instance();
    	switch ($f3->get('VERB')) {
    		case "POST":
    		case "post":
    			// do the save and redirect to $this->settings_route
    			return $this->save();
    			break;
    	}
    
    	$flash = \Dsc\Flash::instance();
    	$f3->set('flash', $flash );
    
    	$settings = \Shop\Models\Settings::fetch();
    	$flash->store( $settings->cast() );
    
    	$this->app->set('meta.title', 'Payment Methods | Shop');
    	    
    	$view = \Dsc\System::instance()->get('theme');
    	echo $view->renderTheme('Shop/Admin/Views::settings/payments.php');
    }
    
}