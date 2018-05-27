<?php

namespace Wordrobe\Builder;

use Wordrobe\Config;
use Wordrobe\Helper\Dialog;
use Wordrobe\Helper\StringsManager;
use Wordrobe\Entity\Template;

class AjaxServiceBuilder extends TemplateBuilder implements Builder
{
	/**
	 * Handles ajax service creation wizard
	 */
	public static function startWizard()
	{
		$theme = self::askForTheme();
		$action = self::askForAction();
		self::build([
			'action' => $action,
			'theme' => $theme
		]);
	}

	/**
	 * Builds ajax service
	 * @param array $params
	 * @example AjaxServiceBuilder::create([
	 * 	'action' => $action,
	 *	'theme' => $theme
	 * ]);
	 */
	public static function build($params)
	{
		$params = self::checkParams($params);
		$filename = StringsManager::toKebabCase($params['action']);
		$theme_path = PROJECT_ROOT . '/' . Config::expect('themes-path') . '/' . $params['theme'];
		$ajax_service = new Template('ajax-service', [
			'{KEY}' => $filename,
			'{ACTION}' => $params['action']
		]);
		$saved = $ajax_service->save("$theme_path/includes/services/ajax/$filename.php");

		if ($saved) {
			Dialog::write("Service 'wp_ajax_" . $params['action'] . "' added!", 'green');
		}
	}

	/**
	 * Asks for action
	 * @return string
	 */
	private static function askForAction()
	{
		$action = Dialog::getAnswer('Action (e.g. send_email):');

		if (!$action) {
			return self::askForAction();
		}

		return StringsManager::toSnakeCase($action);
	}

	/**
	 * Checks params existence and normalizes them
	 * @param $params
	 * @return array
	 */
	private static function checkParams($params)
	{
		// checking existence
		if (!$params['action'] || !$params['theme']) {
			Dialog::write('Error: unable to create ajax service because of missing parameters', 'red');
			exit;
		}

		// normalizing
		$action = StringsManager::toSnakeCase($params['action']);
		$theme = StringsManager::toKebabCase($params['theme']);

		return [
			'action' => $action,
			'theme' => $theme
		];
	}
}
