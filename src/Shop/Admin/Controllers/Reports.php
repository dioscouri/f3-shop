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
            
            $class = $item->getClass();

            
        } catch ( \Exception $e ) {

            \Dsc\System::instance()->addMessage( "Invalid Report", 'error');
            \Dsc\System::instance()->addMessage( $e->getMessage(), 'error');
            $this->app->reroute( '/admin/shop/reports' );
            return;
        }
        
        $this->app->set('report', $item);
        
        $this->app->set('meta.title', $item->title . ' | Reports | Shop');
        
        // display the report
        $class->index();
    }
    
    protected function createClone()
    {
        // TODO make the slug == the parent's slug + a MongoID
    }
    
    protected function TODOafterRemove()
    {
        // TODO Also delete any reports that are clones of this one
    }
}