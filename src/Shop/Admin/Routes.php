<?php

namespace Shop\Admin;

/**
 * Group class is used to keep track of a group of routes with similar aspects (the same controller, the same f3-app and etc)
 */
class Routes extends \Dsc\Routes\Group{
	
	
	function __construct(){
		parent::__construct();
	}
	
	/**
	 * Initializes all routes for this group
	 * NOTE: This method should be overriden by every group
	 */
	public function initialize(){
		$this->setDefaults(
				array(
					'namespace' => '\Shop\Admin\Controllers',
					'url_prefix' => '/admin/shop'
				)
		);

		$this->addSettingsRoutes();
		$this->addCrudList( 'Products' );
		$this->addCrudItem( 'Product' );

		$this->addCrudList( 'Categories', array( 'databable_links' => true ) );
		$this->addCrudItem( 'Category' );

		$this->addCrudList( 'Manufacturers', array( 'databable_links' => true ) );
		$this->addCrudItem( 'Manufacturer' );
		$this->addCrudList( 'Assets' );

		$this->addCrudList( 'Collections', array( 'databable_links' => true ) );
		$this->addCrudItem( 'Collection' );
	}
}