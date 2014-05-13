<?php 
namespace Shop\MassUpdate\Models;

class Categories extends \MassUpdate\Service\Models\Model {
	
	/**
	 * This method returns an instance of model
	 */
	public function getModel(){
		static $model = null;
		
		if( $model == null ){
			$model = new \Shop\Models\Categories;
		}
		
		return $model;
	}
	
	/**
	 * This method gets list of attribute groups with operations
	 *
	 * @return	Array with attribute groups
	*/
	public function getOperationGroups(){
		if ($this->needInitialization())
		{
			$attr_cat = new \MassUpdate\Service\Models\AttributeGroup();
			$attr_cat->setAttributeCollection( 'ancestors.id' )
						->setParentModel( $this )
						->setAttributeTitle( "Parent Categories" )
						->addOperation( new \MassUpdate\Operations\Condition\Category(), array(
					'mode' => 1,
					'model' => new \Shop\Models\Categories
			) );
		
			$attr_created = new \MassUpdate\Service\Models\AttributeGroup();
			$attr_created->setAttributeCollection( 'metadata.created.time' )
						->setAttributeTitle( "Category Created" )
						->setParentModel( $this )
						->addOperation( new \MassUpdate\Operations\Condition\DateTimeCompare(), array(
					'mode' => 1
			) );
		
			$attr_title = new \MassUpdate\Service\Models\AttributeGroup();
			$attr_title->setAttributeCollection( 'title' )
						->setAttributeTitle( "Category Name" )
						->setParentModel( $this )
						->addOperation( new \MassUpdate\Operations\Update\ModifyTo() );
		
			$attr_last_modified = new \MassUpdate\Service\Models\AttributeGroup();
			$attr_last_modified->setAttributeCollection( 'metadata.last_modified' )
						->setAttributeTitle( "Last Modified" )
						->setParentModel( $this )
						->addOperation( new \MassUpdate\Operations\Update\ChangeDateTime() );
		
			$this->addAttributeGroup( $attr_title );
			$this->addAttributeGroup( $attr_cat );
			$this->addAttributeGroup( $attr_created );
			$this->addAttributeGroup( $attr_last_modified );
		}
		
		return $this->getAttributeGroups();
	}
}