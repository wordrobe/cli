<?php

namespace Wordrobe\Factory;

use Wordrobe\Config;
use Wordrobe\Helper\Dialog;

/**
 * Class ConfigFactory
 * @package Wordrobe\Factory
 */
class ConfigFactory implements Factory
{
	/**
	 * Handles config creation wizard
	 */
	public static function startWizard()
	{
		$themes_path = self::askForThemesPath();
		self::create($themes_path);
	}

	/**
	 * Creates config
	 * @param mixed ...$args
	 * @example ConfigFactory::create($themes_path);
	 */
	public static function create(...$args)
	{
		if (func_num_args() < 1) {
			Dialog::write("Error: unable to create config because of missing parameters");
			exit;
		}

		$themes_path = func_get_arg(0);

		Config::init(['{THEMES_PATH}' => $themes_path]);
		Dialog::write('Configuration completed!', 'green');
	}

	/**
	 * Asks for themes path
	 * @return mixed
	 */
	private static function askForThemesPath()
	{
		$themes_path = Dialog::getAnswer('Please provide themes directory path (e.g. wp-content/themes):');

		if (!$themes_path) {
			return self::askForThemesPath();
		}

		return $themes_path;
	}
}
