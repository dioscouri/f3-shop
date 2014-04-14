<?php 
namespace Shop;

class MassUpdateGroup extends \MassUpdate\Service\Models\Group{
	
	public $title = 'Shop';
	
	/**
	 * Initialize list of models
	 * 
	 * @param	$mode	Mode of updater
	 */
	public function initialize($mode) {
		parent::initialize( $mode);
		$this->addModel( new \Shop\Models\Products );
		$this->addModel( new \Shop\Models\Manufacturers );
		$this->addModel( new \Shop\Models\Categories );
	}
}
?>