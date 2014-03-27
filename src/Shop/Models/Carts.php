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
    public $name = null;            // user-defined name for cart
    
    public $checkout = array( // array of values used during checkout
        'order_comments' => null,
        'shipping_address' => array(),
        'billing_address' => array()
    );
    
    public $shipping_methods = array();    // array of \Shop\Models\ShippingMethod objects, each with a rate
    
    protected $__collection_name = 'shop.carts';
    protected $__type = 'shop.carts';
    protected $__config = array(
        'default_sort' => array(
            'metadata.created.time' => -1 
        ) 
    );

    protected function fetchConditions()
    {
        parent::fetchConditions();
        
        $this->setCondition( 'type', $this->__type );
        
        $filter_user = $this->getState('filter.user');
        if (strlen($filter_user))
        {
            $this->setCondition('user_id', new \MongoId((string) $filter_user));
        }
        
        return $this;
    }
    
    /**
     * Get the current user's cart, either based on session_id (visitor) or user_id (logged-in)
     * 
     * @return \Shop\Models\Carts
     */
    public static function fetch()
    {
        $cart = new static;
        
        // TODO does the session have a cart_id specified?  if so, use it to get the cart
        
        $identity = \Dsc\System::instance()->get('auth')->getIdentity();
        $session_id = \Dsc\System::instance()->get('session')->id();
        
        if (empty($identity->id))
        {
            $cart->load(array('session_id' => $session_id));
            $cart->session_id = $session_id;
        }
        else
        {
            $cart->load(array('user_id' => new \MongoId( (string) $identity->id ) ));
            $cart->user_id = $identity->id;
            
            $session_cart = static::fetchForSession();
            
            // if there was no user cart but there IS a session cart, just add the user_id to the session cart and save it
            if (empty($cart->id) && !empty($session_cart->id)) 
            {
            	$cart = $session_cart;
            	$cart->user_id = $identity->id;
            	$cart->save();
            }
            
            // if there was a user cart and there is a session cart, merge them and delete the session cart
            // if we already did the merge, skip this
            $session_cart_merged = \Dsc\System::instance()->get('session')->get('shop.session_cart_merged');
            if (!empty($session_cart->id) && $session_cart->id != $cart->id && empty($session_cart_merged))
            {
                $cart->session_id = $session_id;
                $cart->merge( $session_cart->cast() );
                $session_cart->remove();
                \Dsc\System::instance()->get('session')->set('shop.session_cart_merged', true);
            }
            
            /*
            if (!empty($cart->id)) 
            {
                if ($cart->session_id != $session_id)
                {
                    $cart->session_id = $session_id;
                    $cart->save();
                }
            }
             */
        }
        
        return $cart;
    }
    
    /**
     * Get the current session's cart
     *
     * @return \Shop\Models\Carts
     */
    public static function fetchForSession()
    {
        $cart = new static;

        $session_id = \Dsc\System::instance()->get('session')->id();
    
        $cart->load(array('session_id' => $session_id));
        $cart->session_id = $session_id;
    
        return $cart;
    }
    
    /**
     * Get the current user's cart
     *
     * @return \Shop\Models\Carts
     */
    public static function fetchForUser()
    {
        $cart = new static;
    
        $identity = \Dsc\System::instance()->get('auth')->getIdentity();
        $session_id = \Dsc\System::instance()->get('session')->id();
        
        if (!empty($identity->id))
        {
            $cart->load(array('user_id' => $identity->id));
            $cart->user_id = $identity->id;
            if (!empty($cart->id))
            {
                if ($cart->session_id != $session_id) 
                {
                    $cart->session_id = $session_id;
                    $cart->save();                    	
                }
            }
            
            // Is there a different session cart?  If so, merge them
            $session_cart = static::fetchForSession();
            // have we already done the merge?  if so, skip it
            $session_cart_merged = \Dsc\System::instance()->get('session')->get('shop.session_cart_merged');
            if (!empty($session_cart->id) && empty($session_cart_merged))
            {
                $cart->session_id = $session_id;
                $cart->merge( $session_cart->cast() );
                $session_cart->remove();
                \Dsc\System::instance()->get('session')->set('shop.session_cart_merged', true);
            }            
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

    /**
     * 
     * @param unknown $cartitem_hash
     * @param unknown $new_quantity
     * @return \Shop\Models\Carts
     */
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
     * Merges the data array into $this
     * giving quantities in the data array priority over quantities in $this
     *
     * @param unknown $data            
     */
    public function merge( $data )
    {
        if (!empty($data['items'])) 
        {
            foreach ($data['items'] as $data_key=>$data_item)
            {
            	// does it exist in $this?  if so, merge
                $exists = false;
                foreach ($this->items as $key=>$item)
                {
                    if ($item['hash'] == $data_item['hash'])
                    {
                        $exists = true;
                        $data_item['id'] = $item['id'];
                        $this->items[$key] = $data_item;
                        break;
                    }
                }
                
                // otherwise add it
                if (!$exists) {
                    $this->items[] = $data_item;
                }
            }
            
            $this->save();
        }
        
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
     * Does this cart require shipping?
     * 
     * 0    = return if shipping is not required for this cart and global setting = no
     * 1    = return if global setting = yes but the cart itself does not require shipping
     * true = return if cart itself requires shipping
     *
     * @return boolean
     */
    public function shipping_required()
    {
        $shipping_required = (int) \Shop\Models\Settings::fetch()->{'shipping.required'};
    
        if (empty($this->items)) {
            return $shipping_required;
        }
    
        foreach ($this->items as $item)
        {
            if (\Dsc\ArrayHelper::get($item, 'product.shipping.enabled')) 
            {
                $shipping_required = true;
            }
        }
    
        return $shipping_required;
    }
    
    /**
     * Gets the total weight of all items in the cart
     * 
     */
    public function weight()
    {
        $weight = 0;
        
        if (empty($this->items)) {
            return $weight;
        }
        
        foreach ($this->items as $item)
        {
            $weight += $item['weight'];
        }
        
        return $weight;
    }
    
    /**
     * Gets the total number of items in the cart
     */
    public function quantity()
    {
        $quantity = 0;
        
        if (empty($this->items)) {
            return $quantity;
        }
        
        foreach ($this->items as $item)
        {
            $quantity += (int) $item['quantity'];
        }
        
        return $quantity;
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
            $subtotal += static::calcItemSubtotal( $item );
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
     * Gets the total of all the applied discounts
     * 
     * @return number
     */
    public function discount()
    {
        $discount = 0;
        return $discount;
    }    
    
    /**
     * Gets the total,
     * incl. subtotal, discounts/coupons, gift certificates, 
     * shipping estimate (if possible) and tax (if possible).
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

    /**
     * 
     */
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