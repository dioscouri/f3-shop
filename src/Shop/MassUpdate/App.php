<?php

namespace Shop\MassUpdate;

class App extends \MassUpdate\Service\Models\App{

	protected $name = 'Shop';
	public $title = 'Shop';
	
	/**
	 * Initialize list of models
	 *
	 * @param	$mode	Mode of updater
	 * 
	 * @return	Whether the list was initialized or not (in case the app is not available)
	 */
	public function initialize($mode) {
		$result = parent::initialize($mode);
		
		if( $result ) {
			$this->addModel( new \Shop\MassUpdate\Models\Products );
			$this->addModel( new \Shop\MassUpdate\Models\Manufacturers );
			$this->addModel( new \Shop\MassUpdate\Models\Categories );
			return true;
		}
		return false;
	}	
}

$app = new \Shop\MassUpdate\App();