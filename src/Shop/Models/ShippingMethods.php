<?php
namespace Shop\Models;

class ShippingMethods extends \Dsc\Models
{
    public $id = null;       // (string) unique identifier
    public $name = null;     // (string) human-readable name for display to customer
    public $price = null;    // $$ base price of shipping method
    public $extra = null;    // $$ any handling/surcharge/extra fees
    public $type = null;
    public $code = null;
    public $note = null;      // admin-only notes
    
    public static function find( array $query=array(), array $fields=array() )
    {
        $methods = \Base::instance()->get('shop.shipping.methods');
        
        //TODO loop through them and cast them as models?
        
        return $methods;
    }
    
    /**
     * Register shipping methods with the system.
     * Normal usage is within a Listener or a bootstrap file for registering a shipping method for inclusion in lists,
     * such as Coupon codes that offer free shipping discounts, etc.
     *
     * @param unknown $name
     */
    public static function register( array $new_methods=array() )
    {
        $methods = (array) \Base::instance()->get('shop.shipping.methods');
        if (empty($methods) || !is_array($methods))
        {
            $methods = array();
        }
    
        if (empty($new_methods))
        {
            return $methods;
        }
    
        if (!is_array($new_methods))
        {
            $new_methods = array( $new_methods );
        }
    
        foreach ($new_methods as $method)
        {
            // if $method is not already registered, register it
            if (!in_array($method, $methods))
            {
                $methods[] = $method;
            }
        }
    
        \Base::instance()->set('shop.shipping.methods', $methods);
    
        return $methods;
    }
    
    /**
     * Helper method for creating select list options
     *
     * @param array $query
     * @return multitype:multitype:string NULL
     */
    public static function forSelection(array $query=array())
    {
        $model = new static;
    
        $items = $model->find( $query );
    
        $result = array();
        foreach ($items as $doc) {
            $array = array(
                'id' => (string) $doc['id'],
                'text' => htmlspecialchars( $doc['name'], ENT_QUOTES ),
            );
            $result[] = $array;
        }
    
        return $result;
    }
    
    /**
     * Gets the total cost of this shipping method
     * 
     * @return number
     */
    public function total()
    {
        return $this->price + $this->extra;
    }
}