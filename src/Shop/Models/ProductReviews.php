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
    
    public static function hasUserReviewed( \Users\Models\Users $user, \Shop\Models\Products $product )
    {
        if (empty($user->id))
        {
            return false;
        }
                
        $has_reviewed = static::collection()->findOne(array(
            'product_id' => $product->id,
            'user_id' => $user->id,
        ));

        if (!$has_reviewed)
        {
            return false;
        }
        
        return new static($has_reviewed);
    }
    
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
                
        $settings = \Shop\Models\Settings::fetch();        
        switch ($settings->{'reviews.eligibile'}) 
        {
            case "identified":
                
                // has the user already reviewed it?
                $has_reviewed = static::collection()->count(array(
                    'product_id' => $product->id,
                    'user_id' => $user->id,
                ));
                
                if (!$has_reviewed)
                {
                    return true;
                }
                
                break;
            case "purchasers":
            default:
                
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
                
                break;
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
     * Get various data sets/values related to a product's reviews, 
     * including average rating, count, images, etc
     * 
     * @param \Shop\Models\Products $product
     * @return multitype:
     */
    public static function forProduct( \Shop\Models\Products $product, $return_type='paginated' )
    {
        $return = null;
        
        switch ($return_type) 
        {
            case "image_count":
            
                $return = (new static)->setState('filter.product_id', $product->id)
                ->setState('filter.published_today', true)
                ->setState('filter.publication_status', 'published')
                ->setState('filter.has_image', true)
                ->getCount();
            
                break;            
            case "images":
                
                $return = (new static)->setState('filter.product_id', $product->id)
                ->setState('filter.published_today', true)
                ->setState('filter.publication_status', 'published')
                ->setState('filter.has_image', true)
                ->getList();
                
                break;
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
        
        $filter_has_image = $this->getState('filter.has_image');
        if (!empty($filter_has_image))
        {
            $this->setCondition('images', array(
                '$not' => array(
                    '$size' => 0
                )
            ));
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
        
        $this->description = strip_tags( str_replace('\n', '<br>', $this->description), '<br><p>' );
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
    
    public static function queueEmailForOrder( \Shop\Models\Orders $order )
    {
        $settings = \Shop\Models\Settings::fetch();
    
        if (empty($settings->{'reviews.enabled'}))
        {
            return;
        }
        
        $days_from_now = $settings->{'reviews.email_days'};
        if (empty($days_from_now))
        {
            return;
        }
        
        $email = $order->user_email;
        
        // Schedule the email to be sent $days_from_now
        $days_from_now = abs($days_from_now);
        $time = time() + $days_from_now * 86400;
        $task = \Dsc\Queue::task('\Shop\Models\ProductReviews::sendEmailForOrder', array(
            (string) $order->id
        ), array(
            'title' => 'Request product reviews from ' . $email . ' for order ' . $order->id,
            'when' => $time,
            'email' => $email
        ));        
    }
    
    public static function sendEmailForOrder( $order_id )
    {
        $settings = \Shop\Models\Settings::fetch();
    
        if (empty($settings->{'reviews.enabled'}))
        {
            return;
        }
    
        $days_from_now = $settings->{'reviews.email_days'};
        if (empty($days_from_now))
        {
            return;
        }
    
        // load the order
        $order = (new \Shop\Models\Orders)->setState('filter.id', $order_id)->getItem();
        if (empty($order->id))
        {
            return;
        }
                
        // check which products from the order have not been reviewed.
        // If all have been reviewed, don't send the email.
        $product_ids = array();
        foreach ($order->items as $item) 
        {
            $key = (string) $item['product_id'];
            $product_ids[$key] = $item['product_id']; 
        }
        
        $products = array_values($product_ids);        
        $product_reviews = static::collection()->find(array(
            'product_id' => array(
                '$in' => $products
            ),
            'user_id' => $order->user_id,
        ));
        
        foreach ($product_reviews as $doc) 
        {
            $key = (string) $doc['product_id'];
            unset($product_ids[$key]);
        }
        
        // at this point, $product_ids should have the list of unreviewed products from this order
        if (empty($product_ids)) 
        {
            return;
        }
        
        // so get an array of actual products
        $products = array();
        foreach ($product_ids as $product_id) 
        {
            foreach ($order->items as $item)
            {
                if ($item['product_id'] == $product_id) 
                {
                    $products[] = $item;
                } 
            }
        }
        
        if (empty($products)) 
        {
            return;
        }
        
        // get the recipient's email and send the email
        $recipients = array(
            $order->user_email
        );
        
        if (empty($recipients))
        {
            return;
        }
        
        $subject = $settings->get('reviews.email_subject');
        if (empty($subject)) 
        {
            $subject = "Please review your recent purchases!";
        }
        
        $user = $order->user();
        
        \Base::instance()->set('user', $user);
        \Base::instance()->set('products', $products);
        
        $html = \Dsc\System::instance()->get('theme')->renderView('Shop/Views::emails_html/review_products.php');
        $text = \Dsc\System::instance()->get('theme')->renderView('Shop/Views::emails_text/review_products.php');
        
        foreach ($recipients as $recipient)
        {
            \Dsc\System::instance()->get('mailer')->send($recipient, $subject, array(
                $html,
                $text
            ));
        }
        
    }
}