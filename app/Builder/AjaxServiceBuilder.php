<?php

namespace Wordrobe\Builder;

use Wordrobe\Helper\Config;
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
    try {
      $theme = self::askForTheme();
      $action = self::askForAction();
      self::build([
        'action' => $action,
        'theme' => $theme,
        'override' => 'ask'
      ]);
      Dialog::write('Ajax service added!', 'green');
    } catch (\Exception $e) {
      Dialog::write($e->getMessage(), 'red');
      exit;
    }
	}

	/**
	 * Builds ajax service
	 * @param array $params
	 * @example AjaxServiceBuilder::create([
	 * 	'action' => $action,
	 *	'theme' => $theme,
   *  'override' => 'ask'|'force'|false
	 * ]);
   * @throws \Exception
	 */
	public static function build($params)
	{
		$params = self::checkParams($params);
		$filename = StringsManager::toKebabCase($params['action']);
		$theme_path = PROJECT_ROOT . '/' . Config::get('themes-path', true) . '/' . $params['theme'];
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
		return $action ?: self::askForAction();
	}

	/**
	 * Checks params existence and normalizes them
	 * @param array $params
	 * @return array
	 * @throws \Exception
	 */
	private static function checkParams($params)
	{
		// checking existence
		if (!$params['action'] || !$params['theme']) {
      throw new \Exception('Error: unable to create ajax service because of missing parameters');
		}

		// normalizing
		$action = StringsManager::toSnakeCase($params['action']);
		$theme = StringsManager::toKebabCase($params['theme']);
    $override = strtolower($params['override']);
    
    if ($override !== 'ask' && $override !== 'force') {
      $override = false;
    }
    
    Config::check("themes.$theme", 'array', "Error: theme '$theme' doesn't exist.");

		return [
			'action' => $action,
			'theme' => $theme,
      'override' => $override
		];
	}
}
