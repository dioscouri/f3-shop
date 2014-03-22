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
        )
    );
    
    public $taxes = array(
        'enabled'=>true,
        'class'=>null
    );
    
    public $attributes = array(); // an array of \Shop\Prefabs\Attribute records
    
    // all possible product variations based on the attributes above, each with their product override values
    public $variants = array();          // an array of \Shop\Prefabs\Variant
    
    public $attributes_count = null;
    public $variants_count = null;
    
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
            $category_ids = $this->category_ids;
            unset($this->category_ids);
    
            $categories = array();
            if ($list = (new \Shop\Models\Categories)->setState('select.fields', array('title', 'slug'))->setState('filter.ids', $category_ids)->getList())
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
                    $item['key'] = implode("-", $item['attributes']);
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
    
        unset($this->parent);
        unset($this->new_category_title);
    
        return parent::beforeValidate();
    }
    
    protected function beforeSave()
    {
        $this->attributes_count = count( $this->attributes );
        $this->variants_count = count( $this->variants );
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
    
    public function rebuildVariants()
    {
        $cast = $this->cast();
        return self::buildVariants($cast);
    }
    
    public function price( $groups=array() )
    {
        $price = $this->get('prices.default');
        
        // TODO get this product's price for the user's array of groups.
        // lowest price is given priority
        
        return $price;
    }

    /**
     * This method gets list of attribute groups with operations
     *
     * @return	Array with attribute groups
     */
    public function getMassUpdateOperationGroups(){
    	static $arr = null;
    	if( $arr == null ){
    		
    		$arr = array();
    		$attr_cat = new \MassUpdate\Service\Models\AttributeGroup;
    		$attr_cat->setAttributeCollection('categories')
    		->setAttributeTitle( "Product Category" )
    		->addOperation( new \Shop\Operations\Condition\ProductCategory, 'where' );
    		
    		$attr_title = new \MassUpdate\Service\Models\AttributeGroup;
    		$attr_title->setAttributeCollection('title')
    		->setAttributeTitle( "Product Name" )
    		->addOperation( new \MassUpdate\Operations\Update\ChangeTo, 'update');
    		
    		$arr []= $attr_title;
    		$arr []= $attr_cat;
    	}
    	 
    	return $arr;
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
     * @param unknown $id
     */
    public function variant($id)
    {
        $cast = $this->cast();
        if (empty($cast['variants'])) {
        	return false;
        }
        
        foreach ($cast['variants'] as $variant) 
        {
            if ($variant['id'] == $id) {
            	return $variant;
            }
        }
        
        return false;
    }
}