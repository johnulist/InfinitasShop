<?php
/**
 * ShopAddress
 *
 * @copyright Copyright (c) 2009 Carl Sutton (dogmatic69)
 *
 * @link http://infinitas-cms.org/Shop
 * @package	Shop.Model
 * @license	http://infinitas-cms.org/mit-license The MIT License
 * @since 0.9b1
 *
 * @author Carl Sutton <dogmatic69@infinitas-cms.org>
 */

class ShopAddress extends ShopAppModel {

/**
 * Custom find methods
 *
 * @var array
 */
	public $findMethods = array(
		'addresses' => true
	);

/**
 * belongsTo relations for this model
 *
 * @var array
 */
	public $belongsTo = array(
		'User' => array(
			'className' => 'Users.User',
			'foreignKey' => 'user_id',
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'counterScope' => array(
				'ShopAddress.active' => 1
			),
		)
	);

/**
 * Constructor
 *
 * @param string|integer $id string uuid or id
 * @param string $table the table that the model is for
 * @param string $ds the datasource being used
 *
 * @return void
 */
	public function __construct($id = false, $table = null, $ds = null) {
		parent::__construct($id, $table, $ds);

		$this->validate = array(
			'address_1' => array(
				'notEmpty' => array(
					'rule' => 'notEmpty',
					'message' => __d('shop', 'Address required'),
					'required' => true
				)
			)
		);
	}

/**
 * Find addresses for the specified user
 *
 * if no user condition has been supplied the current logged in user id will be used
 *
 * @param string $state before / after
 * @param array $query the find conditions
 * @param array $results the results of the find
 *
 * @return array
 */
	protected function _findAddresses($state, array $query, array $results = array()) {
		if ($state == 'before') {
			if (empty($query['conditions'][$this->alias . '.user_id'])) {
				$query['conditions'][$this->alias . '.user_id'] = $this->currentUserId();
			}

			$query['fields'] = $this->alias . '.*'; // hack
			return $query;
		}

		return $results;
	}

/**
 * Get a list of countries
 */
	public function countries() {
		return ClassRegistry::init('GeoLocation.GeoLocationCountry')->find('list');
	}

/**
 * Get a list of regions for the specified country
 *
 * @param string|integer $countryId the id of the country
 *
 * @return array
 */
	public function regions($countryId) {
		return ClassRegistry::init('GeoLocation.GeoLocationRegion')->find('regions', $countryId);
	}

}