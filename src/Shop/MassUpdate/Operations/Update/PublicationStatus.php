<?php 
namespace Shop\MassUpdate\Operations\Update;

/**
 * Checks, if a field is published or not
 * 
 */
class PublicationStatus extends \MassUpdate\Operations\Update\ChangeTo{
	/**
	 * This method returns update clause which will be later on passed to collection
	 *
	 * @param 	$data		Data from request
	 * @param	$params		Arrays with possible additional params (for different modes of updater
	 *
	 * @return	Based on mode of updater, either update clause or updated document
	 */
	public function getUpdateClause($data, $params = array() ){
		$data = $this->attribute->getInputFilter()->clean($data, "alnum");
		$values = array( "published", "unpublished" );
		
		if( in_array( $data, $values ) === false ){ // not a possible value
			return null;
		}
		
		return parent::getUpdateClause( $data, $params );
	}

	/**
	 * This method returns string representation how the operation should be rendered in form
	 */
	public function getFormHtml(){
		$name = $this->attribute->getAttributeCollection();
		$html = '
				<select name="'.$this->getNameWithIdx().'" id="'.$this->getNameWithIdx().'" class="form-control">
					<option value="published">Published</option>
					<option value="unpublished">Unpublished</option>
				</select>
				';		
		
		return $html;
	}
	
	/**
	 * This method returns label for getFormHtml() element which should be used as a label for this
	 * operation in form
	 */
	public function getGenericLabel(){
		return "Set Published Status to";
	}
}
?>