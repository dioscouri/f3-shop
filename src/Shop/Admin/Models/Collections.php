<?php 
namespace Shop\Admin\Models;

class Collections extends \Dsc\Models\Categories 
{
    protected $collection = 'shop.collections';
    protected $type = 'shop.collections';

    protected function fetchFilters()
    {
        $this->filters = parent::fetchFilters();
    
        $this->filters['type'] = $this->type;
    
        return $this->filters;
    }
}