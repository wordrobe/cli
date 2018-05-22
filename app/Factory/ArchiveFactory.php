<?php

namespace Wordrobe\Factory;

use Wordrobe\Config;
use Wordrobe\Helper\Dialog;
use Wordrobe\Entity\Template;
use Wordrobe\Helper\StringsManager;

/**
 * Class ConfigFactory
 * @package Wordrobe\Factory
 */
class ArchiveFactory extends TemplateFactory implements Factory
{
	const TYPES = [
		'post-type',
		'category',
		'taxonomy',
		'tag'
	];

	/**
	 * Handles config creation wizard
	 */
	public static function startWizard()
	{
		$theme = self::askForTheme();
		$type = self::askForType();
		$term = $type === 'post-type' ? self::askForPostType() : self::askForTerm();
		self::create($type, $term, $theme);
	}

	/**
	 * Creates archive
	 * @param mixed ...$args
	 * @example ArchiveFactory::create($type, $term, $theme);
	 */
	public static function create(...$args)
	{
		if (func_num_args() < 3) {
			Dialog::write("Error: unable to create archive because of missing parameters");
			exit;
		}

		$type = func_get_arg(0);
		$term = func_get_arg(1);
		$theme = func_get_arg(2);

		$basename = $type === 'post-type' ? 'archive' : $type;
		$filename = $term ? "$basename-$term" : $basename;
		$template_engine = Config::get('template_engine', ['themes', $theme]);
		$theme_path = PROJECT_ROOT . '/' . Config::get('themes_path') . '/' . $theme;
		$type_and_term = trim(str_replace("''", '', "$type '$term'"));
		$archive_ctrl = new Template("$template_engine/archive", ['{TYPE_AND_TERM}' => $type_and_term]);

		if ($template_engine === 'timber') {
			$archive_ctrl->fill('{VIEW_FILENAME}', $filename);
			$archive_view = new Template('timber/view');
			$archive_view->save("$theme_path/views/default/$filename.html.twig");
		}

		$archive_ctrl->save("$theme_path/$filename.php");
		Dialog::write("Archive template for $type_and_term added!", 'green');
	}

	/**
	 * Asks for archive type
	 * @return mixed
	 */
	private static function askForType()
	{
		return Dialog::getChoice('What type of archive do you want to add?', self::TYPES, null);
	}

	/**
	 * Asks for post type
	 * @return mixed
	 */
	private static function askForPostType()
	{
		$post_type = Dialog::getAnswer('Post type:');

		if (!$post_type) {
			return self::askForPostType();
		}

		return StringsManager::toKebabCase($post_type);
	}

	/**
	 * Asks for term
	 * @return mixed
	 */
	private static function askForTerm()
	{
		$term = Dialog::getAnswer('Term:');
		return StringsManager::toKebabCase($term);
	}
}
