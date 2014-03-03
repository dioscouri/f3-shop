<?php 
namespace Shop\Models\Prefabs;

class Price extends \Dsc\Prefabs
{
    /**
     * Default document structure
     * @var array
     */
    protected $document = array(
        'price'=>null,
        'shoppergroup_id'=>null,
        'start_date'=>null, 
        'start_time'=>null,
        'end_date'=>null,
        'end_time'=>null,
        'quantity_min'=>null,
        'quantity_max'=>null
    );
}