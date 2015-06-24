<?php
namespace Shop\Models;

class Vendors extends \Dsc\Mongo\Collection
{
	public $notes = array();

	protected $__collection_name = 'shop.vendors';
	protected $__type = 'shop.vendors';
	protected $__config = array(
		'default_sort' => array(
			'name' => 1
		),
	);

	public static $__indexes = array(
			['name' => -1],
			['slug' => -1]
	);
	
	protected function fetchConditions()
	{
		parent::fetchConditions();

		$filter_keyword = $this->getState('filter.keyword');
		if ($filter_keyword && is_string($filter_keyword))
		{
			$key =  new \MongoRegex('/'. $filter_keyword .'/i');

			$where = array();

			$where[] = array('name' => $key);
			$where[] = array('email' => $key);
			$where[] = array('cite' => $key);
			$where[] = array('state' => $key);
			$where[] = array('phone_number' => $key);

			$this->setCondition('$or', $where);
		}

		return $this;
	}

	protected function beforeValidate()
	{
		parent::beforeValidate();
	}

	protected function beforeUpdate()
	{
		return parent::beforeUpdate();
	}

	protected function afterUpdate()
	{

	}

	/**
	 * Helper method for creating select list options
	 *
	 * @param array $query
	 * @return multitype:multitype:string NULL
	 */
	public static function forSelection(array $query=array())
	{
		if (empty($this)) {
			$model = new static();
		} else {
			$model = clone $this;
		}

		$cursor = $model->collection()->find($query, array("name" => 1));
		$cursor->sort(array('name' => 1));

		$result = array();
		foreach ($cursor as $doc) {
			$array = array(
					'id' => (string) $doc['_id'],
					'text' => htmlspecialchars( $doc['name'], ENT_QUOTES ),
			);
			$result[] = $array;
		}

		return $result;
	}

	public function addNote( $message, $save=false )
	{
		$identity = \Dsc\System::instance()->get('auth')->getIdentity();

		array_unshift( $this->notes, array(
		'created' => \Dsc\Mongo\Metastamp::getDate('now'),
		'created_by' => $identity->fullName(),
		'created_by_id' => $identity->id,
		'message' => $message
		) );

		if ($save) {
			return $this->save();
		}

		return $this;
	}
}