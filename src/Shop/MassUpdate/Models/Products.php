<?php 
namespace Shop\MassUpdate\Models;

class Products extends \MassUpdate\Service\Models\Model {
	
	/**
	 * This method returns an instance of model
	 */
	public function getModel(){
		static $model = null;
		
		if( $model == null ){
			$model = new \Shop\Models\Products;
		}
		
		return $model;
	}
	
	/**
	 * This method gets list of attribute groups with operations
	 *
	 * @return	Array with attribute groups
	*/
	public function getOperationGroups(){
		if( $this->needInitialization() ){
    		
    		$attr_keyword = new \MassUpdate\Service\Models\AttributeGroup;
    		$attr_keyword->setAttributeCollection('keyword')
    		->setAttributeTitle( "Keyword Search" )
    		->setParentModel( $this )
    		->addOperation( new \MassUpdate\Operations\Condition\Contains, array( "custom_label" => "Keyword", "filter" => "keyword") );
    		
    		
    		$attr_cat = new \MassUpdate\Service\Models\AttributeGroup;
    		$attr_cat->setAttributeCollection('categories.id')
    		->setAttributeTitle( "Product Category" )
    		->setParentModel( $this )
    		->addOperation( new \MassUpdate\Operations\Condition\Category, array( 'mode' => 1, 'model' => new \Shop\Models\Categories ) );
    		
    		$attr_cat_change = new \MassUpdate\Service\Models\AttributeGroup;
    		$attr_cat_change->setAttributeCollection('categories')
    		->setAttributeTitle( "Product Category" )
    		->setParentModel( $this )
    		->addOperation( new \MassUpdate\Operations\Update\ChangeCategory, array( 'allow_add' => true, 'model' => new \Shop\Models\Categories ) );
    		
    		$attr_title = new \MassUpdate\Service\Models\AttributeGroup;
    		$attr_title->setAttributeCollection('title')
    		->setParentModel( $this )
    		->setAttributeTitle( "Product Name" )
    		->addOperation( new \MassUpdate\Operations\Update\ChangeTo)
    		->addOperation( new \MassUpdate\Operations\Update\ModifyTo);
    		
    		$attr_price = new \MassUpdate\Service\Models\AttributeGroup;
    		$attr_price->setAttributeCollection('prices.default')
    		->setParentModel( $this )
    		->setAttributeTitle( "Product Price" )
    		->addOperation( new \MassUpdate\Operations\Update\ChangeTo)
    		->addOperation( new \MassUpdate\Operations\Update\IncreaseBy);
    		
    		$attr_published_state = new \MassUpdate\Service\Models\AttributeGroup;
    		$attr_published_state->setAttributeCollection('publication.status')
    		->setParentModel( $this )
    		->setAttributeTitle( "Publication status" )
    		->addOperation( new \Shop\MassUpdate\Operations\Condition\PublicationStatus)
    		->addOperation( new \Shop\MassUpdate\Operations\Update\PublicationStatus);
    		
    		$attr_shipping_required = new \MassUpdate\Service\Models\AttributeGroup;
    		$attr_shipping_required->setAttributeCollection('shipping.enabled')
    		->setParentModel( $this )
    		->setAttributeTitle( "Shipping required" )
    		->addOperation( new \MassUpdate\Operations\Condition\Boolean, array( "custom_label" => "Is Shipping required?" ))
    		->addOperation( new \MassUpdate\Operations\Update\Boolean, array( "custom_label" => "Is Shipping required?" ));

    		$attr_published_start = new \MassUpdate\Service\Models\AttributeGroup;
    		$attr_published_start->setAttributeCollection('publication.start')
    		->setAttributeTitle( "Published Start" )
    		->setParentModel( $this )
    		->addOperation( new \MassUpdate\Operations\Update\ChangeDateTime, 
    									array( "metastamp" => true, 
    											"mode" => 1,
    											'attribute_dt' => array( 
    													"date" => 'publication.start_date', 
    													'time' => 'publication.start_time' )
    										));
    		
    		$attr_creator = new \MassUpdate\Service\Models\AttributeGroup;
    		$attr_creator->setAttributeCollection('metadata.creator')
    		->setAttributeTitle( "Creator" )
    		->setParentModel( $this )
    		->addOperation( new \MassUpdate\Operations\Update\ChangeUser );
    		
    		$attr_creator_id = new \MassUpdate\Service\Models\AttributeGroup;
    		$attr_creator_id->setAttributeCollection('metadata.creator.id')
    		->setAttributeTitle( "Creator" )
    		->setParentModel( $this )
    		->addOperation( new \MassUpdate\Operations\Condition\IsUser );
    		
    		
    		$this->addAttributeGroup( $attr_keyword );
    		$this->addAttributeGroup( $attr_title );
    		$this->addAttributeGroup( $attr_cat );
    		$this->addAttributeGroup( $attr_cat_change );
    		$this->addAttributeGroup( $attr_published_start );
    		$this->addAttributeGroup( $attr_published_state );
    		$this->addAttributeGroup( $attr_creator );
    		$this->addAttributeGroup( $attr_creator_id );
    		$this->addAttributeGroup( $attr_shipping_required );
    	}    	 
    	return $this->getAttributeGroups();
	}
}