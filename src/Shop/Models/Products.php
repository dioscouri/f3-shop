<?php 
namespace Shop\Models;

class Products extends \Dsc\Mongo\Collections\Content implements \MassUpdate\Service\Models\MassUpdateOperations{
	
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
            array_walk($this->variants, function(&$item, $key){
                if (empty($item['id'])) {
                    $item['id'] = (string) new \MongoId;
                }
                if (!empty($item['attributes']) && !is_array($item['attributes'])) {
                    $item['attributes'] = json_decode( $item['attributes'] );
                }
            });
        }
    
        unset($this->parent);
        unset($this->new_category_title);
    
        return parent::beforeValidate();
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
    
    public static function getVariants( $cast )
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
                    $ids[$id] = $option['value'];
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
            	'attributes' => $combos[$key],
                'titles' => $titles
            );
        }
        
        return $result;
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
}