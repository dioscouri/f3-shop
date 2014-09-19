<?php 
namespace Shop\Models;

class ProductReviews extends \Dsc\Mongo\Collections\Nodes
{
    use \Dsc\Traits\Models\Publishable;
    use \Dsc\Traits\Models\Describable;
    
    protected $__collection_name = 'shop.product_reviews';
    protected $__type = 'product_review';

    protected $__config = array(
        'default_sort' => array(
            'metadata.created.time' => -1
        ),
    );
    
    protected $__product;
    protected $__user;
    
    //public $title; // string INDEX
    //public $slug; // string INDEX
    //public $description; // text
    public $rating = 1; // float, .5 increments, 1-5
    public $images = array(); // array of asset slugs
    
    public $product_id; // MongoId INDEX
    public $variant_id; // string INDEX
    public $user_id; // MongoId INDEX
    public $user_name; // display name for the user in this comment
    public $order_verified = false; // has it been confirmed that this user ordered this product?
    
    /**
     * Returns boolean true if user can review product
     * otherwise, returns string error message  
     * 
     * @param \Users\Models\Users $user
     * @param \Shop\Models\Products $product
     * @return string|boolean
     */
    public static function canUserReview( \Users\Models\Users $user, \Shop\Models\Products $product ) 
    {
        if (empty($user->id)) 
        {
            return false;
        }
        
        // has the user purchased this item?
        if (\Shop\Models\Customers::hasUserPurchasedProduct( $user, $product )) 
        {
            // has the user already reviewed it?
            $has_reviewed = static::collection()->count(array(
                'product_id' => $product->id,
                'user_id' => $user->id,
            ));
            
            if (!$has_reviewed) 
            {
                return true;
            }
        }
         
        return false;
    }
    
    /**
     * Add images to this review
     * 
     * @param unknown $images $_FILES array from POST
     * @return \Shop\Models\ProductReviews
     */
    public function addImages( $images=array() )
    {
        foreach ($images as $file_upload) 
        {
            try 
            {
                $asset = \Shop\Models\AssetsProductReviews::createFromUpload( $file_upload );
                $this->images[] = $asset->slug;
            }
            catch (\Exception $e) 
            {
                $this->setError( $e->getMessage() );
            }
        }
        
        return $this->store();
    }

    /**
     * 
     * @param \Shop\Models\Products $product
     * @return multitype:
     */
    public static function forProduct( \Shop\Models\Products $product, $return_type='paginated' )
    {
        $return = null;
        
        switch ($return_type) 
        {
            case "avg_rating":
            
                $conditions = (new static)->setState('filter.product_id', $product->id)
                ->setState('filter.published_today', true)
                ->setState('filter.publication_status', 'published')
                ->conditions();
                
                $agg = static::collection()->aggregate(array(
                    array(
                        '$match' => $conditions
                    ),
                    array(
                        '$group' => array(
                            '_id' => '$product_id',
                            'avgRating' => array( '$avg' => '$rating' )
                        )
                    ),
                ));

                if (!empty($agg['ok']) && !empty($agg['result']))
                {
                    $return = $agg['result'][0]['avgRating'];
                }                
            
                break;            
            case "count":
                
                $return = (new static)->setState('filter.product_id', $product->id)
                ->setState('filter.published_today', true)
                ->setState('filter.publication_status', 'published')
                ->getCount();
                
                break;
            default:
                
                $return = (new static)->setState('filter.product_id', $product->id)
                ->setState('filter.published_today', true)
                ->setState('filter.publication_status', 'published')
                ->setState('list.limit', 10)
                ->paginate();
                
                break;
        }
        
        return $return;
    }
    
    protected function fetchConditions()
    {
        parent::fetchConditions();
        
        $this->describableFetchConditions();
        $this->publishableFetchConditions();
        
        $filter_product_id = $this->getState('filter.product_id');
        if (!empty($filter_product_id))
        {
            $this->setCondition('product_id', new \MongoId( (string) $filter_product_id ) );
        }
        
        $filter_product_ids = $this->getState('filter.product_ids');
        if (!empty($filter_product_ids))
        {
            if (!is_array($filter_product_ids)) 
            {
                $filter_product_ids = array();
                foreach (explode(",", $this->getState('filter.product_ids')) as $product_id) 
                {
                    $filter_product_ids[] = new \MongoId( (string) $product_id );
                }
            }
            $this->setCondition('product_id', array( '$in' => $filter_product_ids ) );
        }
        
        $filter_user_id = $this->getState('filter.user_id');
        if (!empty($filter_user_id))
        {
            $this->setCondition('user_id', new \MongoId( (string) $filter_user_id ) );
        }
        
        return $this;
    }
    
    protected function beforeValidate()
    {
        $this->describableBeforeValidate();
        
        return parent::beforeValidate(); 
    }
    
    protected function beforeSave()
    {
        $this->publishableBeforeSave();
        
        $this->description = strip_tags( $this->description );
        $this->images = array_values( array_filter( $this->images ) );
        $this->rating = (float) $this->rating;
    
        return parent::beforeSave();
    }
    
    /**
     * Gets the associated user object
     *
     * @return unknown
     */
    public function user()
    {
        if (empty($this->__user)) 
        {
            $this->__user = (new \Users\Models\Users)->load(array('_id'=>$this->user_id));
        }
        
        return $this->__user;
    }
    
    /**
     * Gets the associated product object
     *
     * @return unknown
     */
    public function product()
    {
        if (empty($this->__product)) 
        {
            $this->__product = (new \Shop\Models\Products)->load(array('_id'=>$this->product_id));
        }
    
        return $this->__product;
    }
}