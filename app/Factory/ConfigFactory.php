<?php

namespace Wordrobe\Factory;

use Wordrobe\Config;
use Wordrobe\Helper\Dialog;
use Wordrobe\Helper\FilesManager;

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
		$themesPath = self::askForThemesPath();
		Config::init(['{THEMES_PATH}' => $themesPath]);
		Dialog::write('Project configured!', 'green');
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

	/**
	 * Asks for
	 * @return mixed
	 */
	private static function askForThemeDirectoryCreation()
	{
		return Dialog::getConfirmation("Directory doesn't exist. Do you want to create it?", true, 'yellow');
	}
}
