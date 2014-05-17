<?php 
namespace Shop\Admin\Controllers;

class Tags extends \Admin\Controllers\BaseAuth 
{
    public function index()
    {
        if ($tags = \Shop\Models\Products::getTags()) {
        	sort($tags);
        } else {
        	$tags = array();
        }
        
        $model = (new \Shop\Models\Tags)->populateState();
        $state = $model->getState();
        $needle = $model->getState('filter.keyword');
        
        if (!empty($needle)) {
            $tags = array_filter($tags, function ($tags) use($needle) {
                return (stripos($tags, $needle) !== false);
            });
        }
        
        if (!empty($tags)) {
        	$tags = array_map( function($el){
        		return new \Shop\Models\Tags(array('title'=>$el));
        	}, $tags );
        }
        
        \Base::instance()->set('state', $state );
        \Base::instance()->set('tags', $tags );

        $this->app->set('meta.title', 'Tags | Shop');
        
        $view = \Dsc\System::instance()->get('theme');
        echo $view->render('Shop/Admin/Views::tags/index.php');
    }
}