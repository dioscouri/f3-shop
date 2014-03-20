<?php 
namespace Shop;

class MassUpdateGroup extends \MassUpdate\Service\Models\Group{
	
	public $title = 'Shop';
	public $slug = "shop";
	
	/**
	 * Initialize list of models
	 * 
	 * @param	$mode	Mode of updater
	 */
	public function initialize($mode) {
		$this->addModel( new \Shop\Models\Products, $mode );
		$this->addModel( new \Shop\Models\Manufacturers, $mode );
	}
}
?>