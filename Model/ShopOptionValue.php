<?php
/**
 * ShopOptionValue Model
 *
 * @property ShopOption $ShopOption
 * @property ShopPrice $ShopPrice
 * @property ShopProductsOptionValueIgnore $ShopProductsOptionValueIgnore
 * @property ShopProductsOptionValueOverride $ShopProductsOptionValueOverride
 */
class ShopOptionValue extends ShopAppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'name';

	public $findMethods = array(
		'values' => true
	);

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'ShopOption' => array(
			'className' => 'Shop.ShopOption',
			'foreignKey' => 'shop_option_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

	public $hasOne = array(
		'ShopPrice' => array(
			'className' => 'Shop.ShopPrice',
			'foreignKey' => 'foreign_key',
			'conditions' => array(
				'ShopPrice.model' => 'Shop.ShopOptionValue'
			),
			'fields' => '',
			'order' => ''
		)
	);

	public $hasMany = array(
		'ShopProductsOptionValueIgnore' => array(
			'className' => 'Shop.ShopProductsOptionValueIgnore',
			'foreignKey' => 'shop_option_value_id',
			'dependent' => true,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
		'ShopProductsOptionValueOverride' => array(
			'className' => 'Shop.ShopProductsOptionValueOverride',
			'foreignKey' => 'shop_option_value_id',
			'dependent' => true,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
	);

/**
 * @brief get option values for multiple options
 *
 * @param string $state
 * @param array $query
 * @param array $results
 *
 * @return array
 *
 * @throws InvalidArgumentException
 */
	protected function _findValues($state, array $query, array $results = array()) {
		if($state == 'before') {
			if(empty($query['shop_option_id'])) {
				throw new InvalidArgumentException('No option has been specified');
			}

			$query['fields'] = array_merge(
				(array)$query['fields'],
				array(
					$this->alias . '.' . $this->primaryKey,
					$this->alias . '.' . $this->displayField,
					$this->alias . '.shop_option_id',
					$this->ShopPrice->alias . '.' . $this->ShopPrice->primaryKey,
					$this->ShopPrice->alias . '.selling',
					$this->ShopPrice->alias . '.retail'
				)
			);

			$query['conditions'] = array_merge(
				(array)$query['conditions'],
				array(
					$this->alias . '.shop_option_id' => $query['shop_option_id']
				)
			);

			$query['joins'] = array_merge(
				(array)$query['joins'],
				array(
					$this->autoJoinModel($this->ShopPrice->fullModelName())
				)
			);

			return $query;
		}

		if(empty($results)) {
			return array();
		}

		$productValueIgnores = $this->ShopProductsOptionValueIgnore->find('all', array(
			'fields' => array(
				$this->ShopProductsOptionValueIgnore->alias . '.' . $this->ShopProductsOptionValueIgnore->primaryKey,
				$this->ShopProductsOptionValueIgnore->alias . '.shop_option_value_id',
				$this->ShopProductsOptionValueIgnore->alias . '.model',
				$this->ShopProductsOptionValueIgnore->alias . '.foreign_key',
			),
			'conditions' => array(
				$this->ShopProductsOptionValueIgnore->alias . '.shop_option_value_id' => Hash::extract($results, '{n}.' . $this->alias . '.' . $this->primaryKey),
				$this->ShopProductsOptionValueIgnore->alias . '.model' => 'Shop.ShopProduct'
			)
		));

		foreach($results as &$result) {
			$result[$this->alias][$this->ShopPrice->alias] = $result[$this->ShopPrice->alias];
			$extractTemplate = sprintf('{n}.%s[shop_option_value_id=%s]', $this->ShopProductsOptionValueIgnore->alias, $result[$this->alias][$this->primaryKey]);
			$result[$this->alias]['ProductOptionValueIgnore'] = Hash::extract($productValueIgnores, $extractTemplate);
			unset($result[$this->ShopPrice->alias]);
		}

		return Hash::extract($results, '{n}.' . $this->alias);
	}
}
