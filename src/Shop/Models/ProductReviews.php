<?php 
namespace Shop\Models;

class ProductReviews extends \Dsc\Mongo\Collections\Nodes
{
    use \Dsc\Traits\Models\Publishable;
    use \Dsc\Traits\Models\Describable;
    
    protected $__collection_name = 'shop.product_reviews';
    protected $__type = 'product_review';    
    
    //public $title; // string INDEX
    //public $slug; // string INDEX
    //public $description; // text
    public $rating; // float, .5 increments, 1-5
    public $images = array(); // array of asset slugs
    
    public $product_id; // MongoId INDEX
    public $variant_id; // string INDEX
    public $user_id; // MongoId INDEX
    public $user_name; // display name for the user in this comment
    public $order_verified = false; // has it been confirmed that this user ordered this product?
    
    
}