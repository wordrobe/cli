<?php

namespace Wordrobe\Builder;

use Wordrobe\Config;
use Wordrobe\Helper\Dialog;
use Wordrobe\Helper\StringsManager;
use Wordrobe\Entity\Template;

class PageBuilder extends TemplateBuilder implements Builder
{
	/**
	 * Handles page template creation wizard
	 */
	public static function startWizard()
	{
		$theme = self::askForTheme(['template_engine']);
		$name = self::askForName();
		self::build([
			'name' => $name,
			'theme' => $theme
		]);
	}

	/**
	 * Builds page template
	 * @param array $params
	 * @example PageBuilder::create([
	 * 	'name' => $name,
	 *	'theme' => $theme
	 * ]);
	 */
	public static function build($params)
	{
		$name = $params['name'];
		$theme = $params['theme'];

		if (!$name || !$theme) {
			Dialog::write('Error: unable to create page template because of missing parameters', 'red');
			exit;
		}

		$filename = StringsManager::toKebabCase($name);
		$template_engine = Config::expect("themes.$theme.template_engine");
		$theme_path = PROJECT_ROOT . '/' . Config::expect('themes_path') . '/' . $theme;
		$page_ctrl = new Template("$template_engine/page", ['{TEMPLATE_NAME}' => $name]);

		if ($template_engine === 'timber') {
			$page_ctrl->fill('{VIEW_FILENAME}', $filename);
			$page_view = new Template('timber/view');
			$page_view->save("$theme_path/views/pages/$filename.html.twig");
		}

		$page_ctrl->save("$theme_path/pages/$filename.php");
		Dialog::write("Page template '$name' added!", 'green');
	}

	/**
	 * Asks for page template name
	 * @return string
	 */
	private static function askForName()
	{
		$name = Dialog::getAnswer('Template name (e.g. My Custom Page):');

		if (!$name) {
			return self::askForName();
		}

		return ucwords($name);
	}
}