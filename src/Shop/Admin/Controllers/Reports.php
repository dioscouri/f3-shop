<?php 
namespace Shop\Admin\Controllers;

class Reports extends \Admin\Controllers\BaseAuth 
{
    public function index()
    {
        $grouped = (new \Shop\Models\Reports)->grouped();
        $this->app->set('grouped', $grouped);
        
        $this->app->set('meta.title', 'Reports | Shop');
        
        echo $this->theme->render('Shop/Admin/Views::reports/index.php');
        
    }
    
    public function read()
    {
        // load the report
        $slug = $this->inputfilter->clean( $this->app->get('PARAMS.slug'), 'cmd' );
        
        try {
            $item = (new \Shop\Models\Reports)->setState('filter.slug', $slug)->getItem();
            if (empty($item->id)) {
                throw new \Exception('Report not found');
            }
            
            $class_name = $item->namespace . '\Report';
            if (!class_exists($class_name)) {
                throw new \Exception('Class not found');
            }

            $class = new $class_name;
            if (!method_exists($class, 'index')) 
            {
                throw new \Exception('Class must have an index method');
            }
            
        } catch ( \Exception $e ) {

            \Dsc\System::instance()->addMessage( "Invalid Report", 'error');
            \Dsc\System::instance()->addMessage( $e->getMessage(), 'error');
            $this->app->reroute( '/shop/reports' );
            return;
        }
        
        $this->app->set('item', $item);
        
        $this->app->set('meta.title', $item->name . ' | Reports | Shop');
        
        // __construct bootstraps the report
        // so display it
        $class->index();
    }
}