<?php

namespace Wordrobe\Factory;

use Wordrobe\Config;
use Wordrobe\Helper\Dialog;
use Wordrobe\Helper\StringsManager;
use Wordrobe\Entity\Template;

class SingleFactory extends TemplateFactory implements Factory
{
	/**
	 * Handles single template creation wizard
	 */
	public static function startWizard()
	{
		$theme = self::askForTheme();
		$post_type = self::askForPostType();
		self::create($post_type, $theme);
	}

	/**
	 * Creates single template
	 * @param mixed ...$args
	 * @example SingleFactory::create($post_type, $theme);
	 */
	public static function create(...$args)
	{
		if (func_num_args() < 2) {
			Dialog::write("Error: unable to create single template because of missing parameters");
			exit;
		}

		$post_type = func_get_arg(0);
		$theme = func_get_arg(1);

		$filename = "single-$post_type";
		$template_engine = Config::get('template_engine', ['themes', $theme]);
		$theme_path = PROJECT_ROOT . '/' . Config::get('themes_path') . '/' . $theme;
		$single_ctrl = new Template("$template_engine/single", ['{POST_TYPE}' => $post_type]);

		if ($template_engine === 'timber') {
			$single_ctrl->fill('{VIEW_FILENAME}', $filename);
			$single_view = new Template('timber/view');
			$single_view->save("$theme_path/views/default/$filename.html.twig");
		}

		$single_ctrl->save("$theme_path/$filename.php");
		Dialog::write("Single template for post type '$post_type' added!", 'green');
	}

	/**
	 * Asks for post type
	 * @return string
	 */
	private static function askForPostType()
	{
		$post_type = Dialog::getAnswer('Post type (e.g. event):');

		if (!$post_type) {
			return self::askForPostType();
		}

		return StringsManager::toKebabCase($post_type);
	}
}