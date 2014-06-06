<?php
namespace Shop\Models;

class Reports extends \Dsc\Mongo\Collections\Nodes
{
	public $namespace = null;          // required, unique. Fully Qualified
	public $slug = null;           // required, unique.
	
    public $name = null;           // human-readable      
    public $type = null;           // e.g. 'products', 'orders', 'customers', 'misc'
    public $icon = null;           // a font-awesome class name

    protected $__collection_name = 'shop.reports';
    protected $__type = 'misc';
    
    protected $__config = array(
        'default_sort' => array(
        	'type' => 1,
            'name' => 1,
        ) 
    );
    
    protected function fetchConditions()
    {
        parent::fetchConditions();
        
        $filter_namespace = $this->getState('filter.namespace');
        if (strlen($filter_namespace))
        {
            $this->setCondition('namespace', $filter_namespace);
        }
    	
        return $this;
    }

    public static function register( $namespace, array $options=array() )
    {
        // Add the report to the collection if it isn't already
        $report = (new static)->setState('filter.namespace', $namespace)->getItem();
        if (empty($report->id) || !empty($options['__update'])) 
        {
            try {
                
                $report->bind(array(
                    'namespace' => $namespace,
                ))->bind($options)->save();

                return $report;
            }
            catch (\Exception $e) {
                
            	return false;
            }

        }
        
        return true;
    }
    
    protected function beforeValidate()
    {
        if (empty($this->slug)) 
        {
            $this->slug = \Web::instance()->slug( $this->namespace );
        }
        
        // TODO Put this in beforeSave, to ensure that the slug is clean
        //$this->slug = \Web::instance()->slug( $this->slug );
        
        return parent::beforeValidate();
    }

    /**
     * 
     * @return Ambigous <multitype:multitype: , unknown>
     */
    public function grouped()
    {
        $grouped = array();
        
        if ($items = $this->getItems())
        {
            foreach ($items as $item)
            {
                if (empty($grouped[$item->type]))
                {
                    $grouped[$item->type] = array();
                }
        
                $grouped[$item->type][] = $item;
            }
        }
        
        return $grouped;
    }
    
}