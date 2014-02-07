<?php 
namespace Shop\Admin\Models;

class Assets extends \Dsc\Models\Assets 
{
    protected $type = 'shop.assets';
    
    protected function fetchFilters()
    {
        $this->filters = parent::fetchFilters();
    
        $this->filters['metadata.type'] = $this->type;
        
        return $this->filters;
    }
}