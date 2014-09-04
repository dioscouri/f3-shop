<?php 
namespace Shop\ModuleConditions\Orders;

class Condition extends \Modules\Abstracts\Condition
{
    public function bootstrap()
    {
        $this->theme->registerViewPath( __dir__ . '/Views/', 'Shop/ModuleConditions/Orders/Views' );
    
        return parent::bootstrap();
    }
        
    /**
     * Returns the condition's html
     * for the admin-side module-editing form
     */
    public function html() 
    {
        return $this->theme->renderView('Shop/ModuleConditions/Orders/Views::index.php');
    }
    
    /**
     * Determines whether or not this condition passes
     *
     * @param string $route
     * @param unknown $options
    */
    public function passes(\Modules\Models\Modules $module, $route=null, $options=array())
    {
        // if this ruleset is ignored, return null
        if (!in_array($module->{'assignment.shop_orders.method'}, array(
            'include',
        )))
        {
            return null;
        }

        // user must be logged in for this condition to ever evaluate to be true
        $user = \Dsc\System::instance()->auth->getIdentity();
        if (empty($user->id)) 
        {
            return false;
        }
        
        $customer = new \Shop\Models\Customers($user);
        $order_count = $customer->ordersCount(true);
        
        $return = null;
        switch ($module->{'assignment.shop_orders.has_converted'})
        {
            case "1": // user has made an order

                if ($order_count > 0) {
                    $return = true;
                } else {
                    $return = false;
                }
                
                break;
            case "0": // user has never made an order
                
                if ($order_count > 0) {
                    $return = false;
                } else {
                    $return = true;
                }
                
                break;
        }
        
        return $return;
    }    
}