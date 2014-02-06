<?php 
namespace Shop\Admin\Controllers;

class MenuItemQuickAdd extends \Admin\Controllers\BaseAuth 
{
	public function category($event)
	{
		$model = \Shop\Admin\Models\Categories::instance();
		$categories = $model->getList();
		\Base::instance()->set('categories', $categories );
		
		$view = new \Dsc\Template;
		return $view->renderLayout('Shop/Admin/Views::quickadd/category.php');
	}
	
	public function product($event)
	{
	    $model = \Shop\Admin\Models\Products::instance();
	    $tags = $model->getTags();
	    \Base::instance()->set('tags', $tags );
	
	    $view = new \Dsc\Template;
	    return $view->renderLayout('Shop/Admin/Views::quickadd/tag.php');
	}
}