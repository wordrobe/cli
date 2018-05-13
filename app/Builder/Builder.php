<?php

namespace Wordrobe\Builder;

/**
 * Class Builder
 * @package Wordrobe\Builder
 */
abstract class Builder
{
	/**
	 * TemplateCreator constructor
	 */
	function __construct()
	{
		$this->wizard();
	}

	/**
	 * Provides a template build wizard
	 */
	protected function wizard()
	{
		throw new Exception('You must override the wizard() method in the concrete builder class.');
	}
}
