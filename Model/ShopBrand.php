<?php
/**
 * ShopBrand model
 *
 * @brief Add some documentation for ShopBrand model.
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

class ShopBrand extends ShopAppModel {
/**
 * @brief Validation rules
 *
 * @var array
 */
	public $validate = array();

/**
 * @brief behaviors that are attached
 * 
 * @var array
 */
	public $actsAs = array(
		'Filemanager.Upload' => array(
			'image' => array(
				'thumbnailSizes' => array(
					'large' => '1000l',
					'medium' => '600l',
					'small' => '300l',
					'thumb' => '75l'
				)
			)
		)
	);

/**
 * @brief How the default ordering on this model is done
 *
 * @access public
 * @var array
 */
	public $order = array();

/**
 * hasMany relations for this model
 *
 * @access public
 * @var array
 */
	public $hasMany = array(
		'ShopProduct' => array(
			'className' => 'ShopProduct',
			'foreignKey' => 'shop_brand_id',
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
			$this->alias . '.' . $this->displayField => 'asc'
		);

		$this->validate = array(
			'name' => array(
				'notEmpty' => array(
					'rule' => 'notEmpty',
					'message' => __d('shop', 'Please enter the brand name'),
					'required' => true
				),
				'isUnique' => array(
					'rule' => 'isUnique',
					'message' => __d('shop', 'This brand already exists')
				),
			),
			'active' => array(
				'boolean' => array(
					'rule' => 'boolean',
					'message' => __d('shop', 'Active should be boolean')
				)
			)
		);
	}
}
