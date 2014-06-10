<?php 
namespace Shop\Site;

class Listener extends \Prefab 
{
	public function afterUserLogin( $event ) 
	{
	    \Dsc\System::instance()->get('session')->set('shop.check_campaigns', true);
	}
}