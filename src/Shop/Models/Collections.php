<?php 
namespace Shop\Models;

class Collections extends \Dsc\Mongo\Collections\Describable 
{
    public $products = array();
    public $categories = array();
    public $featured_image;
    public $publication_status = "published";
    public $inventory_status = "in_stock";
    
    protected $__collection_name = 'shop.collections';
    protected $__type = 'shop.collections';
    
    /**
     * For the provided collection_id, create the query params to be used in \Shop\Models\Products
     * Trigger plugin events so they can modify the conditions.
     * 
     * @param unknown $collection_id
     */
    public static function getProductQueryConditions( $collection_id )
    {
        $collection = (new static())->load(array('_id'=> new \MongoId( (string) $collection_id) ));         
        if (empty($collection->id)) 
        {
        	return array();
        }

        $conditions = array();
        
        if (!empty($collection->products))
        {
            $ids = array();
            foreach ($collection->products as $id)
            {
                $ids[] = new \MongoId( (string) $id);
            }
             
            $conditions = array_merge( $conditions, array( '_id' => array( '$in' => $ids ) ) );
        }
        
        if (!empty($collection->price_minimum))
        {
            $conditions = array_merge( $conditions, array( 'prices.default' => array('$gte' => (float) $collection->price_minimum) ) );
        }
        
        if (!empty($collection->price_maximum))
        {
            $conditions = array_merge( $conditions, array( 'prices.default' => array('$lte' => (float) $collection->price_maximum) ) );
        }
        
        if (!empty($collection->categories)) 
        {
            $cats = array();
        	foreach ($collection->categories as $cat) 
        	{
        	    $cats[] = new \MongoId( (string) $cat['id']);
        	}
        	
        	$conditions = array_merge( $conditions, array( 'categories.id' => array( '$in' => $cats ) ) );        	
        }
        
        if (!empty($collection->tags))
        {
            $tags = array();
            foreach ($collection->tags as $tag)
            {
                $tags[] = $tag;
            }
            
            $conditions = array_merge( $conditions, array( 'tags' => array( '$in' => $tags ) ) );
        }
        
        if (!empty($collection->publication_status)) 
        {
            $conditions = array_merge( $conditions, array( 'publication.status' => $collection->publication_status ) );
            if ($collection->publication_status == 'published') 
            {
                $and = array(
                                array('$or' => array(
                                                array('publication.start.time' => null),
                                                array('publication.start.time' => array( '$lte' => time() )  )
                                )),
                                array('$or' => array(
                                                array('publication.end.time' => null),
                                                array('publication.end.time' => array( '$gt' => time() )  )
                                ))
                );
                $conditions = array_merge( $conditions, array( '$and' => $and ) );
            }
        }
        
        if (!empty($collection->inventory_status))
        {
            switch($collection->inventory_status) {
            	case "low_stock":
            	    $conditions = array_merge( $conditions, array( 'inventory_count' => array( '$lte' => 20 ) ) );
            	    break;
            	case "no_stock":
            	    $conditions = array_merge( $conditions, array( 'inventory_count' => array( '$lte' => 0 ) ) );
            	    break;
            	case "in_stock":
            	    $conditions = array_merge( $conditions, array( 'inventory_count' => array( '$gte' => 1 ) ) );
            	    break;
            }            
        }
        
        // allow event listeners to modify the query conditions
        $eventName = "ShopModelsCollections_getProductQueryConditions";
        $event = \Dsc\System::instance()->trigger( $eventName, array(
                        'conditions' => $conditions,
                        'collection' => $collection
        ) );
        
        $conditions = $event->getArgument('conditions');

        return $conditions;
        
    }
    
    /**
     * Gets a count of all products returned by this collection's query
     *
     * @param string $id
     * @return multitype: multitype:string
     */
    public static function productCount( $id = null )
    {
        $result = 0;
        if (empty( $id ))
        {
            return $result;
        }
        
        $conditions = static::getProductQueryConditions($id);
        $result = (new \Shop\Models\Products())->collection()->count( $conditions );
    
        return $result;
    }
    
    protected function beforeValidate()
    {

        if (!empty($this->categories))
        {
            $category_ids = array();
            $categories = array();
            $current = (array) $this->categories;
            $this->set('categories', array());
            
            // convert each into an array of values if they aren't already
            foreach ($current as $category) 
            {
            	if (is_array($category)) {
            		$categories[] = $category;
            	} elseif (!empty($category)) {
            		$category_ids[] = $category;
            	}
            }

            if (!empty($category_ids)) 
            {
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
            }
           
            $this->set('categories', $categories);
        }

        return parent::beforeValidate();
    }
    
    protected function beforeSave()
    {
        if (!empty($this->products) && !is_array($this->products))
        {
            $this->products = trim($this->products);
            if (!empty($this->products)) {
                $this->products = \Base::instance()->split( (string) $this->products );
            }
        }
        elseif(empty($this->products) && !is_array($this->products))
        {
            $this->products = array();
        }

        return parent::beforeSave();
    }
}