<?php 
namespace Shop\Admin\Controllers;

class Group extends \Dsc\Controller
{
	public function getModel(){
        $model = new \Shop\Admin\Models\Group();
        return $model;
	}
	
	public function fetchTabGroups( $item, $isNew, $identifier = '' ) {
		
        $view = new \Dsc\Template;
		$view->item = $item;
        $prefab = $this->getModel()->prefab();
		if( !isset($view->item['shop'] ) ) {
			$view->item = $prefab->cast();
		} else {
			$view->item = array_merge( $prefab->cast(), $view->item->cast() );
		}
		return $view->renderLayout('Shop/Admin/Views::groups/tab_usergroups.php');
	}	
}