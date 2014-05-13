<?php 
namespace Shop\MassUpdate\Operations\Condition;

/**
 * Checks, if a field is published or not
 * 
 */
class PublicationStatus extends \MassUpdate\Operations\Condition{

	/**
	 * This method returns where clause which will be later on passed to collection
	 * 
	 * @param 	$data		Data from request
	 * @param	$params		Arrays with possible additional params (for different modes of updater)
	 */
	public function getWhereClause($data, $params = array()){
		if( !$this->checkParams( $params ) ){
			return null;
		}
		$data = $this->attribute->getInputFilter()->clean($data, "alnum");
		$values = array( "published", "unpublished" );
		
		if( in_array( $data, $values ) === false ){ // not a possible value
			return null;
		}
		
		$res_clause = new \MassUpdate\Service\Models\Clause();
		$res_clause->{'key'} = $this->attribute->getAttributeCollection();		
		$res_clause->{'val'} = $data;
		return $res_clause;
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
		return "Published Status is";
	}

	/**
	 * This method returns nature of this operation - whether it uses mdoel's filter or generates its own where clause statement
	 * 
	 * @return True if it uses model's filter
	 */
	public function getNatureOfOperation(){
		return false;
	}
}
?>