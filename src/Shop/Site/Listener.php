<?php 
namespace Shop\Site;

class Listener extends \Prefab 
{
	public function afterUserLogin( $event ) 
	{
	    \Dsc\System::addMessage( 'This is the Shop Listener and I am observing the afterUserLogin event for front-end logins' );
	}
}