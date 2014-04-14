<?php 
namespace Shop\Models;

class Products extends \Dsc\Mongo\Collections\Content implements \MassUpdate\Service\Models\MassUpdateOperations
{
	use \MassUpdate\Service\Traits\Model;
	
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
        'enabled'=>false,
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
        )
    );
    
    public $display = array(
    	'stickers' => array()
    );
    
    protected $__collection_name = 'shop.products';
    protected $__type = 'shop.products';
    protected $__config = array(
        'default_sort' => array(
            'title' => 1
        ),
    );
    
    protected function fetchConditions()
    {
        parent::fetchConditions();
    
        $this->setCondition('type', $this->__type );
        
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
            	    $this->setCondition('inventory_count', array('$gte' => 1));
            	    break;
            }
        }
        
        $filter_category_slug = $this->getState('filter.category.slug');
        if (strlen($filter_category_slug))
        {
            $this->setCondition('categories.slug', $filter_category_slug );
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
            $this->attributes = array_values($this->attributes);
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
    
        unset($this->parent);
        unset($this->new_category_title);
    
        return parent::beforeValidate();
    }
    
    protected function beforeSave()
    {
        $this->attributes_count = count( $this->attributes );
        $this->variants_count = count( $this->variants );
        
        $this->inventory_count = 0;
        foreach ($this->variants as $variant) 
        {
            if (!empty($variant['quantity'])) {
                $this->inventory_count += (int) $variant['quantity'];
            }            
        }
        
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
        
        return parent::beforeSave();
    }
    
    protected function beforeCreate()
    {
        $this->createVariants();
        
        return parent::beforeCreate();
    }
    
    protected function createVariants()
    {
        if (!empty($this->variants) && is_array($this->variants))
        {
            $variants = $this->rebuildVariants();
        
            array_walk($this->variants, function(&$item, $key) use($variants) {
                if (empty($item['id'])) {
                    $item['id'] = (string) new \MongoId;
                }
                if (!empty($item['attributes']) && !is_array($item['attributes'])) {
                    $item['attributes'] = json_decode( $item['attributes'] );
                }
                // if the variant's key is empty, build it from the attributes
                if (empty($item['key'])) {
                    $item['key'] = implode("-", (array) $item['attributes']);
                }
                if (empty($variants[$item['key']])) {
                    unset($this->variants[$key]);
                } else {
                    // if the variant's attribute titles is empty, add it
                    if (empty($item['attribute_titles'])) {
                        $item['attribute_titles'] = $variants[$item['key']]['titles'];
                    }
                    // if the variant's attribute title is empty, build it automatically
                    if (empty($item['attribute_title'])) {
                        $item['attribute_title'] = implode("&nbsp;|&nbsp;", (array) $item['attribute_titles']);
                    }
                }
            });
        
            $this->variants = \Dsc\ArrayHelper::sortArrays(array_values( $this->variants ), 'attribute_title');
        }
        
        if (empty($this->attributes))
        {
            // build the variants array for just the single variant
            $mongo_id = (string) new \MongoId;
        
            $variant = new \Shop\Models\Prefabs\Variant(array(
                'id' => $mongo_id,
                'key' => $mongo_id
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
            $this->createVariants();             

            
            $variant = array_merge($prev_product->variants[0], $this->variants[0]);
            $variant['id'] = $prev_product->variants[0]['id'];
            $variant['key'] = !empty($prev_product->variants[0]['key']) ? $prev_product->variants[0]['key'] : $variant['key'];
            if (!empty($variant['attributes']) && !is_array($variant['attributes'])) {
                $variant['attributes'] = json_decode( $variant['attributes'] );
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
            });
            
        }
        
        return parent::beforeUpdate();
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
    
        $tags = $model->collection()->distinct("display.stickers", $query);
        $tags = array_values( array_filter( $tags ) );
    
        return $tags;
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
        $combos = array();
        
        if (is_object($cast) && method_exists($cast, 'cast')) {
            $cast = $cast->cast();
        }
        
        if (!is_array($cast) || empty($cast['attributes'])) {
            return $combos;
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
        
        $result = array();
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
            
            $result[$sorted_key] = array(
                'id' => $mongo_id,
                'key' => $sorted_key,
            	'attributes' => $combos[$key],
                'titles' => $titles
            );
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
     * 
     * @param unknown $groups
     * @return unknown
     */
    public function price( $groups=array() )
    {
        $price = $this->get('prices.default');
        
        // TODO get this product's price for the user's array of groups.
        // lowest price is given priority
        
        // TODO adjust price based on date ranges too
        
        return $price;
    }
    
    /**
     * 
     */
    public function priceForGroup( $group )
    {
    	
    }

    /**
     * This method gets list of attribute groups with operations
     *
     * @return	Array with attribute groups
     */
    public function getMassUpdateOperationGroups(){
    	if( $this->needInitializationMassUpdate() ){
    		
    		$attr_keyword = new \MassUpdate\Service\Models\AttributeGroup;
    		$attr_keyword->setAttributeCollection('keyword')
    		->setAttributeTitle( "Keyword Search" )
    		->setModel( $this )
    		->addOperation( new \MassUpdate\Operations\Condition\Contains, 'where', array( "custom_label" => "Keyword", "filter" => "keyword") );
    		
    		
    		$attr_cat = new \MassUpdate\Service\Models\AttributeGroup;
    		$attr_cat->setAttributeCollection('categories.id')
    		->setAttributeTitle( "Product Category" )
    		->setModel( new \Shop\Models\Categories )
    		->addOperation( new \MassUpdate\Operations\Condition\Category, 'where', array( 'mode' => 1 ) );
    		
    		$attr_cat_change = new \MassUpdate\Service\Models\AttributeGroup;
    		$attr_cat_change->setAttributeCollection('categories')
    		->setAttributeTitle( "Product Category" )
    		->setModel( new \Shop\Models\Categories )
    		->addOperation( new \MassUpdate\Operations\Update\ChangeCategory, 'update', array( 'allow_add' => true ) );
    		
    		$attr_title = new \MassUpdate\Service\Models\AttributeGroup;
    		$attr_title->setAttributeCollection('title')
    		->setModel( $this )
    		->setAttributeTitle( "Product Name" )
    		->addOperation( new \MassUpdate\Operations\Update\ChangeTo, 'update')
    		->addOperation( new \MassUpdate\Operations\Update\ModifyTo, 'update');
    		
    		$attr_price = new \MassUpdate\Service\Models\AttributeGroup;
    		$attr_price->setAttributeCollection('prices.default')
    		->setModel( $this )
    		->setAttributeTitle( "Product Price" )
    		->addOperation( new \MassUpdate\Operations\Update\ChangeTo, 'update')
    		->addOperation( new \MassUpdate\Operations\Update\IncreaseBy, 'update');
    		
    		$attr_published_state = new \MassUpdate\Service\Models\AttributeGroup;
    		$attr_published_state->setAttributeCollection('publication.status')
    		->setModel( $this )
    		->setAttributeTitle( "Publication status" )
    		->addOperation( new \Shop\Operations\Condition\PublicationStatus, 'where')
    		->addOperation( new \Shop\Operations\Update\PublicationStatus, 'update');
    		
    		$attr_shipping_required = new \MassUpdate\Service\Models\AttributeGroup;
    		$attr_shipping_required->setAttributeCollection('shipping.enabled')
    		->setModel( $this )
    		->setAttributeTitle( "Shipping required" )
    		->addOperation( new \MassUpdate\Operations\Condition\Boolean, 'where', array( "custom_label" => "Is Shipping required?" ))
    		->addOperation( new \MassUpdate\Operations\Update\Boolean, 'update', array( "custom_label" => "Is Shipping required?" ));

    		$attr_published_start = new \MassUpdate\Service\Models\AttributeGroup;
    		$attr_published_start->setAttributeCollection('publication.start')
    		->setAttributeTitle( "Published Start" )
    		->setModel( $this )
    		->addOperation( new \MassUpdate\Operations\Update\ChangeDateTime, 'update', 
    									array( "metastamp" => true, 
    											"mode" => 1,
    											'attribute_dt' => array( 
    													"date" => 'publication.start_date', 
    													'time' => 'publication.start_time' )
    										));
    		
    		$attr_creator = new \MassUpdate\Service\Models\AttributeGroup;
    		$attr_creator->setAttributeCollection('metadata.creator')
    		->setAttributeTitle( "Creator" )
    		->setModel( $this )
    		->addOperation( new \MassUpdate\Operations\Update\ChangeUser, 'update' );
    		
    		$attr_creator_id = new \MassUpdate\Service\Models\AttributeGroup;
    		$attr_creator_id->setAttributeCollection('metadata.creator.id')
    		->setAttributeTitle( "Creator" )
    		->setModel( $this )
    		->addOperation( new \MassUpdate\Operations\Condition\IsUser, 'where' );
    		
    		
    		$this->addAttributeGroupMassUpdate( $attr_keyword );
    		$this->addAttributeGroupMassUpdate( $attr_title );
    		$this->addAttributeGroupMassUpdate( $attr_cat );
    		$this->addAttributeGroupMassUpdate( $attr_cat_change );
    		$this->addAttributeGroupMassUpdate( $attr_published_start );
    		$this->addAttributeGroupMassUpdate( $attr_published_state );
    		$this->addAttributeGroupMassUpdate( $attr_creator );
    		$this->addAttributeGroupMassUpdate( $attr_creator_id );
    		$this->addAttributeGroupMassUpdate( $attr_shipping_required );
    	}    	 
    	return $this->getAttributeGroupsMassUpdate();
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
         
        $variant_images = \Dsc\ArrayHelper::where($this->variants, function($key, $variant) {
            if (!empty($variant['image'])) {
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
}