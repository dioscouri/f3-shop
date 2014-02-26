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
		
		$this->add( '/settings', 'GET', array(
							'controller' => 'Settings',
							'action' => 'display'
						));

		$this->add( '/settings', 'POST', array(
				'controller' => 'Settings',
				'action' => 'save'
		));

		$this->add( '/products', array('GET', 'POST'), array(
				'controller' => 'Products',
				'action' => 'display'
		));

		$this->add( '/products/page/@page', array('GET', 'POST'), array(
				'controller' => 'Products',
				'action' => 'display'
		));

		$this->add( '/products/delete', array('GET', 'POST'), array(
				'controller' => 'Products',
				'action' => 'delete'
		));

		$this->add( '/product/create', 'GET', array(
				'controller' => 'Product',
				'action' => 'create'
		));

		$this->add( '/product/add', 'POST', array(
				'controller' => 'Product',
				'action' => 'add'
		));

		$this->add( '/product/read/@id', 'GET', array(
				'controller' => 'Product',
				'action' => 'read'
		));

		$this->add( '/product/edit/@id', 'GET', array(
				'controller' => 'Product',
				'action' => 'edit'
		));

		$this->add( '/product/update/@id', 'POST', array(
				'controller' => 'Product',
				'action' => 'update'
		));

		$this->add( '/product/delete/@id', array('GET', 'DELETE'), array(
				'controller' => 'Product',
				'action' => 'delete'
		));

		$this->add( '/categories', 'GET', array(
				'controller' => 'Categories',
				'action' => 'getDatatable',
				'ajax' => true
		));

		$this->add( '/categories/all', 'GET', array(
				'controller' => 'Categories',
				'action' => 'getAll',
				'ajax' => true
		));

		$this->add( '/categories/checkboxes', array('GET', 'POST'), array(
				'controller' => 'Categories',
				'action' => 'getCheckboxes',
				'ajax' => true
		));

		$this->add( '/categories', array('GET', 'POST'), array(
				'controller' => 'Categories',
				'action' => 'display'
		));

		$this->add( '/categories/page/@page', array('GET', 'POST'), array(
				'controller' => 'Categories',
				'action' => 'display'
		));

		$this->add( '/categories/delete', array('GET', 'POST'), array(
				'controller' => 'Categories',
				'action' => 'delete'
		));

		$this->add( '/category/create', 'GET', array(
				'controller' => 'Category',
				'action' => 'create'
		));

		$this->add( '/category/add', 'POST', array(
				'controller' => 'Category',
				'action' => 'add'
		));

		$this->add( '/category/read/@id', 'GET', array(
				'controller' => 'Category',
				'action' => 'read'
		));

		$this->add( '/category/edit/@id', 'GET', array(
				'controller' => 'Category',
				'action' => 'edit'
		));

		$this->add( '/category/update/@id', 'POST', array(
				'controller' => 'Category',
				'action' => 'update'
		));

		$this->add( '/category/delete/@id', array('GET', 'DELETE'), array(
				'controller' => 'Category',
				'action' => 'delete'
		));

		$this->add( '/manufacturers', array('GET', 'POST'), array(
				'controller' => 'Manufacturers',
				'action' => 'display'
		));

		$this->add( '/manufacturers/page/@page', array('GET', 'POST'), array(
				'controller' => 'Manufacturers',
				'action' => 'display'
		));

		$this->add( '/manufacturers/delete', array('GET', 'POST'), array(
				'controller' => 'Manufacturers',
				'action' => 'delete'
		));

		$this->add( '/manufacturers', 'GET', array(
				'controller' => 'Manufacturers',
				'action' => 'getDatatable',
				'ajax' => true
		));

		$this->add( '/manufacturers/all', 'GET', array(
				'controller' => 'Manufacturers',
				'action' => 'getAll',
				'ajax' => true
		));

		$this->add( '/manufacturers/checkboxes', 'GET', array(
				'controller' => 'Manufacturers',
				'action' => 'getCheckboxes',
				'ajax' => true
		));

		$this->add( '/manufacturer', 'GET', array(
				'controller' => 'Manufacturer',
				'action' => 'create'
		));

		$this->add( '/manufacturer/add', 'POST', array(
				'controller' => 'Manufacturer',
				'action' => 'add'
		));

		$this->add( '/manufacturer/read/@id', 'GET', array(
				'controller' => 'Manufacturer',
				'action' => 'read'
		));

		$this->add( '/manufacturer/edit/@id', 'GET', array(
				'controller' => 'Manufacturer',
				'action' => 'edit'
		));

		$this->add( '/manufacturer/update/@id', 'POST', array(
				'controller' => 'Manufacturer',
				'action' => 'update'
		));

		$this->add( '/manufacturer/delete/@id', array( 'GET', 'DELETE'), array(
				'controller' => 'Manufacturer',
				'action' => 'delete'
		));

		$this->add( '/assets', array( 'GET', 'POST'), array(
				'controller' => 'Assets',
				'action' => 'display'
		));

		$this->add( '/assets/page/@page', array( 'GET', 'POST'), array(
				'controller' => 'Assets',
				'action' => 'display'
		));

		$this->add( '/assets/delete', array( 'GET', 'POST'), array(
				'controller' => 'Assets',
				'action' => 'delete'
		));

		$this->add( '/collections', 'GET', array(
				'controller' => 'Collections',
				'action' => 'getDatatable',
				'ajax' => true
		));

		$this->add( '/collections/all', 'GET', array(
				'controller' => 'Collections',
				'action' => 'getAll',
				'ajax' => true
		));

		$this->add( '/collections/checkboxes', array('GET', 'POST'), array(
				'controller' => 'Collections',
				'action' => 'getCheckboes',
				'ajax' => true
		));

		$this->add( '/collections', array('GET', 'POST'), array(
				'controller' => 'Collections',
				'action' => 'display'
		));

		$this->add( '/collections/page/@page', array('GET', 'POST'), array(
				'controller' => 'Collections',
				'action' => 'display'
		));

		$this->add( '/collections/delete', array('GET', 'POST'), array(
				'controller' => 'Collections',
				'action' => 'delete'
		));

		$this->add( '/collection/create', 'GET', array(
				'controller' => 'Collection',
				'action' => 'create'
		));

		$this->add( '/collection/add', 'POST', array(
				'controller' => 'Collection',
				'action' => 'add'
		));

		$this->add( '/collection/read/@id', 'GET', array(
				'controller' => 'Collection',
				'action' => 'read'
		));

		$this->add( '/collection/edit/@id', 'GET', array(
				'controller' => 'Collection',
				'action' => 'edit'
		));
		

		$this->add( '/collection/update/@id', 'GET', array(
				'controller' => 'Collection',
				'action' => 'update'
		));


		$this->add( '/collection/delete/@id', array('GET', 'DELETE'), array(
				'controller' => 'Collection',
				'action' => 'delete'
		));
	}
}