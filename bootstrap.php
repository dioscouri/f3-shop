<?php 
class ShopBootstrap extends \Dsc\BaseBootstrap{
	protected $dir = __DIR__;
	protected $namespace = 'Shop';

	protected function runAdmin(){
		parent::runAdmin();
		try{
			$service = \Dsc\System::instance()->get('massupdate');
			if( !empty( $service ) ){
				$service->registerGroup( new \Shop\MassUpdateGroup );
			}
		} catch( \Exception $e){}
	}
}
$app = new ShopBootstrap();