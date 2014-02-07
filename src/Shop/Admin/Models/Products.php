<?php 
namespace Shop\Admin\Models;

class Products extends \Dsc\Models\Content 
{
    protected $collection = 'shop.products';
    protected $type = 'shop.products';
    protected $default_ordering_direction = '1';
    protected $default_ordering_field = 'metadata.title';
    
    public function __construct($config=array())
    {
        parent::__construct($config);
        
        $this->filter_fields = $this->filter_fields + array(
            'publication.start_date'
        );
    }
    
    public function prefab( $source=array(), $options=array() ) 
    {
        $prefab = new \Shop\Prefabs\Product($source, $options);
        
        return $prefab;
    }
    
    protected function fetchFilters()
    {
        $this->filters = array();
    
        $filter_keyword = $this->getState('filter.keyword');
        if ($filter_keyword && is_string($filter_keyword))
        {
            $key =  new \MongoRegex('/'. $filter_keyword .'/i');
    
            $where = array();
            $where[] = array('metadata.title'=>$key);
            $where[] = array('details.copy'=>$key);
            $where[] = array('metadata.creator.name'=>$key);
    
            $this->filters['$or'] = $where;
        }
    
        $filter_id = $this->getState('filter.id');
        if (strlen($filter_id))
        {
            $this->filters['_id'] = new \MongoId((string) $filter_id);
        }
        
        $filter_copy_contains = $this->getState('filter.copy-contains');
        if (strlen($filter_copy_contains))
        {
            $key =  new \MongoRegex('/'. $filter_copy_contains .'/i');
            $this->filters['details.copy'] = $key;
        }
        
        $this->filters['metadata.type'] = $this->type;
    
        return $this->filters;
    }
    
    public function save( $values, $options=array(), $mapper=null )
    {
        if (empty($values['publication']['start'])) {
            $values['publication']['start'] = \Dsc\Mongo\Metastamp::getDate( $values['publication']['start_date'] . ' ' . $values['publication']['start_time'] );
        }
        
        if (empty($values['publication']['end']) && !empty($values['publication']['end_date'])) {
            $string = $values['publication']['end_date'];
            if (!empty($values['publication']['end_time'])) {
                $string .= ' ' . $values['publication']['end_time']; 
            }
            $values['publication']['end'] = \Dsc\Mongo\Metastamp::getDate( trim( $string ) );
        }
    
        // if no slug exists, generate it and make sure it's unique
        if (empty($values['metadata']['slug']))
        {
            $values['metadata']['slug'] = $this->generateSlug( $values, $mapper );
        }
        
        if (!empty($values['metadata']['tags']) && !is_array($values['metadata']['tags']))
        {
            $values['metadata']['tags'] = trim($values['metadata']['tags']);
            if (!empty($values['metadata']['tags'])) {
                $values['metadata']['tags'] = \Base::instance()->split( (string) $values['metadata']['tags'] );
            }
        }
        
        if (empty($values['metadata']['tags'])) {
            unset($values['metadata']['tags']);
        }

        // create an array of categories from the category_ids, if present
        if (isset($values['category_ids'])) 
        {
            $category_ids = $values['category_ids'];
            unset($values['category_ids']);
            
            $categories = array();
            $model = new \Blog\Admin\Models\Categories;
            if ($list = $model->setState('select.fields', array('title'))->setState('filter.ids', $category_ids)->getList()) {
                foreach ($list as $list_item) {
                    $cast = $list_item->cast();
                    $cat = array(
                        'id' => (string) $cast['_id'],
                        'title' => $cast['title']
                    );
                    unset($cast);
                    $categories[] = $cat;
                }
            }
            $values['metadata']['categories'] = $categories; 
        }
        
        if (!empty($values['attributes']) && is_array($values['attributes'])) {
            // Compress the attributes array to just the values, then sort them by sort order
            $values['attributes'] = array_values($values['attributes']);
            usort($values['attributes'], function($a, $b) {
                return $a['ordering'] - $b['ordering'];
            });
            array_walk($values['attributes'], function(&$item, $key){
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
        
        if (!empty($values['variants']) && is_array($values['variants'])) 
        {
            array_walk($values['variants'], function(&$item, $key){
                if (empty($item['id'])) {
                    $item['id'] = (string) new \MongoId;
                }                
            	if (!empty($item['attributes'])) {
            	    $item['attributes'] = json_decode( $item['attributes'] );
            	}
            });
        }
        
        unset($values['variants']['build']);
        unset($values['parent']);
        unset($values['new_category_title']);
    
        return parent::save( $values, $options, $mapper );
    }
    
    /**
     * An alias for the save command, used only for creating a new object
     *
     * @param array $values
     * @param array $options
     */
    public function create( $values, $options=array() )
    {
        $values = $this->prefab( $values, $options )->cast();

        return $this->save( $values, $options );
    }
    
    /**
     * An alias for the save command, used only for updating an existing object
     *
     * @param unknown_type $mapper
     * @param array $values
     * @param array $options
     */
    public function update( $mapper, $values, $options=array() )
    {
        if (empty($values['attributes'])) {
            $values['attributes'] = array();
        }
        if (empty($values['variants'])) {
            $values['variants'] = array();
        }
        
        $values = $this->prefab( $mapper->cast(), $options )->bind( $values )->cast();
        
        return $this->save( $values, $options, $mapper );
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
            
            /*
            $key_values = explode( '.', $key );
            sort( $key_values );
            $sorted_key = implode( '.', $key_values );
            $md5_key = md5($sorted_key);
            */
            $mongo_id = (string) new \MongoId;
            
            $result[] = array(
                'id' => $mongo_id,
            	'attributes' => $combos[$key],
                'titles' => $titles
            );
        }
        
        return $result;
    }
}