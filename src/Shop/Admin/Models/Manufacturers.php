<?php 
namespace Shop\Admin\Models;

class Manufacturers extends \Dsc\Models\Categories 
{
    protected $collection = 'shop.manufacturers';
    protected $type = 'shop.manufacturers';
    protected $default_ordering_direction = '1';
    protected $default_ordering_field = 'metadata.title';

}