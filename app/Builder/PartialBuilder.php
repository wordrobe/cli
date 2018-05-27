<?php

namespace Wordrobe\Builder;

use Wordrobe\Config;
use Wordrobe\Helper\Dialog;
use Wordrobe\Helper\StringsManager;
use Wordrobe\Entity\Template;

class PartialBuilder extends TemplateBuilder implements Builder
{
	/**
	 * Handles partial template creation wizard
	 */
	public static function startWizard()
	{
		$theme = self::askForTheme(['template-engine']);
		$class_name = self::askForClassName();
		self::build([
			'class_name' => $class_name,
			'theme' => $theme
		]);
	}

	/**
	 * Builds partial template
	 * @param array $params
	 * @example PartialBuilder::create([
	 * 	'class_name' => $class_name,
	 *	'theme' => $theme
	 * ]);
	 */
	public static function build($params)
	{
		$params = self::checkParams($params);
		$filename = StringsManager::toKebabCase($params['class_name']);
		$template_engine = Config::expect('themes.' . $params['theme'] . '.template-engine');
		$theme_path = PROJECT_ROOT . '/' . Config::expect('themes-path') . '/' . $params['theme'];
		$partial = new Template('partial', ['{CLASS_NAME}' => $params['class_name']]);

		if ($template_engine === 'timber') {
			$file_type = 'html.twig';
			$partials_path = 'views/partials';
		} else {
			$file_type = 'php';
			$partials_path = 'partials';
		}

		$saved = $partial->save("$theme_path/$partials_path/$filename.$file_type");

		if ($saved) {
			Dialog::write('Partial template added!', 'green');
		}
	}

	/**
	 * Asks for partial class name
	 * @return string
	 */
	private static function askForClassName()
	{
		$class_name = Dialog::getAnswer('Class name (e.g. my-partial):');

		if (!$class_name) {
			return self::askForClassName();
		}

		return $class_name;
	}

	/**
	 * Checks params existence and normalizes them
	 * @param $params
	 * @return array
	 */
	private static function checkParams($params)
	{
		// checking existence
		if (!$params['class_name'] || !$params['theme']) {
			Dialog::write('Error: unable to create partial template because of missing parameters', 'red');
			exit;
		}

		// normalizing
		$theme = StringsManager::toKebabCase($params['theme']);

		return [
			'class-name' => $params['class_name'],
			'theme' => $theme
		];
	}
}
