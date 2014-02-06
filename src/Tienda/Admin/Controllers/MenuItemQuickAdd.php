<?php 
namespace Tienda\Admin\Controllers;

class MenuItemQuickAdd extends \Admin\Controllers\BaseAuth 
{
	public function category($event)
	{
		$model = \Tienda\Admin\Models\Categories::instance();
		$categories = $model->getList();
		\Base::instance()->set('categories', $categories );
		
		$view = new \Dsc\Template;
		return $view->renderLayout('Tienda/Admin/Views::quickadd/category.php');
	}
	
	public function product($event)
	{
	    $model = \Tienda\Admin\Models\Products::instance();
	    $tags = $model->getTags();
	    \Base::instance()->set('tags', $tags );
	
	    $view = new \Dsc\Template;
	    return $view->renderLayout('Tienda/Admin/Views::quickadd/tag.php');
	}
}