<?php 
namespace Shop\Models;

class Collections extends \Dsc\Mongo\Collections\Describable 
{
    public $categories = array();
    public $featured_image;
    
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
        
        // TODO Add that the product must be published
        // $conditions['publication.status'] = 'published';
        
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
        
        // allow event listeners to modify the query conditions
        $eventName = "ShopModelsCollections_getProductQueryConditions";
        $event = \Dsc\System::instance()->trigger( $eventName, array(
                        'conditions' => $conditions,
                        'collection' => $collection
        ) );
        
        $conditions = $event->getArgument('conditions');
        
        return $conditions;
        
    }
    
    protected function beforeValidate()
    {
        if (!empty($this->categories))
        {
            $category_ids = array();
            $categories = array();
            $current = (array) $this->categories;
            unset($this->categories);
            
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
            $this->categories = $categories;
        }

        return parent::beforeValidate();
    }
}