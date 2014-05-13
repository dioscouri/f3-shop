<?php 
namespace Shop\MassUpdate\Models;

class Manufacturers extends \MassUpdate\Service\Models\Model {
	
	/**
	 * This method returns an instance of model
	 */
	public function getModel(){
		static $model = null;
		
		if( $model == null ){
			$model = new \Shop\Models\Manufacturers;
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
    		$attr_title = new \MassUpdate\Service\Models\AttributeGroup;
    		$attr_title->setAttributeCollection('title')
    		->setParentModel( $this )
    		->setAttributeTitle( "Manufacturer Name" )
    		->addOperation( new \MassUpdate\Operations\Condition\EqualsTo)
    		->addOperation( new \MassUpdate\Operations\Update\ChangeTo);
					
			$this->addAttributeGroup( $attr_title );
		}
		return $this->getAttributeGroups();
	}
}