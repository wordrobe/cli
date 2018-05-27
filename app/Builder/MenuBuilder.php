<?php

namespace Wordrobe\Builder;

use Wordrobe\Config;
use Wordrobe\Helper\Dialog;
use Wordrobe\Helper\StringsManager;
use Wordrobe\Entity\Template;

class MenuBuilder extends TemplateBuilder implements Builder
{
	/**
	 * Handles menu template creation wizard
	 */
	public static function startWizard()
	{
		$theme = self::askForTheme();
		$location = self::askForLocation();
		$name = self::askForName($location);
		$description = self::askForDescription();
		self::build([
			'location' => $location,
			'name' => $name,
			'$description' => $description,
			'theme' => $theme
		]);
	}

	/**
	 * Builds menu template
	 * @param array $params
	 * @example MenuBuilder::create([
	 *	'location' => $location,
	 *	'name' => $name,
	 *	'$description' => $description,
	 *	'theme' => $theme
	 * ]);
	 */
	public static function build($params)
	{
		$params = self::checkParams($params);
		$filename = StringsManager::toKebabCase($params['location']);
		$theme_path = PROJECT_ROOT . '/' . Config::expect('themes-path') . '/' . $params['theme'];
		$menu = new Template('menu', [
			'{LOCATION}' => $params['location'],
			'{NAME}' => $params['name'],
			'{DESCRIPTION}' => $params['description']
		]);

		$saved = $menu->save("$theme_path/includes/menus/$filename.php");

		if ($saved) {
			Dialog::write('Menu added!', 'green');
		}
	}

	/**
	 * Asks for location
	 * @return mixed
	 */
	private static function askForLocation()
	{
		return Dialog::getAnswer('Location (e.g. main_menu):');
	}

	/**
	 * Asks for name
	 * @param $location
	 * @return mixed
	 */
	private static function askForName($location)
	{
		$default = ucwords(StringsManager::removeDashes($location));
		return Dialog::getAnswer("Name [$default]:", $default);
	}

	/**
	 * Asks for description
	 * @return mixed
	 */
	private static function askForDescription()
	{
		return Dialog::getAnswer('Description:');
	}

	/**
	 * Checks params existence and normalizes them
	 * @param $params
	 * @return array
	 */
	private static function checkParams($params)
	{
		// checking existence
		if (!$params['location'] || !$params['name'] || !$params['theme']) {
			Dialog::write('Error: unable to create menu because of missing parameters', 'red');
			exit;
		}

		// normalizing
		$location = StringsManager::toSnakeCase($params['location']);
		$name = ucwords($params['name']);
		$description = ucfirst($params['description']);
		$theme = StringsManager::toKebabCase($params['theme']);

		return [
			'location' => $location,
			'name' => $name,
			'$description' => $description,
			'theme' => $theme
		];
	}
}
