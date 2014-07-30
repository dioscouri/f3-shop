<?php
namespace Shop\Models;

class PaymentMethods extends \Dsc\Mongo\Collection
{
    public $identifier = null; // required
    public $title = null; // required. human-readable
    public $namespace = null; // required, unique. Fully Qualified
    public $slug = null; // required, unique.
    
    public $enabled = false;
    
    protected $__order = null;             // \Shop\Models\Orders object
    protected $__cart = null;             // \Shop\Models\Carts object
    protected $__paymentData = array();    
    
    protected $__collection_name = 'shop.payment_methods';
    protected $__type = 'misc';

    protected $__config = array(
        'default_sort' => array(
            'type' => 1,
            'title' => 1
        )
    );

    protected function fetchConditions()
    {
        parent::fetchConditions();
        
        $filter_namespace = $this->getState('filter.namespace');
        if (strlen($filter_namespace))
        {
            $this->setCondition('namespace', $filter_namespace);
        }
        
        $filter_identifier = $this->getState('filter.identifier');
        if (strlen($filter_identifier))
        {
            $this->setCondition('identifier', $filter_identifier);
        }
        
        $filter_enabled = $this->getState('filter.enabled');
        if (strlen($filter_enabled) && !empty($filter_enabled))
        {
            $this->setCondition('enabled', array('$in' => array( true, '1', 1 ) ) );
        } 
        elseif (isset($filter_enabled) && is_bool($filter_enabled)) 
        {
            $this->setCondition('enabled', array('$nin' => array( true, '1', 1 ) ) );
        }        
        
        return $this;
    }

    protected function beforeValidate()
    {
        if (empty($this->slug))
        {
            $this->slug = \Web::instance()->slug($this->namespace);
        }
        
        return parent::beforeValidate();
    }

    /**
     * Register payment methods with the system.
     * Normal usage is within a Listener or a bootstrap file for registering a payment method for inclusion in lists
     *
     * @param unknown $name            
     */
    public static function register($namespace, array $options = array())
    {
        $item = (new static())->setState('filter.namespace', $namespace)->getItem();
        if (empty($item->id) || !empty($options['__update']))
        {
            try
            {
                if (empty($item->id))
                {
                    $item = new static();
                }
                
                $item->bind(array(
                    'namespace' => $namespace
                ))
                    ->bind($options)
                    ->save();
                
                return $item;
            }
            catch (\Exception $e)
            {
                return false;
            }
        }
        
        return true;
    }

    /**
     * Bootstrap all known payment methods
     * 
     * @return boolean
     */
    public static function bootstrapAll()
    {
        if ($items = (new static())->getItems())
        {
            foreach ($items as $item)
            {
                $item->getClass()->bootstrap();
            }
        }
        
        return true;
    }

    /**
     * Bootstrap just this payment method
     * 
     * @return boolean
     */
    public function bootstrap()
    {
        $this->getClass()->bootstrap();
        
        return true;
    }

    /**
     * Registers a path/folder where payment methods may be located
     * Used when getting a list of installed payment method
     *
     * Will register any detected payment methods with the system
     *
     * @param unknown $path            
     */
    public static function registerPath($path)
    {
        $paths = \Base::instance()->get('shop.payment.paths');
        if (empty($paths) || !is_array($paths))
        {
            $paths = array();
        }
        
        // if $path is not already registered, register it
        // last ones inserted are given priority by using unshift
        if (!in_array($path, $paths))
        {
            array_unshift($paths, $path);
            \Base::instance()->set('shop.payment.paths', $paths);
            
            // TODO Register each detected payment method
        }
        
        return $paths;
    }

    /**
     * Gets an instance of the payment method's class
     *
     * @throws \Exception
     * @return unknown
     */
    public function getClass()
    {
        $class_name = $this->namespace . '\PaymentMethod';
        if (!class_exists($class_name))
        {
            throw new \Exception('Class not found');
        }
        
        // get an instance of the class
        // add this model to the class
        $instance = new $class_name(array(
            'model' => $this
        ));
        
        if (!is_a($instance, '\Shop\PaymentMethods\PaymentAbstract'))
        {
            throw new \Exception('Class must be an instance of \Shop\PaymentMethods\PaymentAbstract');
        }
        
        $class = $instance;
        
        return $class;
    }

    /**
     * Helper method for creating select list options
     *
     * @param array $query            
     * @return multitype:multitype:string NULL
     */
    public static function forSelection(array $query = array())
    {
        $model = new static();
        
        $items = $model->find($query);
        
        $result = array();
        foreach ($items as $doc)
        {
            $array = array(
                'id' => (string) $doc['id'],
                'text' => htmlspecialchars($doc['name'], ENT_QUOTES)
            );
            $result[] = $array;
        }
        
        return $result;
    }
    
    /**
     * Add cart data to the model
     *
     * @param \Shop\Models\Carts $cart
     * 
     * @return \Shop\Models\PaymentMethods
     */
    public function addCart(\Shop\Models\Carts $cart)
    {
        $this->__cart = $cart;
    
        return $this;
    }    
    
    /**
     * Get the cart used for checkout
     */
    public function cart()
    {
        return $this->__cart;
    }

    /**
     * Add payment data to the model
     *
     * @param array $data
     *
     * @return \Shop\Models\PaymentMethods
     */
    public function addPaymentData(array $data)
    {
        $this->__paymentData = $data;
    
        return $this;
    }
    
    /**
     * Get the payment data
     */
    public function paymentData()
    {
        return $this->__paymentData;
    }    
    
    /**
     * Add an order to the model
     *
     * @param \Shop\Models\Orders $order
     *
     * @return \Shop\Models\PaymentMethods
     */
    public function addOrder(\Shop\Models\Orders $order)
    {
        $this->__order = $order;
    
        return $this;
    }
    
    /**
     * Get the order
     */
    public function order()
    {
        return $this->__order;
    }
}