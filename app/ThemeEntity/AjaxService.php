<?php

namespace Wordrobe\ThemeEntity;

/**
 * Class AjaxService
 * @package Wordrobe\ThemeEntity
 */
class AjaxService implements ThemeEntity {

	private $name;

	/**
	 * AjaxService constructor.
	 * @param $name
	 */
	function __construct($name)
	{
		$this->name = $name;
		add_action("wp_ajax_nopriv_$name", [$this, 'register']);
		add_action("wp_ajax_$name", [$this, 'register']);
	}

	public function register()
	{
		// Service logic here
	}
}
