<?php
namespace Shop\Models;

class CustomerAddresses extends \Shop\Models\Addresses
{
    public $user_id = null;
    
    protected $__collection_name = 'shop.customers.addresses';
    protected $__type = 'shop.addresses';
    protected $__config = array(
        'default_sort' => array(
            'title' => 1
        )
    );
}