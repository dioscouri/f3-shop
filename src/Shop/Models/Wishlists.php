<?php
namespace Shop\Models;

class Wishlists extends \Dsc\Mongo\Collections\Nodes
{
    public $user_id = null;
    public $session_id = null;
    public $items = array();        // array of \Shop\Models\Prefabs\CartItem objects, for easy copying to/from wishlist
    public $name = null;            // user-defined name for wishlist
    
    protected $__collection_name = 'shop.wishlists';
    protected $__type = 'shop.wishlists';
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
     * Get the current user's wishlist, either based on session_id (visitor) or user_id (logged-in)
     * 
     * @return \Shop\Models\Wishlists
     */
    public static function fetch()
    {
        $identity = \Dsc\System::instance()->get('auth')->getIdentity();
        if (empty($identity->id))
        {
            $wishlist = static::fetchForSession();
        }
        else
        {
            $wishlist = static::fetchForUser();
        }
        
        return $wishlist;
    }
    
    /**
     * Get the current session's wishlist
     *
     * @return \Shop\Models\Wishlists
     */
    public static function fetchForSession()
    {
        $wishlist = new static;

        $session_id = \Dsc\System::instance()->get('session')->id();
    
        $wishlist->load(array('session_id' => $session_id));
        $wishlist->session_id = $session_id;
    
        return $wishlist;
    }
    
    /**
     * Get the current user's wishlist
     *
     * @return \Shop\Models\Wishlists
     */
    public static function fetchForUser()
    {
        $wishlist = new static;
    
        $identity = \Dsc\System::instance()->get('auth')->getIdentity();
        $session_id = \Dsc\System::instance()->get('session')->id();
        
        if (!empty($identity->id))
        {
            $wishlist->load(array('user_id' => new \MongoId( (string) $identity->id ) ));
            $wishlist->user_id = $identity->id;
            
            $session_wishlist = static::fetchForSession();
            
            // if there was no user wishlist but there IS a session wishlist, just add the user_id to the session wishlist and save it
            if (empty($wishlist->id) && !empty($session_wishlist->id))
            {
                $wishlist = $session_wishlist;
                $wishlist->user_id = $identity->id;
                $wishlist->save();
            }
            
            // if there was a user wishlist and there is a session wishlist, merge them and delete the session wishlist
            // if we already did the merge, skip this
            $session_wishlist_merged = \Dsc\System::instance()->get('session')->get('shop.session_wishlist_merged');
            if (!empty($session_wishlist->id) && $session_wishlist->id != $wishlist->id && empty($session_wishlist_merged))
            {
                $wishlist->session_id = $session_id;
                $wishlist->merge( $session_wishlist->cast() );
                $session_wishlist->remove();
                \Dsc\System::instance()->get('session')->set('shop.session_wishlist_merged', true);
            }
            
            if (empty($wishlist->id)) {
                $wishlist->save();
            }
        }
    
        return $wishlist;
    }

    /**
     * Adds an item to the wishlist
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
        
        $wishlistitem = new \Shop\Models\Prefabs\CartItem(array(
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
        
        // Is the item already in the wishlist?
            // if so, inc quantity
            // otherwise add the wishlistitem
        $exists = false;
        foreach ($this->items as $key=>$item)
        {
            if ($item['hash'] == $wishlistitem->hash)
            {
                $exists = true;
                $wishlistitem->id = $item['id'];
                $wishlistitem->quantity = $wishlistitem->quantity + $item['quantity']; 
                $this->items[$key] = $wishlistitem->cast();
                
                break;
            }
        }
        
        if (!$exists) {
            $this->items[] = $wishlistitem->cast();
        }        
        
        return $this->save();
    }

    /**
     * 
     * @param unknown $wishlistitem_hash
     * @param unknown $new_quantity
     * @return \Shop\Models\Wishlists
     */
    public function updateItemQuantity( $wishlistitem_hash, $new_quantity )
    {
        $exists = false;
        foreach ($this->items as $key=>$item)
        {
            if ($item['hash'] == $wishlistitem_hash)
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
     * Removes an item from the wishlist
     * 
     * @param unknown $wishlistitem_hash
     * @return \Shop\Models\Wishlists
     */
    public function removeItem( $wishlistitem_hash )
    {
        $exists = false;
        foreach ($this->items as $key=>$item)
        {
            if ($item['hash'] == $wishlistitem_hash)
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
     * A diagnostic method. Updates/Removes products from stored wishlists.
     * Should be run each time a wishlist is viewed or when a checkout begins.
     * Checks that products are still available,
     * that the product definition in the wishlist is up to date,
     * etc. 
     * 
     * @return Array of messages from actions taken
     */
    public function validateProducts()
    {
        $return = array();
        
        if (empty($this->items)) {
            return $return;
        }
        
        $change = false;
        foreach ($this->items as $key=>$wishlistitem)
        {
            $variant_id = \Dsc\ArrayHelper::get($wishlistitem, 'variant_id');
            
            try {
                $product = (new \Shop\Models\Variants)->getById($variant_id);
            } catch (\Exception $e) {
                // remove item from wishlist and add message to $return
                $title = \Dsc\ArrayHelper::get($wishlistitem, 'product.title');
                $return[] = 'The item "'. $title .'" is invalid and has been removed from your wishlist.';
                unset($this->items[$key]);
                $change = true;
                continue;
            }

            // TODO If the product is not available, remove item from wishlist and add message to $return
            
            // update the wishlist's stored product definition
            $cast = $product->cast();
            if ($this->items[$key]['product'] != $cast) 
            {
                $change = true;
                $this->items[$key]['product'] = $cast;
            }
            
            // TODO Has the price changed?  If so, update the wishlist and add message to $return
            
        }
        
        if ($change) {
        	$this->save();
        }        
        
        return $return;
    }
    
    /**
     * Get a wishlist item using its hash
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