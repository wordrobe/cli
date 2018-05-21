<?php

namespace Wordrobe\Factory;

use Wordrobe\Config;
use Wordrobe\Helper\Dialog;
use Wordrobe\Helper\StringsManager;
use Wordrobe\Entity\Template;

class PageFactory extends TemplateFactory implements Factory
{
	/**
	 * Handles page template creation wizard
	 */
	public static function startWizard()
	{
		$theme = self::askForTheme();
		$name = self::askForName();
		self::create($name, $theme);
	}

	/**
	 * Creates page template
	 * @param mixed ...$args
	 * @example PageFactory::create($name, $theme);
	 */
	public static function create(...$args)
	{
		if (func_num_args() < 2) {
			Dialog::write("Error: unable to create page template because of missing parameters");
			exit;
		}

		$name = func_get_arg(0);
		$theme = func_get_arg(1);

		$filename = StringsManager::toKebabCase($name);
		$template_engine = Config::get('template_engine', ['themes', $theme]);
		$theme_path = PROJECT_ROOT . '/' . Config::get('themes_path') . '/' . $theme;
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