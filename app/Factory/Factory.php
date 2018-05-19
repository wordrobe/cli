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
	 * @return mixed
	 */
	public static function startWizard();
}
