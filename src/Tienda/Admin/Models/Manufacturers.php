<?php 
namespace Tienda\Admin\Models;

class Manufacturers extends \Dsc\Models\Content 
{
    protected $collection = 'tienda.manufacturers';
    protected $type = 'tienda.manufacturers';
    protected $default_ordering_direction = '1';
    protected $default_ordering_field = 'metadata.title';

}