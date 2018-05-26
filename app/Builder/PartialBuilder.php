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
		$theme = self::askForTheme(['template_engine']);
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
		$class_name = $params['class_name'];
		$theme = $params['theme'];

		if (!$class_name || !$theme) {
			Dialog::write('Error: unable to create partial template because of missing parameters', 'red');
			exit;
		}

		$filename = StringsManager::toKebabCase($class_name);
		$template_engine = Config::expect("themes.$theme.template_engine");
		$theme_path = PROJECT_ROOT . '/' . Config::expect('themes_path') . '/' . $theme;
		$partial = new Template('partial', ['{CLASS_NAME}' => $class_name]);

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
}
