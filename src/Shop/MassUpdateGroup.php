<?php 
namespace Shop;

class MassUpdateGroup extends \MassUpdate\Service\Models\Group{
	
	public $title = 'Shop';
	public $slug = "shop";
	
	/**
	 * Initialize list of models
	 */
	public function initialize() {
		$this->addModel( new \Shop\Models\Manufacturers );
	}
}
?>