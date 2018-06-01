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
    
    try {
      self::build([
        'action' => $action,
        'theme' => $theme,
        'override' => 'ask'
      ]);
    } catch (\Exception $e) {
      Dialog::write($e->getMessage(), 'red');
      exit;
    }
    
    Dialog::write('Ajax service added!', 'green');
	}

	/**
	 * Builds ajax service
	 * @param array $params
	 * @example AjaxServiceBuilder::create([
	 * 	'action' => $action,
	 *	'theme' => $theme,
   *  'override' => 'ask'|'force'|false
	 * ]);
	 */
	public static function build($params)
	{
		$params = self::checkParams($params);
		$filename = StringsManager::toKebabCase($params['action']);
		$theme_path = PROJECT_ROOT . '/' . Config::get('themes-path') . '/' . $params['theme'];
		$ajax_service = new Template('ajax-service', [
			'{KEY}' => $filename,
			'{ACTION}' => $params['action']
		]);
		$ajax_service->save("$theme_path/includes/services/ajax/$filename.php", $params['override']);
	}

	/**
	 * Asks for action
	 * @return string
	 */
	private static function askForAction()
	{
		$action = Dialog::getAnswer('Action (e.g. send_email):');
		return $action ? $action : self::askForAction();
	}

	/**
	 * Checks params existence and normalizes them
	 * @param $params
	 * @return array
	 * @throws \Exception
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
    $override = ($params['override'] === 'ask' || $params['override'] === 'force') ? $params['override'] : false;

		if (!Config::get("themes.$theme")) {
			throw new \Exception("Error: theme '$theme' doesn't exist.");
		}

		return [
			'action' => $action,
			'theme' => $theme,
      'override' => $override
		];
	}
}
