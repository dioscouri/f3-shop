<?php 
namespace Shop\Admin\Controllers;

class MenuItemQuickAdd extends \Admin\Controllers\BaseAuth 
{
	public function category($event)
	{
		$model = \Shop\Models\Collections::instance();
		$categories = $model->getList();
		\Base::instance()->set('collections', $collections );
		
		$view = \Dsc\System::instance()->get('theme');
		return $view->renderLayout('Shop/Admin/Views::quickadd/collection.php');
	}
	
	public function category($event)
	{
	    $model = \Shop\Models\Categories::instance();
	    $categories = $model->getList();
	    \Base::instance()->set('categories', $categories );
	
	    $view = \Dsc\System::instance()->get('theme');
	    return $view->renderLayout('Shop/Admin/Views::quickadd/category.php');
	}
	
	public function product($event)
	{
	    $model = \Shop\Models\Products::instance();
	    $tags = $model->getTags();
	    \Base::instance()->set('tags', $tags );
	
	    $view = \Dsc\System::instance()->get('theme');
	    return $view->renderLayout('Shop/Admin/Views::quickadd/tag.php');
	}
}