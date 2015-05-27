<?php
namespace Shop\Models;

class CustomerAddresses extends \Shop\Models\Address
{
	public $user_id = null;
	public $primary_billing = null;
	public $primary_shipping = null;

	protected $__collection_name = 'shop.addresses';
	protected $__type = 'shop.addresses';
	protected $__config = array(
		'default_sort' => array(
			'metadata.last_modified.time' => -1
		)
	);

	protected function fetchConditions()
	{
		parent::fetchConditions();

		$this->setCondition( 'type', $this->__type );

		$filter_user = $this->getState('filter.user');
		if (strlen($filter_user))
		{
			$this->setCondition('user_id', new \MongoId((string) $filter_user));
		}

		$filter_primary_shipping = $filter_primary_billing = null;

		$filter_primary_shipping = $this->getState('filter.primary_shipping');
		if (is_bool($filter_primary_shipping))
		{
			$this->setCondition('primary_shipping', $filter_primary_shipping);
		}

		$filter_primary_billing = $this->getState('filter.primary_billing');
		if (is_bool($filter_primary_billing))
		{
			$this->setCondition('primary_billing', $filter_primary_billing);
		}

		$filter_region = $this->getState('filter.region');
		if ($filter_region && is_string($filter_region))
		{
			$this->setCondition('region', $filter_region);
		}

		$filter_region = $this->getState('filter.region');
		if ($filter_region && is_string($filter_region))
		{
			$this->setCondition('region', $filter_region);
		}

		$filter_city = $this->getState('filter.city');
		if ($filter_city && is_string($filter_city))
		{
			$key = new \MongoRegex('/' . $filter_city . '/i');

			$where = array();
			$where[] = array(
					'city' => $key
			);

			$this->setCondition('$or', $where);
		}

		$filter_phone_number = $this->getState('filter.phone_number');
		if ($filter_phone_number && is_string($filter_phone_number))
		{
			$key = new \MongoRegex('/' . $filter_phone_number . '/i');

			$where = array();
			$where[] = array(
					'phone_number' => $key
			);

			$this->setCondition('$or', $where);
		}

		$filter_postal_code = $this->getState('filter.postal_code');
		if ($filter_postal_code && is_string($filter_postal_code))
		{
			$key = new \MongoRegex('/' . $filter_postal_code . '/i');

			$where = array();
			$where[] = array(
					'postal_code' => $key
			);

			$this->setCondition('$or', $where);
		}

		$filter_user_ids = $this->getState('filter.user_ids');
		if (!empty($filter_user_ids) && is_array($filter_user_ids))
		{
			$user_ids = array();
			foreach ($filter_user_ids as $user_id)
			{
				$user_ids[] = new \MongoId( (string) $user_id);
			}

			$this->setCondition('user_id', array('$in' => $user_ids) );
		}

		return $this;
	}

	protected function beforeValidate()
	{
		if (empty($this->user_id))
		{
			$identity = \Dsc\System::instance()->get( 'auth' )->getIdentity();
			if (!empty( $identity->id ))
			{
				$this->set('user_id', $identity->id);
			}
		}

		return parent::beforeValidate();
	}

	public function validate()
	{
		if (empty($this->user_id))
		{
			$this->setError('Addresses must have an associated customer');
		}

		return parent::validate();
	}

	/**
	 * Get the current user's addresses
	 *
	 * @return array \Shop\Models\CustomerAddresses
	 */
	public static function fetch()
	{
		$identity = \Dsc\System::instance()->get('auth')->getIdentity();
		if (empty($identity->id))
		{
			return array();
		}

		$items = (new static)->setState('filter.user', (string) $identity->id )->getItems();

		return $items;
	}

	/**
	 * Get the addresses for a specified user id
	 *
	 * @return array \Shop\Models\CustomerAddresses
	 */
	public static function fetchForId($id)
	{
		$items = (new static)->setState('filter.user', (string) $id )->getItems();

		return $items;
	}

	/**
	 *
	 * @return \Shop\Models\CustomerAddresses
	 */
	public function setAsPrimaryBilling()
	{
		// set primary_billing = null for all user's addresses
		$this->__last_operation = $this->collection()->update(
			array(
				'user_id'=>$this->user_id
			),
			array('$set' => array(
				'primary_billing'=>null
			)),
			array('multiple'=>true)
		);

		// set primary_billing = true for this address
		$this->update(array(
			'primary_billing'=>true
		), array(
			'overwrite'=>false
		));

		return $this;
	}

	public function setAsPrimaryShipping()
	{
		// set primary_shipping = null for all user's addresses
		$this->__last_operation = $this->collection()->update(
			array(
				'user_id'=>$this->user_id
			),
			array('$set' => array(
				'primary_shipping'=>null
			)),
			array('multiple'=>true)
		);

		// set primary_shipping = true for this address
		$this->update(array(
			'primary_shipping'=>true
		), array(
			'overwrite'=>false
		));

		return $this;
	}
}