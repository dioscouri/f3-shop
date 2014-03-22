<?php 
namespace Shop\Admin\Controllers;

class MenuItemQuickAdd extends \Admin\Controllers\BaseAuth 
{
	public function collection($event)
	{
		$view = \Dsc\System::instance()->get('theme');
		return $view->renderLayout('Shop/Admin/Views::quickadd/collection.php');
	}

	public function category($event)
	{
	    $view = \Dsc\System::instance()->get('theme');
	    return $view->renderLayout('Shop/Admin/Views::quickadd/category.php');
	}
	
	public function product($event)
	{
	    $model = new \Shop\Models\Products;
	    $tags = $model->getTags();
	    \Base::instance()->set('tags', $tags );
	
	    $view = \Dsc\System::instance()->get('theme');
	    return $view->renderLayout('Shop/Admin/Views::quickadd/tag.php');
	}
	
	public function cart($event)
	{
	    $view = \Dsc\System::instance()->get('theme');
	    return $view->renderLayout('Shop/Admin/Views::quickadd/cart.php');
	}
}