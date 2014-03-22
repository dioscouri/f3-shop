<?php
namespace Shop\Models;

class Carts extends \Dsc\Mongo\Collections\Nodes
{
    public $user_id = null;
    public $session_id = null;
    public $items = array();        // array of \Shop\Models\Prefabs\CartItem objects
    public $taxes = array();        // array of \Shop\Models\Prefabs\Tax objects
    public $coupons = array();      // array of \Shop\Models\Prefabs\Coupon objects
    public $discounts = array();    // array of \Shop\Models\Prefabs\Discount objects
    public $shipping_address = array();      // address used for estimating shipping
    public $name = null;            // user-defined name for cart
    
    // TODO Remove these?  Why would we use these?
    public $order = null;           // \Shop\Models\Orders object cast as array when cart is completed
    public $order_id = null;        // id of order after checkout    
    
    protected $__collection_name = 'shop.carts';
    protected $__type = 'shop.carts';
    protected $__config = array(
        'default_sort' => array(
            'metadata.created.time' => - 1 
        ) 
    );

    protected function fetchConditions()
    {
        parent::fetchConditions();
        
        $this->setCondition( 'type', $this->__type );
        
        return $this;
    }
    
    /**
     * Get the current user's cart, either based on session_id (visitor) or user_id (logged-in)
     * 
     * @return \Shop\Models\Carts
     */
    public static function fetch()
    {
        $cart = new self;
        
        $identity = \Dsc\System::instance()->get('auth')->getIdentity();
        $session_id = \Dsc\System::instance()->get('session')->id();
        
        if (empty($identity->id))
        {
            $cart->load(array('session_id' => $session_id));
            if (empty($cart->id))
            {
                $cart->session_id = $session_id;
                try {
                    $cart->save();
                } catch (\Exception $e) {
                    // TODO respond appropriately with failure message
                    // return;
                }
            }
        }
        else
        {
            $cart->load(array('user_id' => $identity->id));
            $cart->session_id = $session_id;
            $cart->save();
        }
        
        return $cart;
    }

    /**
     * Adds an item to the cart
     * 
     * @param string $variant_id
     * @param \Shop\Models\Products $product
     * @param array $post
     */
    public function addItem( $variant_id, \Shop\Models\Products $product, array $post )
    {
        $options = !empty($post['options']) ? $post['options'] : array();
        $quantity = (!empty($post['quantity']) && $post['quantity'] > 0) ? (int) $post['quantity'] : 1;
        $price = $product->price();
        if (!$variant = $product->variant($variant_id)) {
            $variant = array();
        }
        
        $attribute_title = \Dsc\ArrayHelper::get( $variant, 'attribute_title' );
        $attribute_titles = \Dsc\ArrayHelper::get( $variant, 'attribute_titles' );
        $attributes = \Dsc\ArrayHelper::get( $variant, 'attributes' );
        $sku = \Dsc\ArrayHelper::get( $variant, 'sku' );
        $model_number = \Dsc\ArrayHelper::get( $variant, 'model_number' );
        $upc = \Dsc\ArrayHelper::get( $variant, 'upc' );
        $weight = \Dsc\ArrayHelper::get( $variant, 'weight' );
        $image = \Dsc\ArrayHelper::get( $variant, 'image' );
        
        $cartitem = new \Shop\Models\Prefabs\CartItem(array(
        	'variant_id' => (string) $variant_id,
            'options' => (array) $options,
            'product' => $product->cast(),
            'product_id' => $product->id,
            'quantity' => $quantity,
            'price' => $price,
            // Set these based on the variant_id and the product
            'attribute_title' => !empty($attribute_title) ? $attribute_title : null,
            'attribute_titles' => !empty($attribute_titles) ? $attribute_titles : array(),
            'attributes' => !empty($attributes) ? $attributes : array(),
            'sku' => !empty($sku) ? $sku : $product->{'tracking.sku'},
            'model_number' => !empty($model_number) ? $model_number : $product->{'tracking.model_number'},
            'upc' => !empty($upc) ? $upc : $product->{'tracking.upc'},
            'weight' => !empty($weight) ? $weight : $product->{'shipping.weight'},
            'image' => !empty($image) ? $image : $product->{'featured_image.slug'}                        
        )); 
        
        // Is the item already in the cart?
            // if so, inc quantity
            // otherwise add the cartitem
        $exists = false;
        foreach ($this->items as $key=>$item)
        {
            if ($item['hash'] == $cartitem->hash)
            {
                $exists = true;
                $cartitem->id = $item['id'];
                $cartitem->quantity = $cartitem->quantity + $item['quantity']; 
                $this->items[$key] = $cartitem->cast();
                
                break;
            }
        }
        
        if (!$exists) {
            $this->items[] = $cartitem->cast();
        }        
        
        return $this->save();
    }

    public function updateItemQuantity( $cartitem_hash, $new_quantity )
    {
        $exists = false;
        foreach ($this->items as $key=>$item)
        {
            if ($item['hash'] == $cartitem_hash)
            {
                $exists = true;
                $this->items[$key]['quantity'] = $new_quantity;
                
                break;
            }
        }
        
        if ($exists) {
            $this->save();
        }        
        
        return $this;
    }

    /**
     * Removes an item from the cart
     * 
     * @param unknown $cartitem_hash
     * @return \Shop\Models\Carts
     */
    public function removeItem( $cartitem_hash )
    {
        $exists = false;
        foreach ($this->items as $key=>$item)
        {
            if ($item['hash'] == $cartitem_hash)
            {
                $exists = true;
                unset($this->items[$key]);
                
                break;
            }
        }
        
        if ($exists) {
            $this->save();
        }        
        
        return $this;
    }

    /**
     * Merges the data object into $this
     *
     * @param unknown $data            
     */
    public function merge( $data )
    {
        return $this;
    }
    
    /**
     * Get a cart item using its hash
     *
     * @param unknown $id
     */
    public function fetchItemByHash($hash)
    {
        if (empty($this->items)) {
            return false;
        }
    
        foreach ($this->items as $item)
        {
            if ($item['hash'] == $hash) {
                return $item;
            }
        }
    
        return false;
    }
    
    /**
     * Given a cartitem, calculates the subtotal
     * 
     * @param unknown $item
     */
    public static function calcItemSubtotal( $data )
    {
        $subtotal = $data['quantity'] * $data['price'];
        return $subtotal;
    }
    
    /**
     * Gets the subtotal
     * 
     * @return number
     */
    public function subtotal()
    {
        $subtotal = 0;
        
        if (empty($this->items)) {
            return $subtotal;
        }
        
        foreach ($this->items as $item)
        {
            $subtotal += self::calcItemSubtotal( $item );
        }
        
        return $subtotal;
    }
    
    /**
     * 
     * @return number
     */
    public function shipping_estimate()
    {
        $estimate = 0;
        return $estimate;
    }
    
    /**
     * 
     * @return number
     */
    public function tax_estimate()
    {
        $estimate = 0;
        return $estimate;
    }
    
    /**
     * Gets the total,
     * incl. subtotal, shipping estimate (if possible) and tax (if possible).
     * 
     * @return number
     */
    public function total()
    {
        $total = $this->subtotal()
            + $this->shipping_estimate()
            + $this->tax_estimate();
    
        return $total;
    }

    protected function beforeValidate()
    {
        if (! $this->get( 'metadata.creator' ))
        {
            $identity = \Dsc\System::instance()->get( 'auth' )->getIdentity();
            if (! empty( $identity->id ))
            {
                $this->set( 'metadata.creator', array(
                    'id' => $identity->id,
                    'name' => $identity->getName() 
                ) );
            }
            else
            {
                $this->set( 'metadata.creator', array(
                    'id' => \Dsc\System::instance()->get( 'session' )->id(),
                    'name' => 'session' 
                ) );
            }
        }
        
        if (!empty($this->items)) 
        {
            $this->items = array_values($this->items);
        }
        
        return parent::beforeValidate();
    }
}