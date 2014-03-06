<?php 
namespace Shop\Admin\Controllers;

class MenuItemQuickAdd extends \Admin\Controllers\BaseAuth 
{
	public function category($event)
	{
<<<<<<< HEAD
		$model = \Shop\Models\Collections::instance();
=======
		$model = new \Shop\Models\Categories;
>>>>>>> 519123d5d7783f66d0ff05e7fd112f2e1ef14dd5
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
<<<<<<< HEAD
	    $model = \Shop\Models\Products::instance();
=======
	    $model = new \Shop\Models\Products;
>>>>>>> 519123d5d7783f66d0ff05e7fd112f2e1ef14dd5
	    $tags = $model->getTags();
	    \Base::instance()->set('tags', $tags );
	
	    $view = \Dsc\System::instance()->get('theme');
	    return $view->renderLayout('Shop/Admin/Views::quickadd/tag.php');
	}
}