<?php
/**
 * ShopOrderStatus model
 *
 * Add some documentation for ShopOrderStatus model.
 *
 * @copyright Copyright (c) 2009 Carl Sutton (dogmatic69)
 *
 * @link		  http://infinitas-cms.org/Shop
 * @package	   Shop.Model
 * @license	   http://infinitas-cms.org/mit-license The MIT License
 * @since 0.9b1
 *
 * @author dogmatic69
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 */

class ShopOrderStatus extends ShopAppModel {

/**
 * status for orders that are no longer valid
 *
 * @var integer
 */
	public static $statusCanceled = 0;

/**
 * status for orders that are pending
 *
 * @var integer
 */
	public static $statusPending = 5;

/**
 * status for orders that are being processed
 *
 * @var integer
 */
	public static $statusProcessing = 10;

/**
 * status for orders that have been processed
 *
 * @var integer
 */
	public static $statusProcessed = 15;

/**
 * status for orders that are completed
 *
 * @var integer
 */
	public static $statusCompleted = 20;

/**
 * status for orders that have been reversed
 *
 * @var integer
 */
	public static $statusReversed = 25;

	public $findMethods = array(
		'statuses' => true
	);

/**
 * hasMany relations for this model
 *
 * @access public
 * @var array
 */
	public $hasMany = array(
		'ShopOrderNote' => array(
			'className' => 'Shop.ShopOrderNote',
			'foreignKey' => 'shop_order_status_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
		'ShopOrder' => array(
			'className' => 'Shop.ShopOrder',
			'foreignKey' => 'shop_order_status_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		)
	);

/**
 * overload the construct method so that you can use translated validation
 * messages.
 *
 * @access public
 *
 * @param mixed $id string uuid or id
 * @param string $table the table that the model is for
 * @param string $ds the datasource being used
 *
 * @return void
 */
	public function __construct($id = false, $table = null, $ds = null) {
		parent::__construct($id, $table, $ds);

		$this->order = array(
			$this->alias . '.status' => 'asc',
			$this->alias . '.' . $this->displayField => 'asc'
		);

		$this->validate = array(
			'name' => array(
				'notEmpty' => array(
					'rule' => 'notEmpty',
					'message' => __d('shop', 'Please enter the name for this status'),
					'required' => true
				),
				'isUnique' => array(
					'rule' => 'isUnique',
					'message' => __d('shop', 'This status already exists'),
				)
			),
			'status' => array(
				'notEmpty' => array(
					'rule' => 'notEmpty',
					'message' => __d('shop', 'Please select the status')
				)
			)
		);
	}

/**
 * get a list of internal statuses
 *
 * @return array
 */
	public function statuses() {
		$statuses = array();
		foreach (get_class_vars('ShopOrderStatus') as $name => $var) {
			if (strstr($name, 'status') !== false) {
				$statuses[ShopOrderStatus::$$name] = __d('shop', substr_replace($name, '', 0, strlen('status')));
			}
		}

		return $statuses;
	}

	protected function _findStatuses($state, array $query, array $results = array()) {
		if ($state == 'before') {

			return $query;
		}

		$return = array();
		$statuses = $this->statuses();
		foreach($results as $result) {
			$field = $statuses[$result[$this->alias]['status']];
			$return[$field . ' '][$result[$this->alias][$this->primaryKey]] = $result[$this->alias][$this->displayField];
		}

		return $return;
	}
}
