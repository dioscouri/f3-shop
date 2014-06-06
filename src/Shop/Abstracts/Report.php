<?php 
namespace Shop\Abstracts;

abstract class Report extends \Dsc\Controller  
{
    public function __construct(array $config=array()) 
    {
        foreach ($config as $key=>$value) {
        	$this->$key = $value;
        }
    }
    
    abstract function index();
    
    /**
     * Gets the report slug
     *
     * @return string
     */
    protected function slug()
    {
        $slug = '';
    
        if (!empty($this->report)) 
        {
            return $this->report->slug;
        }
    
        $path = $this->app->hive()['PATH'];
        $path = str_replace('/admin/shop/reports/', '', $path);
        $pieces = explode( '/', $path);
        $slug = $pieces[0];
    
        return $slug;
    }
    
    /**
     * Bootstrap this Report, including:
     * 1. Register any custom routes that the report needs
     * 2. Add Custom view paths
     * 
     * @return \Shop\Abstracts\Report
     */
    public function bootstrap()
    {
        return $this;
    }
    
}