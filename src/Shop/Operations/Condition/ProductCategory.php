<?php 
namespace Shop\Operations\Condition;

/**
 * Checks, if a product is in a category
 */
class ProductCategory extends \MassUpdate\Operations\Condition{

	/**
	 * This method returns where clause which will be later on passed to collection
	 * 
	 * @param 	$data		Data from request
	 * @param	$params		Arrays with possible additional params (for different modes of updater)
	 */
	public function getWhereClause($data, $params = array()){
		if( is_array( $data ) == false ){
			return array();
		}
		$ids = array();
		$empty_categories = false;
		foreach( $data as $id ){
			$id = $this->attribute->getInputFilter()->clean($id, "string");
			if( $id == 'empty' ){
				$empty_categories = true;
				continue;
			}
			if( strlen( trim( $id ) ) > 0 ){
				$ids []= new \MongoId( (string)$id );
			}
		}
		if( count( $ids ) > 0 || $empty_categories ){
			$res = array();
			if( $empty_categories ){ // we want to select products with no categories
				$res_clause = new \MassUpdate\Service\Models\Clause();
				$res_clause->{"idx"} = $this->idx;
				$res_clause->{'key'} = '$or';
				$res_clause->{'val'} = array(
						array(
								"categories" => array(
										'$size' => '0'
								)
						),
						array(
								"categories" => array(
										'$exists' => '0'
								)
						)
				);
				if( count( $ids ) > 0 ){ // also,we want to check ID with several selected categories
					$res_clause->{"val"} []= array( $this->attribute->getAttributeCollection().'.id' => array( '$in' => $ids) );
				}
				
				$res []= $res_clause;
			} else {
				// only with selected categories, if you can find any
				if( count( $ids ) > 0 ) {
					$res_clause = new \MassUpdate\Service\Models\Clause();
					$res_clause->{"idx"} = $this->idx;
					$res_clause->{'key'} = $this->attribute->getAttributeCollection().'.id';
					$res_clause->{'val'} = array( '$in' => $ids);
					$res []= $res_clause;
				}
			}
			return $res;
		}
		return null;
	}
	
	/**
	 * This method returns string representation how the operation should be rendered in form
	 */
	public function getFormHtml(){
		static $categories = null;
		if( $categories == null ){
			$categories = \Shop\Models\Categories::find();
		}
		$html = '<select name="'.$this->getNameWithIdx().'[]" id="'.$this->getNameWithIdx().'" multiple="true" size="5" style="min-weight: 150px;" class="form-control">';
		$html.= '<option value="empty">- No Category Assigned -</option>';
		foreach( $categories as $cat ){
			$opt = @str_repeat( "&ndash;", substr_count( @$cat->path, "/" ) - 1 ) . " " . $cat->title;
			$html.= '<option value="'.$cat->_id.'">'.$opt.'</option>';
		}
		$html.= '</select>';
		
		return $html;
	}
	
	/**
	 * This method returns label for getFormHtml() element which should be used as a label for this
	 * operation in form
	 */
	public function getLabel(){
		return "Product category is";
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