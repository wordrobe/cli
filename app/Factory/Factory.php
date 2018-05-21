<?php

namespace Wordrobe\Factory;

/**
 * Class Factory
 * @package Wordrobe\Factory
 */
interface Factory
{
	/**
	 * Handles entity creation wizard
	 */
	public static function startWizard();

	/**
	 * Creates entity
	 * @param mixed ...$args
	 */
	public static function create(...$args);
}
