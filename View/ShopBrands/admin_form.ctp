<?php
	/**
	 * Add some documentation for this edit form.
	 *
	 * @copyright Copyright (c) 2009 Carl Sutton (dogmatic69)
	 *
	 * @link    http://infinitas-cms.org/Shop
	 * @package	Shop.views.edit
	 * @license	http://infinitas-cms.org/mit-license The MIT License
	 * @since   0.9b1
	 *
	 * @author dogmatic69
	 *
	 * Licensed under The MIT License
	 * Redistributions of files must retain the above copyright notice.
	 */
	echo $this->Form->create(null, array('type' => 'file'));
		echo $this->Infinitas->adminEditHead();
		echo $this->Form->input('id');

		$tabs = array(
			__d('shop', 'Brand')
		);

		$contents = array(
			implode('', array(
				$this->Form->input('name'),
				$this->Form->input('slug'),
				$this->Form->input('image', array('type' => 'file')),
				$this->Form->input('active')
			))
		);

		echo $this->Design->tabs($tabs, $contents);
	echo $this->Form->end();
