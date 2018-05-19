<?php

namespace Wordrobe\Entity;

/**
 * Class ChildTheme
 * @package Wordrobe\Entity
 */
class ChildTheme extends Theme
{
	private $parent;

	/**
	 * ChildTheme constructor.
	 * @param $name
	 * @param $description
	 * @param Theme $parent
	 */
	function __construct($name, $description, Theme $parent)
	{
		parent::__construct($name, $description, $parent->template_engine);
		$this->parent = $parent;
	}

	/**
	 * Parent theme getter
	 * @return null|Theme
	 */
	public function getParent()
	{
		return $this->parent;
	}
}