<?php 
namespace Shop\Models;

class Products extends \Dsc\Mongo\Collections\Content
{
	public $product_type = null;
	
	public $categories = array();
    public $featured_image = array();
    public $images = array();       // array of f3-asset slugs

    public $template = null;        // product template, prefab set of custom fields
    public $manufacturer = array(); // _id and title of a shop.manufacturer, or treat this like tags?

    public $tracking = array(
        'model_number'=>null,
        'sku'=>null,
        'upc'=>null
    );
    
    public $visibility = array();
    
    public $quantities = array(
        'manual'=>null
    );
    
    public $prices = array(
        'default'=>null,
        'list'=>null,
        'special'=>array(       // array of \Shop\Prefabs\Price

        )
    );
    
    public $shipping = array(
        'enabled'=>true,
        'weight'=>null,
        'dimensions'=>array(
            'length'=>null,
            'width'=>null,
            'height'=>null
        ),
        'surcharge'=>null // an amount added to shipping total regardless of shipping method
    );
    
    public $taxes = array(
        'enabled'=>true,
        'class'=>null
    );
    
    public $attributes = array(); // an array of \Shop\Prefabs\Attribute records
    
    // all possible product variations based on the attributes above, each with their product override values
    public $variants = array();          // an array of \Shop\Prefabs\Variant objects cast as an array
    
    public $attributes_count = null;
    public $variants_count = null;
    public $inventory_count = null;
    
    public $policies = array(
        'track_inventory'=>true,
        'quantity_input'=>array(
            'product_detail'=>true,
            'cart'=>true,
            'default'=>1
        ),
        'quantity_restrictions'=>array(
            'enabled'=>false,
            'min'=>1,
            'max'=>10,
            'increment'=>1
        ),
        'variant_pricing'=>array(
            'enabled'=>false,
        ),
        'hide_price'=>false,
    );
    
    public $display = array(
    	'stickers' => array()
    );
    
    public $related_products = array();
    
    public $gm_product_category = null;
    
    protected $__collection_name = 'shop.products';
    protected $__type = 'shop.products';
    protected $__config = array(
        'default_sort' => array(
            'title' => 1
        ),
    );
    
    /**
     * Method to auto-populate the model state.
     *
     */
    public function populateState()
    {
        parent::populateState();
        
        $system = \Dsc\System::instance();
        
        if ($system->app->get('APP_NAME') == 'site') 
        {
            $input = $system->get('input');
            
            /**
             * Handle the sort_by value, which users use to sort the list of products
            */
            $sort_by = $input->get('sort_by', null, 'string');
            $this->handleSortBy($sort_by);            
        }
        
        return $this;
    }
    
    public function handleSortBy( $sort_by )
    {
        $system = \Dsc\System::instance();
                
        $default = null;
        $old_state = $system->getUserState($this->context() . '.sort_by');
        $cur_state = (!is_null($old_state)) ? $old_state : $default;
        if ($sort_by && $cur_state != $sort_by)
        {
            $pieces = explode('-', $sort_by);
        } else {
            $pieces = explode('-', $cur_state);
        }
        $sort_by = implode('-', $pieces);
        $this->setState('sort_by', $sort_by);
        $system->setUserState($this->context() . '.sort_by', $sort_by);
        
        switch($pieces[0])
        {
        	case "price":
        	    
        	    // Set which price field to use
        	    $price_field = 'prices.default';
        	    $user = \Dsc\System::instance()->get('auth')->getIdentity();
        	    $primaryGroup = \Shop\Models\Customers::primaryGroup( $user );
        	    if ($group_slug = $primaryGroup->{'slug'}) {
        	        if ($this->exists('prices.'.$group_slug)) {
        	            $price_field = 'prices.'.$group_slug;
        	        }
        	    }

        	    if (!empty($pieces[1]) && $pieces[1] == 'desc') {
        	        $dir = -1;
        	    }
        	    else {
        	        $dir = 1;
        	    }
        	    $this->setState('list.sort', array( $price_field => $dir ) );
        	    $this->setState('list.order', 'price');
        	    
        	    break;
        	case "title":
        	default:
        	    if (!empty($pieces[1]) && $pieces[1] == 'desc') {
        	        $dir = -1;
        	    }
        	    else {
        	        $dir = 1;
        	    }
        	    $this->setState('list.sort', array( 'title' => $dir ) );
        	    $this->setState('list.order', 'title');
        	    break;
        }
        
        return $this;
    }
    
    protected function fetchConditions()
    {
        if ($this->getState('is.search') === true) 
        {
            $this->setState('filter.publication_status', 'published');
            $this->setState('filter.published_today', true);
            $this->setState('filter.inventory_status', 'in_stock');
        }
                
        parent::fetchConditions();
    
        $this->setCondition('type', $this->__type );
        
        $filter_keyword = $this->getState('filter.keyword');
        if ($filter_keyword && is_string($filter_keyword))
        {
            $key =  new \MongoRegex('/'. $filter_keyword .'/i');
        
            $where = array();
        
            $regex = '/^[0-9a-z]{24}$/';
            if (preg_match($regex, (string) $filter_keyword))
            {
                $where[] = array('_id'=>new \MongoId((string) $filter_keyword));
            }
            $where[] = array('slug'=>$key);
            $where[] = array('title'=>$key);
            $where[] = array('copy'=>$key);
            $where[] = array('description'=>$key);
            $where[] = array('tracking.sku'=>$key);
            $where[] = array('tracking.model_number'=>$key);
        
            $this->setCondition('$or', $where);
        }
        
        $filter_status_stock = $this->getState('filter.inventory_status');
        if (strlen($filter_status_stock))
        {
            switch($filter_status_stock) {
            	case "low_stock":
            	    $this->setCondition('inventory_count', array('$lte' => 20));
            	    break;
            	case "no_stock":
            	    $this->setCondition('inventory_count', array('$lte' => 0));
            	    break;
            	case "in_stock":
            	    $this->setCondition('$and',
                	    array( '$or' =>  
                    	    array(
                                array('$and' => array(
                                    array('inventory_count' => array('$gte' => 1)),
                                    array('policies.track_inventory' => array( '$in' => array( '1', true ) ) ),
                                )),
                                array('policies.track_inventory' => array( '$in' => array( '0', false ) ) ),
                    	    )
                    	),
                    	'append'
                    );           	        
            	    break;
            }
        }
        
        $filter_category_slug = $this->getState('filter.category.slug');
        if (strlen($filter_category_slug))
        {
            $this->setCondition('categories.slug', $filter_category_slug );
        }
        
        $filter_category_id = $this->getState('filter.category.id');
        if (strlen($filter_category_id))
        {
            if ($filter_category_id == '__uncategorized') {
            	// where no categories are assigned
                $this->setCondition('categories', array('$size' => 0) );
            }
            else {
                $this->setCondition('categories.id', new \MongoId( (string) $filter_category_id ) );
            }            
        }
        
        $filter_price_default_min = $this->getState('filter.price.default.min');
        if (strlen($filter_price_default_min))
        {
            $this->setCondition('prices.default', array('$gte' => (float) $filter_price_default_min) );
        }
        
        $filter_price_default_max = $this->getState('filter.price.default.max');
        if (strlen($filter_price_default_max))
        {
            $this->setCondition('prices.default', array('$lte' => (float) $filter_price_default_max) );
        }
        
        $filter_tags = (array) $this->getState('filter.vtags');
        if (!empty($filter_tags))
        {
            $filter_tags = array_filter( array_values( $filter_tags ), function( $var ) {return !empty( trim($var) ); } );
        
            if (!empty($filter_tags)) {
                if( count( $filter_tags ) == 1 && $filter_tags[0] == '--' ) {
                    $this->setCondition('variants.tags', array( '$size' => 0 ) );
                } else {
                    $this->setCondition('variants.tags', array( '$in' => $filter_tags ) );
                }
                 
            }
        }        
        
        // standard tag filtering searches both product-level and variant-level tags
        $filter_tags = (array) $this->getState('filter.tags');
        if (!empty($filter_tags))
        {
            // unset whatever \Dsc\Mongo\Collections\Taggable set
            $this->unsetCondition( 'tags' );
            
            $filter_tags = array_filter( array_values( $filter_tags ), function( $var ) {return !empty( trim($var) ); } );
            if (!empty($filter_tags)) 
            {
                if (!$and = $this->getCondition('$and'))
                {
                    $and = array();
                }
                
                if( count( $filter_tags ) == 1 && $filter_tags[0] == '--' ) 
                {
                    $and[] = array(
                        '$and' => array(
                            array(
                                'tags' => array( '$size' => 0 )
                            ),
                            array(
                                'variants.tags' => array( '$size' => 0 )
                            )
                        )
                    );                    
                }
                 
                else 
                {
                    $and[] = array(
                        '$or' => array(
                            array(
                                'tags' => array( '$in' => $filter_tags )
                            ),
                            array(
                                'variants.tags' => array( '$in' => $filter_tags )
                            )
                        )
                    );
                    
                }
                
                $this->setCondition('$and', $and);                
            }
        }        
        
        // variant-only tag filter
        $filter_tags = (array) $this->getState('filter.vtags');
        if (!empty($filter_tags))
        {
            $filter_tags = array_filter( array_values( $filter_tags ), function( $var ) {return !empty( trim($var) ); } );
        
            if (!empty($filter_tags)) {
                if( count( $filter_tags ) == 1 && $filter_tags[0] == '--' ) {
                    $this->setCondition('variants.tags', array( '$size' => 0 ) );
                } else {
                    $this->setCondition('variants.tags', array( '$in' => $filter_tags ) );
                }
                 
            }
        }

        // product-level-only tag filter, filter.ptag
        $filter_tags = (array) $this->getState('filter.ptags');
        if (!empty($filter_tags))
        {
            $filter_tags = array_filter( array_values( $filter_tags ), function( $var ) {return !empty( trim($var) ); } );
        
            if (!empty($filter_tags)) {
                if( count( $filter_tags ) == 1 && $filter_tags[0] == '--' ) {
                    $this->setCondition('tags', array( '$size' => 0 ) );
                } else {
                    $this->setCondition('tags', array( '$in' => $filter_tags ) );
                }
                 
            }
        }        
    
        return $this;
    }

    protected function beforeValidate()
    {
        if (!empty($this->images))
        {
            $images = array();
            $current = $this->images;
            $this->images = array();
            
            foreach ($current as $image)
            {
                if (!empty($image['image'])) {
                    $images[] = array( 'image' => $image['image'] );
                }
            }
            
            $this->images = $images;
        }
                
        if (!empty($this->{'manufacturer.id'}))
        {
            $manufacturer = array();
            if ($item = (new \Shop\Models\Manufacturers)->setState('filter.id', $this->{'manufacturer.id'})->getItem())
            {
                $manufacturer = array(
                    'id' => $item->id,
                    'title' => $item->title,
                    'slug' => $item->slug
                );
            }
            $this->manufacturer = $manufacturer;
        } else {
            $this->manufacturer = array();
        }
                
        if (!empty($this->category_ids))
        {
            $category_ids = array_filter( $this->category_ids );
            unset($this->category_ids);
    
            $categories = array();
            if (empty($category_ids)) {
                $this->categories = $categories;
            }
            elseif ($list = (new \Shop\Models\Categories)->setState('select.fields', array('title', 'slug'))->setState('filter.ids', $category_ids)->getList())
            {
                foreach ($list as $list_item) {
                    $cat = array(
                        'id' => $list_item->id,
                        'title' => $list_item->title,
                        'slug' => $list_item->slug
                    );
                    $categories[] = $cat;
                }
            }
            $this->categories = $categories;
        }
        
        if (!empty($this->attributes) && is_array($this->attributes)) 
        {
            // Compress the attributes array to just the values, then sort them by sort order
            $this->attributes = array_filter( array_values($this->attributes) );
            usort($this->attributes, function($a, $b) {
                return $a['ordering'] - $b['ordering'];
            });
            array_walk($this->attributes, function(&$item, $key){
                if (empty($item['id'])) {
                    $item['id'] = (string) new \MongoId;
                }
                if ($item['ordering'] != ($key+1)) {
                    $item['ordering'] = $key+1;
                }
                 
                // then Loop through each attribute and do the same for each attribute's options
                $item['options'] = array_values($item['options']);
                usort($item['options'], function($a, $b) {
                    return $a['ordering'] - $b['ordering'];
                });
                array_walk($item['options'], function(&$item, $key){
                    if (empty($item['id'])) {
                        $item['id'] = (string) new \MongoId;
                    }
                    if ($item['ordering'] != ($key+1)) {
                        $item['ordering'] = $key+1;
                    }
                });
            });
        }        

        // related_products could be a CSV of MongoIds
        if( empty( $this->related_products) ){
        	if( !is_array( $this->related_products ) ) {
        		$this->related_products = array();
        	}
        } else {
        	if( !is_array( $this->related_products ) ) {
        		$this->related_products = trim( $this->related_products );
        		if (!empty( $this->related_products )) {
        			$this->related_products = \Base::instance()->split( (string) $this->related_products );
        		}
        		else
        		{
        			$this->related_products = array();
        		}
        	}
        }
         
         
        foreach ($this->related_products as $key=>$product_id)
        {
        	// don't allow self-relations.  it will make you go blind.  :-)
        	if ((string) $product_id == (string) $this->id)
        	{
        		unset($this->related_products[$key]);
        	}
        	else
        	{
        		$this->related_products[$key] = new \MongoId( (string) $product_id );
        	}
        }
        $this->related_products = array_values($this->related_products);
        sort($this->related_products);
        
        // whether related_products is empty or not, we have to compare it to its previous state
        // and make updates if they aren't the same
        $old_products = array();
        
        if( !empty( $this->id ) ){
        	$old_product = (new static)->load( array('_id' => new \MongoId( (string) $this->id ) ));
        	 
        	if (!empty($old_product->related_products) && is_array($old_product->related_products)) {
        		sort($old_product->related_products);
        	} else {
        		$old_product->related_products = array();
        	}
        	$old_products = $old_product->related_products;
        }
        $this->__old_products = $old_products;
        
        
        unset($this->parent);
        unset($this->new_category_title);
    
        return parent::beforeValidate();
    }
    
    protected function beforeSave()
    {
        if (!empty($this->{'display.stickers'}) && !is_array($this->{'display.stickers'}))
        {
            $this->{'display.stickers'} = trim($this->{'display.stickers'});
            if (!empty($this->{'display.stickers'})) {
                $this->{'display.stickers'} = \Base::instance()->split( (string) $this->{'display.stickers'} );
            }
        }
        elseif(empty($this->{'display.stickers'}) && !is_array($this->{'display.stickers'}))
        {
            $this->{'display.stickers'} = array();
        }
        
        $this->set( 'shipping.enabled', (bool) $this->get( 'shipping.enabled') );
        $this->set( 'taxes.enabled', (bool) $this->get( 'taxes.enabled') );
        
        $this->set( 'prices.default', (float) $this->get( 'prices.default') );
        $this->set( 'prices.list', (float) $this->get( 'prices.list') );
        if ($this->get( 'prices.wholesale')) {
            $this->set( 'prices.wholesale', (float) $this->get( 'prices.wholesale') );
        }
        
        $this->set( 'policies.track_inventory', (bool) $this->get( 'policies.track_inventory') );
        
        $this->attributes_count = count( $this->attributes );
        $this->variants_count = count( $this->variants );        
        $this->inventory_count = 0;
        array_walk($this->variants, function(&$item, $key) {
            $item['quantity'] = (int) $item['quantity'];
            $item['price'] = (float) $item['price'];
            if (empty($item['quantity']) && !empty($this->{'policies.track_inventory'})) {
            	$item['enabled'] = 0;
            }
            if (!empty($item['quantity'])) {
                $this->inventory_count += (int) $item['quantity'];
            }            
        });
        
        if (!empty($this->{'prices.special'}) && is_array($this->{'prices.special'}))
        {
            // Compress the array to just the values, then sort them by sort order
            $special_prices = array_filter( array_values($this->{'prices.special'}) );
            usort($special_prices, function($a, $b) {
                return $a['ordering'] - $b['ordering'];
            });
            array_walk($special_prices, function(&$item, $key){
                if ($item['ordering'] != ($key+1)) {
                    $item['ordering'] = $key+1;
                }
                
                if (!empty($item['start_date'])) {
                    $string = $item['start_date'];
                    if (!empty($item['start_time'])) {
                        $string .= ' ' . $item['start_time'];
                    }
                    $item['start'] = \Dsc\Mongo\Metastamp::getDate( trim( $string ) );
                } else {
                    $item['start'] = \Dsc\Mongo\Metastamp::getDate('now');
                }
                
                if (empty($item['end_date'])) {
                    unset($item['end']);
                }
                elseif (!empty($item['end_date'])) {
                    $string = $item['end_date'];
                    if (!empty($item['end_time'])) {
                        $string .= ' ' . $item['end_time'];
                    }
                    $item['end'] = \Dsc\Mongo\Metastamp::getDate( trim( $string ) );
                }
                                
            });            
            $this->{'prices.special'} = $special_prices;
        }
        
        if (!$this->variantsInStock()) 
        {
        	$this->{'publication.status'} = 'unpublished';
        }
        
        return parent::beforeSave();
    }
    
    protected function beforeCreate()
    {
        $this->createVariants();

        return parent::beforeCreate();
    }
    
    protected function afterSave(){
    	
    	// compare them, only acting if they're different
    	// the arrays need to be sorted for comparison, which is why we sort above
    	if (is_array($this->__old_products) && $this->related_products != $this->__old_products)
    	{
    		// we need two arrays:
    		// $new_relationships == the ones from $this->related_products that are NOT in $old_product->related_products
    		// $deleted_relationships == the ones from $old_product->related_products that are NOT in $this->related_products
    		$new_relationships = array_diff($this->related_products, $this->__old_products);
    		$deleted_relationships = array_diff($this->__old_products, $this->related_products);
    	
    		// remove all $deleted_relationships
    		if (!empty($deleted_relationships))
    		{
    			$this->collection()->update(array(
    					'_id' => array(
    							'$in' => array_values( $deleted_relationships )
    					),
    					'related_products' => new \MongoId((string) $this->id)
    			), array(
    					'$pull' => array(
    							'related_products' => new \MongoId((string) $this->id)
    					)
    			), array(
    					'multiple' => true
    			));
    		}
    	
    		// insert $new_relationships
    		if (!empty($new_relationships))
    		{
    			$this->collection()->update(array(
    					'_id' => array(
    							'$in' => array_values( $new_relationships )
    					)
    			), array(
    					'$push' => array(
    							'related_products' => new \MongoId((string) $this->id)
    					)
    			), array(
    					'multiple' => true
    			));
    		}
    	}
    	 
    	return parent::afterSave();
    }
    
    protected function createVariants()
    {
        if (!empty($this->variants) && is_array($this->variants))
        {
            $variants = $this->rebuildVariants();
            $prev = array_values( array_filter( $this->variants ) );
            array_walk($variants, function(&$item, $key) use($prev) {
                // find the previous version of this variant, if possible
                foreach ($prev as $p) {
                	if ($p['key'] == $key) {
                		$item = array_merge($item, $p);
                		break;
                	}
                }
            });
            unset($prev);
            
            $this->variants = \Dsc\ArrayHelper::sortArrays(array_values( $variants ), 'attribute_title');
        }
        
        if (empty($this->attributes))
        {
            // build the variants array for just the single variant
            $mongo_id = (string) new \MongoId;
        
            $variant = new \Shop\Models\Prefabs\Variant(array(
                'id' => $mongo_id,
                'key' => $mongo_id,
                'quantity' => (int) $this->{'quantities.manual'}
            ));
        
            $this->variants = array( $variant->cast() );
        }
    }
    
    protected function beforeUpdate()
    {
        // IMPORTANT: Variant IDs need to be preserved, SO,
        // if the attributes array is diff from before, Variants are no longer valid and can be recreated (IDs included)
        // but if the attributes array is the same, match the Variant with its pre-existing ID
        
        $prev_product = (new static)->setState('filter.id', $this->id)->getItem();
        
        // get the attribute ids for the prev_product and $this, sorted
        $prev_attributes = \Joomla\Utilities\ArrayHelper::getColumn($prev_product->attributes, 'id');
        sort( $prev_attributes );
        
        $this_attributes = \Joomla\Utilities\ArrayHelper::getColumn($this->attributes, 'id');
        sort( $this_attributes );

        if ($prev_attributes != $this_attributes) 
        {
        	// a complete variant rebuild is fine
        	$this->createVariants();
        }
        
        elseif (count($this->attributes) == 0)
        {
            // there's only one variant, the default product, so 
            // preserve variant IDs since the attribute set hasn't changed
            $edited = array();
            if (!empty($this->variants) && is_array($this->variants)) {
                $edited = array_values( $this->variants );
                $edited = $edited[0];
            }            

            $this->createVariants();             

            $variant = array_merge($prev_product->variants[0], $this->variants[0], $edited);
            $variant['id'] = $prev_product->variants[0]['id'];
            $variant['key'] = $variant['id'];
            if (!empty($variant['attributes']) && !is_array($variant['attributes'])) {
                $variant['attributes'] = json_decode( $variant['attributes'] );
            } elseif (empty($variant['attributes'])) {
                $variant['attributes'] = array();
            }
            if (!empty($variant['tags']) && !is_array($variant['tags']))
            {
                $variant['tags'] = trim($variant['tags']);
                if (!empty($variant['tags'])) {
                    $variant['tags'] = array_map(function($el){
                        return strtolower($el);
                    }, \Base::instance()->split( (string) $variant['tags'] ));
                }
            }
            elseif(empty($variant['tags']) && !is_array($variant['tags']))
            {
                $variant['tags'] = array();
            }            
                        
            $this->variants[0] = $variant;
            
            //$this->variants[0]['id'] = $prev_product->variants[0]['id'];
            //$this->variants[0]['key'] = $prev_product->variants[0]['key'];
        }
        
        else
        {
            // preserve variant IDs since the attribute set hasn't changed
            $this->createVariants();

            array_walk($this->variants, function(&$item, $key) use($prev_product) {
            	// if a variant with this attribute set existed, then preserve its ID and extended properties
            	if ($prev_variant = $prev_product->variantByKey($item['key'])) {
            		
            	    $variant = array_merge( $prev_variant, $item );
            		$variant['id'] = $prev_variant['id'];
            		$item = $variant;
            		
            	    //$item['id'] = $prev_variant['id'];
            	}
            	
            	if (!empty($item['attributes']) && !is_array($item['attributes'])) {
            	    $item['attributes'] = json_decode( $item['attributes'] );
            	}
            	
            	if (!empty($item['tags']) && !is_array($item['tags']))
            	{
            	    $item['tags'] = trim($item['tags']);
            	    if (!empty($item['tags'])) {
            	        $item['tags'] = array_map(function($el){
            	            return strtolower($el);
            	        }, \Base::instance()->split( (string) $item['tags'] ));
            	    }
            	}
            	elseif(empty($item['tags']) && !is_array($item['tags']))
            	{
            	    $item['tags'] = array();
            	}            	
            });
            
        }
        
        return parent::beforeUpdate();
    }
    
    /**
     * Fetches an item from the collection using set conditions
     *
     * @return Ambigous <NULL, \Dsc\Mongo\Collection>
     */
    protected function fetchItem()
    {
        $this->__cursor = $this->collection()->find($this->conditions(), $this->fields());
    
        if ($this->getParam('sort')) {
            $this->__cursor->sort($this->getParam('sort'));
        }
        $this->__cursor->limit(1);
        $this->__cursor->skip(0);
    
        $item = null;
        if ($this->__cursor->hasNext()) {
            $item = (new static( $this->__cursor->getNext() ))->convert();
        }
    
        return $item;
    }
    
    /**
     * Fetches multiple items from a collection using set conditions
     *
     * @return multitype:\Dsc\Mongo\Collection
     */
    protected function fetchItems()
    {
        $this->__cursor = $this->collection()->find($this->conditions(), $this->fields());
    
        if ($this->getParam('sort')) {
            $this->__cursor->sort($this->getParam('sort'));
        }
        if ($this->getParam('limit')) {
            $this->__cursor->limit($this->getParam('limit'));
        }
        if ($this->getParam('skip')) {
            $this->__cursor->skip($this->getParam('skip'));
        }
    
        $items = array();
        foreach ($this->__cursor as $doc) {
            $item = (new static( $doc ))->convert();
            $items[] = $item;
        }
    
        return $items;
    }
    
    public function convert()
    {
        if (empty($this->product_type)) {
        	return $this;
        }
        
        switch($this->product_type) 
        {
            case "giftcard":
        	case "giftcards":
        	    $model = new \Shop\Models\GiftCards($this);  
        	    break;
        	default:
        	    return $this;
        	    break;
        }
        
        return $model;
    }

    /**
     * Converts this to a search item, used in the search template when displaying each search result
     */
    public function toSearchItem()
    {
        $image = (!empty($this->{'featured_image.slug'})) ? './asset/thumb/' . $this->{'featured_image.slug'} : null;
        $settings = \Admin\Models\Settings::fetch();
        $is_kissmetrics = $settings->enabledIntegration( 'kissmetrics' );
        
        $js = '';
        // TODO: This is ugly fix, but for now it's OK ==> maybe if we move Kissmetrics up to f3-admin
        // and make tighter implementation, we can move this to view file
        if( $is_kissmetrics ){
        	$term = \Base::instance()->get('q');
        	$js = "\" onclick=\"javascript:_kmq.push(['record', 'Searched Product', {'Product Name' : '".$this->title."', 'SKU' : '".$this->{'tracking.sku'}."', 'Search Terms' : '".$term."' }])";
        }
        
        $item = new \Search\Models\Item(array(
        	'url' => './shop/product/' . $this->slug . $js,
            'title' => $this->title,
            'subtitle' => $this->{'tracking.sku'},
            'image' => $image . $js,
            'summary' => $this->description,
            'datetime' => null,
            'price' => $this->price(),
            'prices' => $this->{'prices'},
        ));
        
        return $item;
    }
    
    /**
     * Converts this to a search item, used in the search template when displaying each search result
     */
    public function toAdminSearchItem()
    {
        $image = (!empty($this->{'featured_image.slug'})) ? './asset/thumb/' . $this->{'featured_image.slug'} : null;
        $sku = ($this->{'tracking.sku'}) ? ' - ' . $this->{'tracking.sku'} : null; 
        $published_status = '<span class="label ' . $this->publishableStatusLabel() . '">' . $this->{'publication.status'} . '</span>';
        
        $item = new \Search\Models\Item(array(
            'url' => './admin/shop/product/edit/' . $this->id,
            'title' => $this->title . $sku,
            'subtitle' => \Shop\Models\Currency::format( $this->price() ),
            'image' => $image,
            'summary' => $this->getAbstract(),
            'datetime' => $published_status . ' ' . date('Y-m-d', $this->{'publication.start.time'} ),
            'price' => $this->price(),
            'prices' => $this->{'prices'},
        ));
    
        return $item;
    }    
    
    /**
     *
     * @param array $types
     * @return unknown
     */
    public static function distinctStickers($query=array())
    {
        if (empty($this)) {
            $model = new static();
        } else {
            $model = clone $this;
        }
    
        $distinct = $model->collection()->distinct("display.stickers", $query);
        $distinct = array_values( array_filter( $distinct ) );
    
        return $distinct;
    }
    
    /**
     * Helper method for creating select list options
     * 
     * @param array $query
     * @return multitype:multitype:string NULL
     */
    public static function forSelection(array $query=array())
    {
        if (empty($this)) {
            $model = new static();
        } else {
            $model = clone $this;
        }
        
        $cursor = $model->collection()->find($query, array("title"=>1) );
        $cursor->sort(array(
        	'title' => 1
        ));
        
        $result = array();
        foreach ($cursor as $doc) {
            $array = array(
            	'id' => (string) $doc['_id'],
                'text' => htmlspecialchars( $doc['title'], ENT_QUOTES ),
            );
            $result[] = $array;
        }
        
        return $result;
    }
    
    /**
     * Given a multi-dimensional array,
     * this will find all possible combinations of the array's elements
     *
     * Given:
     *
     * $traits = array
     * (
     *   array('Happy', 'Sad', 'Angry', 'Hopeful'),
     *   array('Outgoing', 'Introverted'),
     *   array('Tall', 'Short', 'Medium'),
     *   array('Handsome', 'Plain', 'Ugly')
     * );
     *
     * Returns:
     *
     * Array
     * (
     *      [0] => Happy,Outgoing,Tall,Handsome
     *      [1] => Happy,Outgoing,Tall,Plain
     *      [2] => Happy,Outgoing,Tall,Ugly
     *      [3] => Happy,Outgoing,Short,Handsome
     *      [4] => Happy,Outgoing,Short,Plain
     *      [5] => Happy,Outgoing,Short,Ugly
     *      etc
     * )
     *
     * @param string $string   The result string
     * @param array $traits    The multi-dimensional array of values
     * @param int $i           The current level
     * @param array $return    The final results stored here
     * @return array           An Array of CSVs
     */
    public static function getCombinations( $string, $traits, $i, &$return )
    {
        if ( $i >= count( $traits ) )
        {
            $return[trim($string)] = explode(".", $string); 
        }
        else
        {
            foreach ( $traits[$i] as $trait )
            {
                $new_string = !empty($string) ? $string.".".$trait : $trait;
                self::getCombinations( $new_string, $traits, $i + 1, $return );
            }
        }
        
        return $return;
    }
    
    /**
     * Returns an array of the product's variants, indexed by key
     * where key is an alphabetized, hyphenated string of each attribute's MongoId
     * 
     * @param unknown $cast
     * @return multitype:|multitype:multitype:string unknown multitype:Ambigous <string, unknown>
     */
    public static function buildVariants( $cast )
    {
        $result = array();
        
        if (is_object($cast) && method_exists($cast, 'cast')) {
            $cast = $cast->cast();
        }
        
        if (!is_array($cast)) {
            return $result;
        }
        
        if (empty($cast['attributes'])) 
        {
            // build the variants array for just the single variant
            $mongo_id = (string) new \MongoId;
            if (!empty($cast['variants'])) {
                $variants = array_values( $cast['variants'] );
                $mongo_id = !empty($variants[0]['id']) ? (string) $variants[0]['id'] : $mongo_id;
            }            
            
            $variant = new \Shop\Models\Prefabs\Variant(array(
                'id' => $mongo_id,
                'key' => $mongo_id,
                'attributes' => array(),
                'attribute_titles' => array(),
                'quantity' => (int) \Dsc\ArrayHelper::get( $cast, 'quantities.manual' )
            ));  
                      
            $result[] = $variant->cast();
            
            return $result;
        }
        
        $ids = array();
        $traits = array();
        foreach ($cast['attributes'] as $attribute) 
        {
            foreach ($attribute['options'] as $option) 
            {
                $id = (string) $option['id'];
                if (empty($ids[$id]))
                {
                    if (is_numeric($option['value'])) {
                        $ids[$id] = $attribute['title'] . ": " . $option['value'];
                    } else {
                        $ids[$id] = $option['value'];
                    }
                }
            }
            $traits[] = \Joomla\Utilities\ArrayHelper::getColumn($attribute['options'], 'id');
        }

        $combos = self::getCombinations( "", $traits, 0, $combos );
        foreach ( $combos as $key=>$values )
        {
            $titles = array();
            foreach ($values as $id) 
            {
                $titles[] = $ids[$id];
            }
            sort( $combos[$key] );
            
            $key_values = explode( '.', $key );
            sort( $key_values );
            $sorted_key = implode( '-', $key_values );
            /*
            $md5_key = md5($sorted_key);
            */
            $mongo_id = (string) new \MongoId;
            
            /*
            $result[$sorted_key] = array(
                'id' => $mongo_id,
                'key' => $sorted_key,
            	'attributes' => $combos[$key],
                'titles' => $titles
            );
            */
            $result[$sorted_key] = (new \Shop\Models\Prefabs\Variant(array(
                'id' => $mongo_id,
                'key' => $sorted_key,
            	'attributes' => $combos[$key],
                'attribute_titles' => $titles
            )))->cast();            
        }
        
        return $result;
    }
    
    /**
     * 
     */
    public function rebuildVariants()
    {
        $cast = $this->cast();
        return self::buildVariants($cast);
    }
    
    /**
     * User-aware price of the product,
     * accounting for user group, date, specials, etc.
     * Defaults to the product's default price.
     * 
     * @param \Users\Models\Users $user
     * @return unknown
     */
    public function price( $variant_id=null, \Users\Models\Users $user=null )
    {
        $price = $this->get('prices.default');
        
        if (empty($user)) { 
        	$identity = \Dsc\System::instance()->get('auth')->getIdentity();
        	if (!empty($identity->id)) {
        		$user = $identity;
        	}
        }
        
        // Get the product price for the user's primary group
        // primaryGroup defaults to the site-wide default user group
        $primaryGroup = \Shop\Models\Customers::primaryGroup( $user );
        if ($group_slug = $primaryGroup->{'slug'}) {
            if ($this->exists('prices.'.$group_slug)) {
            	$price = $this->get('prices.'.$group_slug);
            }
        }
        
        if (!empty($variant_id) && $this->{'policies.variant_pricing.enabled'} && $variant = $this->variant($variant_id)) 
        {
        	$price = $variant['price'];
        }
        
        // adjust price based on date ranges too
        $now = strtotime('now');
        $today = date('Y-m-d', $now);
        foreach ((array) $this->{'prices.special'} as $special_price) 
        {
        	if (empty($special_price['group_id']) || $special_price['group_id'] == (string) $primaryGroup->id) 
        	{
        		if ((!empty($special_price['start']['time']) && $special_price['start']['time'] <= $now) 
        		  && (empty($special_price['end']['time']) || $special_price['end']['time'] > $now )
                ) {
        			$price = $special_price['price'];
        			break;
        		}
        	}
        }
        
        return $price;
    }
    
    /**
     * Return the product price for a specific group
     * 
     * @param \Users\Models\Groups $group
     * @return unknown
     */
    public function priceForGroup( \Users\Models\Groups $group )
    {
        $price = $this->get('prices.default');
        
        if ($group_slug = $group->{'slug'}) {
            if ($this->exists('prices.'.$group_slug)) {
            	$price = $this->get('prices.'.$group_slug);
            }
        }
        
        return $price;
    }
    
    /**
     * Get all the images associated with a product
     * incl. featured image, related images, and variant images
     *  
     * @param unknown $cast
     * @return array
     */
    public function images()
    {
        $featured_image = array();
        if (!empty($this->featured_image['slug'])) {
            $featured_image = array( $this->featured_image['slug'] );
        }
         
        $variant_images = \Dsc\ArrayHelper::where($this->variantsInStock(), function($key, $variant) {
            if (!empty($variant['enabled']) && !empty($variant['quantity']) && !empty($variant['image'])) {
                return $variant['image'];
            }
        });
            
        
        $related_images = \Dsc\ArrayHelper::where($this->images, function($key, $ri) {
            if (!empty($ri['image'])) {
                return $ri['image'];
            }
        });        
            
        $images = array_unique( array_merge( array(), (array) $featured_image, (array) $variant_images, (array) $related_images ) );
        
        return $images;
    }
    
    public function image($tags=array())
    {
        $image = null;
        
        if (!empty($this->featured_image['slug'])) {
            $image = $this->featured_image['slug'];
        }        
        
        if (empty($tags)) 
        {
            return $image;
        }
        
        if (!is_array($tags)) 
        {
            $tags = array($tags);
        }        
        
        foreach ($this->variantsInStockWithImages(true) as $variant)
        {
            if (!empty($variant['tags'])) 
            {
                if (array_intersect($variant['tags'], $tags)) 
                {
                    $image = $variant['image'];
                    break;
                }
            }            
        }
        
        return $image;
    }
    
    /**
     * Get a variant using its id
     * 
     * @param string $id
     */
    public function variant($id)
    {
        $cast = $this->cast();
        if (empty($cast['variants'])) {
        	return false;
        }
        
        foreach ($cast['variants'] as $variant) 
        {
            if ($variant['id'] == (string) $id) {
            	return $variant;
            }
        }
        
        return false;
    }
    
    /**
     * Get a variant using its key,
     * which is an alphabetized, hyphenated string using each of its attributes's MongoId
     *
     * @param string $key
     */
    public function variantByKey($key)
    {
        $cast = $this->cast();
        if (empty($cast['variants'])) {
            return false;
        }
    
        foreach ($cast['variants'] as $variant)
        {
            if ($variant['key'] == (string) $key) {
                return $variant;
            }
        }
    
        return false;
    }
    
    /**
     * Get a variant using its attributes
     *
     * @param array $attributes
     */
    public function variantByAttributes(array $attributes)
    {
        $attributes = sort($attributes);
        
        $cast = $this->cast();
        if (empty($cast['variants'])) {
            return false;
        }
    
        foreach ($cast['variants'] as $variant)
        {
            if ($variant['attributes'] == $attributes) {
                return $variant;
            }
        }
    
        return false;
    }
    
    /**
     * 
     * @return boolean
     */
    public function variantsInStock()
    {
        if (empty($this->__variants_in_stock)) 
        {
            $this->__variants_in_stock = array_values( array_filter( $this->variants, function($el){
                 $return = true; 
                 if (empty($el['enabled'])) 
                 { 
                     $return = false; 
                 }
                 elseif (empty($el['quantity']) && $this->{'policies.track_inventory'}) {
                     $return = false;
                 }  
                 return $return;
            } ) );
        }
        
        return $this->__variants_in_stock;
    }
    
    /**
     *
     * @return boolean
     */
    public function variantsInStockWithImages($unique_images_only=false)
    {
        $done = array();
        
        if (empty($this->__variants_in_stock_with_images))
        {
            $this->__variants_in_stock_with_images = array();
            foreach ($this->variantsInStock() as $variant) 
            {
                if (!empty($variant['image'])) 
                {
                    if (!$unique_images_only || ($unique_images_only && !in_array($variant['image'], $done))) 
                    {
                        $this->__variants_in_stock_with_images[] = $variant;
                        $done[] = $variant['image'];
                    }
                }
            }
        }
    
        return $this->__variants_in_stock_with_images;
    }    
    
    /**
     * Determines if a product is available for purchase
     * by checking multiple things, including variant inventory
     * publication dates, etc
     * 
     */
    public function isAvailable() 
    {
        $return = true;
        
    	// stock:
    	if (empty($this->inventory_count)) 
    	{
    	    $return = false;
    	}
    	
    	// TODO Check publication dates
    	    
    	return $return;
    }
    
    /**
     * 
     * @param unknown $title
     * @return Ambigous <boolean, unknown>
     */
    public function findAttributeByTitle( $title ) 
    {
        $return = false;
        
    	foreach ($this->attributes as $attribute) 
    	{
    		$attribute_title = \Dsc\ArrayHelper::get($attribute, 'title');
    		if (strtolower($title) == strtolower($attribute_title)) 
    		{
    			$return = $attribute;
    			break; 
    		}
    	}
    	
    	return $return;
    }
    
    /**
     * Gets the products related to this one
     * 
     * @return array
     */
    public function relatedProducts()
    {
        $this->related_products = (array) $this->related_products;
        if (empty($this->related_products)) 
        {
        	return array();
        }
        
        $related_products = (new static)->setState('filter.ids', $this->related_products)
            ->setState('filter.published_today', true)
            ->setState('filter.publication_status', 'published')
    		->setState('filter.inventory_status', 'in_stock')
        ->getList();
        
        return $related_products;
    }
    
    /**
     * Gets the pages associated with this product
     *
     * @return array
     */
    public function relatedPages()
    {
        $this->{'pages.related'} = (array) $this->{'pages.related'};
        if (empty($this->{'pages.related'}))
        {
            return array();
        }
    
        $related = (new \Pages\Models\Pages)->setState('filter.ids', $this->{'pages.related'})
        ->setState('filter.published_today', true)
        ->setState('filter.publication_status', 'published')
        ->getList();
    
        return $related;
    }
    
    /**
     * Gets the posts associated with this product
     *
     * @return array
     */
    public function relatedPosts()
    {
        $this->{'blog.related'} = (array) $this->{'blog.related'};
        if (empty($this->{'blog.related'}))
        {
            return array();
        }
    
        $related = (new \Blog\Models\Posts)->setState('filter.ids', $this->{'blog.related'})
        ->setState('filter.published_today', true)
        ->setState('filter.publication_status', 'published')
        ->getList();
    
        return $related;
    }
    
    /**
     *
     * @param array $types
     * @return unknown
     */
    public static function distinctTags($query=array())
    {
        $model = new static();
        $distinct = $model->collection()->distinct("tags", $query);
        $vdistinct = $model->collection()->distinct("variants.tags", $query);
        
        $distinct = array_values( array_unique( array_filter( array_merge( $distinct, $vdistinct ) ) ) );
    
        return $distinct;
    }    
}