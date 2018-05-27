<?php

namespace Wordrobe\Builder;

use Wordrobe\Config;
use Wordrobe\Helper\Dialog;
use Wordrobe\Helper\StringsManager;
use Wordrobe\Entity\Template;

class TermBuilder extends TemplateBuilder implements Builder
{
	/**
	 * Handles term creation wizard
	 */
	public static function startWizard()
	{
		$theme = self::askForTheme();
		$name = self::askForName();
		$taxonomy = self::askForTaxonomy($theme);
		$slug = self::askForSlug($name);
		$description = self::askForDescription();
		$parent = self::askForParent();
		$build_archive = self::askForArchiveTemplateBuild($slug);
		self::build([
			'name' => $name,
			'taxonomy' => $taxonomy,
			'slug' => $slug,
			'description' => $description,
			'parent' => $parent,
			'theme' => $theme,
			'build-archive' => $build_archive
		]);
	}

	/**
	 * Builds term
	 * @param array $params
	 * @example TermBuilder::create([
	 *	'name' => $name,
	 *	'taxonomy' => $taxonomy,
	 *	'slug' => $slug,
	 *	'description' => $description,
	 *	'parent' => $parent,
	 *	'theme' => $theme,
	 *	'build-archive' => $build_archive
	 * ]);
	 */
	public static function build($params)
	{
		$name = $params['name'];
		$taxonomy = $params['taxonomy'];
		$slug = $params['slug'];
		$description = $params['description'];
		$parent = $params['parent'];
		$theme = $params['theme'];
		$build_archive = $params['build-archive'] || false;

		if (!$name || !$taxonomy || !$theme) {
			Dialog::write('Error: unable to create term because of missing parameters.', 'red');
			exit;
		}

		$theme_path = PROJECT_ROOT . '/' . Config::expect('themes-path') . '/' . $theme;
		$term = new Template('term', [
			'{NAME}' => $name,
			'{TAXONOMY}' => $taxonomy,
			'{SLUG}' => $slug,
			'{DESCRIPTION}' => $description,
			'{PARENT}' => $parent
		]);
		$saved = $term->save("$theme_path/includes/terms/$taxonomy/$slug.php");

		if ($saved) {
			Dialog::write("Term '$name' added!", 'green');
		}

		if ($build_archive) {

			if ($taxonomy === 'category' || $taxonomy === 'tag') {
				$type = $taxonomy;
				$key = $slug;
			} else {
				$type = 'taxonomy';
				$key = "$taxonomy-$slug";
			}

			ArchiveBuilder::build([
				'type' => $type,
				'key' => $key,
				'theme' => $theme
			]);
		}
	}

	/**
	 * Asks for term name
	 * @return string
	 */
	private static function askForName()
	{
		$name = Dialog::getAnswer('Term name (e.g. Entertainment):');

		if (!$name) {
			return self::askForName();
		}

		return ucwords($name);
	}

	/**
	 * Asks for taxonomy
	 * @param $theme
	 * @return mixed
	 */
	private static function askForTaxonomy($theme)
	{
		$taxonomies = Config::get("themes.$theme.taxonomies");
		return Dialog::getChoice('Taxonomy:', $taxonomies, null);
	}

	/**
	 * Asks for slug
	 * @param $name
	 * @return mixed
	 */
	private static function askForSlug($name)
	{
		$default = StringsManager::toKebabCase($name);
		$slug = Dialog::getAnswer("Slug [$default]:", $default);
		return StringsManager::toKebabCase($slug);
	}

	/**
	 * Asks for description
	 * @return string
	 */
	private static function askForDescription()
	{
		$description = Dialog::getAnswer('Description:');
		return ucfirst($description);
	}

	/**
	 * Asks for parent
	 * @return mixed|null
	 */
	private static function askForParent()
	{
		$parent_slug = Dialog::getAnswer('Parent term slug [null]:');

		if ($parent_slug) {
			return StringsManager::toKebabCase($parent_slug);
		}

		return null;
	}

	/**
	 * Asks for archive template auto-build confirmation
	 * @param $slug
	 * @return mixed
	 */
	private static function askForArchiveTemplateBuild($slug)
	{
		return Dialog::getConfirmation("Do you want to automatically create an archive template for '$slug' term?", true, 'yellow');
	}
}
