<?php
/**
 * ShopProducts controller
 *
 * @brief Add some documentation for ShopProducts controller.
 *
 * @copyright Copyright (c) 2009 Carl Sutton (dogmatic69)
 *
 * @link		  http://infinitas-cms.org/Shop
 * @package	   Shop.Controller
 * @license	   http://infinitas-cms.org/mit-license The MIT License
 * @since 0.9b1
 *
 * @author dogmatic69
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 */

class ShopProductsController extends ShopAppController {
/**
 * @brief the index method
 *
 * Show a paginated list of ShopProduct records.
 *
 * @todo update the documentation
 *
 * @return void
 */
	public function admin_index() {
		$this->Paginator->settings = array('adminPaginated');

		$shopProducts = $this->Paginator->paginate(null, $this->Filter->filter);

		$filterOptions = $this->Filter->filterOptions;
		$filterOptions['fields'] = array(
			'name',
			'shop_product_type_id' => $this->{$this->modelClass}->ShopProductType->find('list'),
			'shop_brand_id' => $this->{$this->modelClass}->ShopBrand->find('list'),
			'shop_supplier_id' => $this->{$this->modelClass}->ShopSupplier->find('list'),
			'active' => (array)Configure::read('CORE.active_options'),
		);

		$this->set(compact('shopProducts', 'filterOptions'));
	}

/**
 * @brief view method for a single row
 *
 * Show detailed information on a single ShopProduct
 *
 * @todo update the documentation
 * @param mixed $id int or string uuid or the row to find
 *
 * @return void
 */
	public function admin_view($id = null) {
		if(!$id) {
			$this->Infinitas->noticeInvalidRecord();
		}

		$shopProduct = $this->ShopProduct->getViewData(
			array($this->ShopProduct->alias . '.' . $this->ShopProduct->primaryKey => $id)
		);

		$this->set(compact('shopProduct'));
	}

/**
 * @brief admin create action
 *
 * Adding new ShopProduct records.
 *
 * @todo update the documentation
 *
 * @return void
 */
	public function admin_add() {
		if(!empty($this->request->data)) {
			$this->request->data['ShopBranchStock'][0]['shop_branch_id'] = '5076d76c-6710-47cc-8f7e-0aeac0a80102';
			try {
				if($this->{$this->modelClass}->saveProduct($this->request->data)) {
					$this->notice('saved');
				}
				$this->notice('not_saved');
			} catch(Exception $e) {
				$this->notice($e);
			}
		}

		$shopImages = $this->ShopProduct->ShopImage->find('list');
		$shopCategories = $this->ShopProduct->ShopCategoriesProduct->ShopCategory->generateTreeList();
		$shopSuppliers = $this->ShopProduct->ShopSupplier->find('list');
		$shopBrands = $this->ShopProduct->ShopBrand->find('list');
		$shopProductTypes = $this->ShopProduct->ShopProductType->find('list');
		$shopBranches = $this->ShopProduct->ShopBranchStock->ShopBranch->find('all', array(
			'contain' => array(
				'ContactBranch.name',
				'Manager.full_name'
			)
		));
		$this->set(compact('shopImages', 'shopCategories', 'shopSuppliers', 'shopBrands', 'shopProductTypes', 'shopBranches'));
	}

/**
 * @brief admin edit action
 *
 * Edit old ShopProduct records.
 *
 * @todo update the documentation
 * @param mixed $id int or string uuid or the row to edit
 *
 * @return void
 */
	public function admin_edit($id = null) {
		if(!empty($this->request->data)) {
			$this->request->data['ShopBranchStock'][0]['shop_branch_id'] = '5076d76c-6710-47cc-8f7e-0aeac0a80102';
			try {
				if($this->{$this->modelClass}->saveProduct($this->request->data)) {
					$this->notice('saved');
				}
				$this->notice('not_saved');
			} catch(Exception $e) {
				$this->notice($e);
			}
		} else {
			parent::admin_edit($id, array(
				'contain' => array(
					'ShopPrice',
					'ShopSize',
					'ShopImagesProduct',
					'ShopCategoriesProduct'
				)
			));
		}

		$shopImages = $this->ShopProduct->ShopImage->find('list');
		$shopCategories = $this->ShopProduct->ShopCategoriesProduct->ShopCategory->generateTreeList();
		$shopSuppliers = $this->ShopProduct->ShopSupplier->find('list');
		$shopBrands = $this->ShopProduct->ShopBrand->find('list');
		$shopProductTypes = $this->ShopProduct->ShopProductType->find('list');
		$shopBranches = $this->ShopProduct->ShopBranchStock->ShopBranch->find('list');
		$this->set(compact('shopImages', 'shopCategories', 'shopSuppliers', 'shopBrands', 'shopProductTypes', 'shopBranches'));
	}
}