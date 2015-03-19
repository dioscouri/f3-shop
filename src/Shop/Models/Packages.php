<?php
namespace Shop\Models;

class Packages extends \Dsc\Mongo\Collection implements \Shop\Models\Packer\Box
{

	protected $__collection_name = 'shop.packages';
   
	var $length = null;
	var $width = null;
	var $height = null;
	var $volume = null;
	var $weight_limit = null;
	var $box_cost = null;
	//all measurements are to the inside of the box, so we just aprox the outer size as 5 mil bigger
	var $outsideMargin = '5'; //mm
	/**
	 * Reference for box type (e.g. SKU or description)
	 * @return string
	 */
	public function getReference() {
		return (string) $this->title;
	}
	
	/**
	 * Outer width in mm
	 * @return int
	*/
	public function getOuterWidth() {
		return (int) $this->width + $outsideMargin;
	}
	
	/**
	 * Outer length in mm
	 * @return int
	*/
	public function getOuterLength() {
		return (int) $this->length + $outsideMargin;
	}
	
	/**
	 * Outer depth in mm
	 * @return int
	*/
	public function getOuterDepth() {
		return (int) $this->height + $outsideMargin;
	}
	
	/**
	 * Empty weight in g
	 * @return int
	*/
	public function getEmptyWeight() {
		return (int) 20;
	}
	
	/**
	 * Inner width in mm
	 * @return int
	*/
	public function getInnerWidth() {
		return (int) $this->width;
	}
	
	/**
	 * Inner length in mm
	 * @return int
	*/
	public function getInnerLength() {
		return (int) $this->length;
	}
	
	/**
	 * Inner depth in mm
	 * @return int
	*/
	public function getInnerDepth() {
		return (int) $this->height;
	}
	
	/**
	 * Total inner volume of packing in mm^3
	 * @return int
	*/
	public function getInnerVolume() {
		return (int) $this->volume;
	}
	
	/**
	 * Max weight the packaging can hold in g
	 * @return int
	*/
	public function getMaxWeight() {
		return (int) $this->weight_limit;
	}
	
	/**
	 * Used to create a box from a cart Item, making all the packages the same when submitting to shipping
	 * @return int
	 */
	public static function fromCartItem($cartItem) {
		$box = new static;
		$box->set('length',$cartItem->length * 25.4 );
		$box->set('width',$cartItem->width * 25.4);
		$box->set('height',$cartItem->height * 25.4);
		$box->set('weight_limit',$cartItem->weight*453.59237);
		$box->set('box_cost', '0.00');
		$box->set('title', 'Product Box');
		
		return $box;
	}
	
    
}