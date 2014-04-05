<?php 
namespace Shop\Site;

class Listener extends \Prefab 
{
	public function afterUserLogin( $event ) 
	{
	    // TODO Add any wishlist items in session to the actual wishlist in the DB
	}
}