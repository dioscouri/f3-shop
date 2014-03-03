<?php 
namespace Shop\Models\Prefabs;

class UserGroupDetail extends \Dsc\Prefabs
{
    /**
     * Default document structure
     * @var array
     */
    protected $document = array(
        'shop'=> array(
        	'discount' => 0 // discount across shop
        )
    );
}