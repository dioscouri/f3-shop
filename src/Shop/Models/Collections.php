<?php
namespace Shop\Models;

class Collections extends \Dsc\Mongo\Collections\Describable
{
	use \Dsc\Traits\Models\ForSelection;
	
    public $products = array();

    public $categories = array();

    public $featured_image;

    public $publication_status = "published";

    public $inventory_status = "in_stock";
    
    public $sort_by = "title-asc";

    protected $__collection_name = 'shop.collections';

    protected $__type = 'shop.collections';

    /**
     * For the provided collection_id, create the query params to be used in \Shop\Models\Products
     * Trigger plugin events so they can modify the conditions.
     *
     * @param unknown $collection_id            
     */
    public static function getProductQueryConditions($collection_id)
    {
        $collection = (new static())->load(array(
            '_id' => new \MongoId((string) $collection_id)
        ));
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
                $ids[] = new \MongoId((string) $id);
            }
            
            $conditions = array_merge($conditions, array(
                '_id' => array(
                    '$in' => $ids
                )
            ));
        }
        
        // a range search
        if (!empty($collection->price_minimum) && !empty($collection->price_maximum))
        {
            $and = array(
                array(
                    'prices.default' => array(
                        '$lte' => (float) $collection->price_maximum
                    )
                ),
                array(
                    'prices.default' => array(
                        '$gte' => (float) $collection->price_minimum
                    )
                )
            );
            
            if (!empty($conditions['$and']))
            {
                $conditions['$and'] = array_merge($conditions['$and'], $and);
            }
            else
            {
                $conditions['$and'] = $and;
            }
        }
        // everything $gt a minimum
        elseif (!empty($collection->price_minimum) && empty($collection->price_maximum))
        {
            $conditions = array_merge($conditions, array(
                'prices.default' => array(
                    '$gte' => (float) $collection->price_minimum
                )
            ));
        }
        // everything $lt a maximum
        elseif (empty($collection->price_minimum) && !empty($collection->price_maximum))
        {
            $conditions = array_merge($conditions, array(
                'prices.default' => array(
                    '$lte' => (float) $collection->price_maximum
                )
            ));
        }
        
        if (!empty($collection->categories))
        {
            $cats = array();
            foreach ($collection->categories as $cat)
            {
                $cats[] = new \MongoId((string) $cat['id']);
            }
            
            $conditions = array_merge($conditions, array(
                'categories.id' => array(
                    '$in' => $cats
                )
            ));
        }
        
        if (!empty($collection->tags))
        {
            $tags = array();
            foreach ($collection->tags as $tag)
            {
                $tags[] = $tag;
            }
            
            if (!empty($conditions['tags']))
            {
                if (is_array($conditions['tags']))
                {
                    if (!empty($conditions['tags']['$in']))
                    {
                        foreach ($conditions['tags']['$in'] as $tag)
                        {
                            if (!in_array($tag, $tags))
                            {
                                $tags[] = $tag;
                            }
                        }
                    }
                }
                else
                {
                    $tags[] = $conditions['tags'];
                }
                
                $conditions['tags'] = array(
                    '$in' => $tags
                );
            }
            else
            {
                $conditions['tags'] = array(
                    '$in' => $tags
                );
            }
        }
        
        if (!empty($collection->publication_status))
        {
            $conditions = array_merge($conditions, array(
                'publication.status' => $collection->publication_status
            ));
            if ($collection->publication_status == 'published')
            {
                $and = array(
                    array(
                        '$or' => array(
                            array(
                                'publication.start.time' => null
                            ),
                            array(
                                'publication.start.time' => array(
                                    '$lte' => time()
                                )
                            )
                        )
                    ),
                    array(
                        '$or' => array(
                            array(
                                'publication.end.time' => null
                            ),
                            array(
                                'publication.end.time' => array(
                                    '$gt' => time()
                                )
                            )
                        )
                    )
                );

                if (!empty($conditions['$and']))
                {
                    $conditions['$and'] = array_merge($conditions['$and'], $and);
                }
                else
                {
                    $conditions['$and'] = $and;
                }
            }
        }
        
        if (!empty($collection->inventory_status))
        {
            switch ($collection->inventory_status)
            {
                case "low_stock":
                    $conditions = array_merge($conditions, array(
                        'inventory_count' => array(
                            '$lte' => 20
                        )
                    ));
                    break;
                case "no_stock":
                    $conditions = array_merge($conditions, array(
                        'inventory_count' => array(
                            '$lte' => 0
                        )
                    ));
                    break;
                case "in_stock":
                    $conditions = array_merge($conditions, array(
                        'inventory_count' => array(
                            '$gte' => 1
                        )
                    ));
                    break;
            }
        }
        
        // allow event listeners to modify the query conditions
        $eventName = "ShopModelsCollections_getProductQueryConditions";
        $event = \Dsc\System::instance()->trigger($eventName, array(
            'conditions' => $conditions,
            'collection' => $collection
        ));
        
        $conditions = $event->getArgument('conditions');
        
        return $conditions;
    }

    /**
     * Gets a count of all products returned by this collection's query
     *
     * @param string $id            
     * @return multitype: multitype:string
     */
    public static function productCount($id = null)
    {
        $result = 0;
        if (empty($id))
        {
            return $result;
        }
        
        $conditions = static::getProductQueryConditions($id);
        $result = (new \Shop\Models\Products())->collection()->count($conditions);
        
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
                if (is_array($category))
                {
                    $categories[] = $category;
                }
                elseif (!empty($category))
                {
                    $category_ids[] = $category;
                }
            }
            
            if (!empty($category_ids))
            {
                if ($list = (new \Shop\Models\Categories())->setState('select.fields', array(
                    'title',
                    'slug'
                ))
                    ->setState('filter.ids', $category_ids)
                    ->getList())
                {
                    foreach ($list as $list_item)
                    {
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

    protected function beforeCreate()
    {
        $this->slug = $this->generateSlug();
        
        return parent::beforeCreate();
    }

    protected function beforeSave()
    {
        if (!empty($this->products) && !is_array($this->products))
        {
            $this->products = trim($this->products);
            if (!empty($this->products))
            {
                $this->products = \Base::instance()->split((string) $this->products);
            }
        }
        elseif (empty($this->products) && !is_array($this->products))
        {
            $this->products = array();
        }
        
        $this->__update_products_ordering = false;
        if ($this->sort_by == 'ordering-asc') 
        {
            if (!empty($this->id)) 
            {
                // check what it was before
                $this->__old = (new static)->load( array('_id' => new \MongoId( (string) $this->id ) ));
                if ($this->__old->sort_by != $this->sort_by) 
                {
                    $this->__update_products_ordering = true;
                }
            }
            else 
            {
            	$this->__update_products_ordering = true;
            }
        }
        
        return parent::beforeSave();
    }
    
    protected function afterSave() 
    {
        parent::afterSave();
        
        if (!empty($this->__update_products_ordering)) 
        {
            // $to_update = find all products in this collection that dont have an ordering value for this collection
            // if there are some,
            // get a count of all products in this collection, set $start = count to push them to the end
            // loop though $to_update and set their ordering value = $start + $key
            
            $conditions = \Shop\Models\Collections::getProductQueryConditions($this->id);
            $conditions['collections.'. $this->id .'.ordering'] = null;
            $to_update = \Shop\Models\Products::collection()->distinct( '_id', $conditions);
            if (!empty($to_update)) 
            {
                $collection_id = (string) $this->id;
                unset($conditions['collections.'. $this->id .'.ordering']);
                $count = \Shop\Models\Products::collection()->count($conditions);
                foreach ($to_update as $key=>$product_id) 
                {
                    $ordering = $count + $key;
                    $product = (new \Shop\Models\Products)->setState('filter.id', (string) $product_id)->getItem();
                    if (!empty($product->id))
                    {
                        $product->update(array(
                            'collections.' . $collection_id . '.ordering' => (int) $ordering
                        ), array(
                            'overwrite' => false
                        ));
                    }                	
                }
            }
            
        }
    }
}