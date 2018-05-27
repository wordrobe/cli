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
		$params = self::checkParams($params);
		$theme_path = PROJECT_ROOT . '/' . Config::expect('themes-path') . '/' . $params['theme'];
		$term = new Template('term', [
			'{NAME}' => $params['name'],
			'{TAXONOMY}' => $params['taxonomy'],
			'{SLUG}' => $params['slug'],
			'{DESCRIPTION}' => $params['description'],
			'{PARENT}' => $params['parent']
		]);
		$saved = $term->save("$theme_path/includes/terms/" . $params['taxonomy'] . "/" . $params['slug'] . ".php");

		if ($saved) {
			Dialog::write("Term '" . $params['name'] . "' added!", 'green');
		}

		if ($params['build_archive']) {

			if ($params['taxonomy'] === 'category' || $params['taxonomy'] === 'tag') {
				$type = $params['taxonomy'];
				$key = $params['slug'];
			} else {
				$type = 'taxonomy';
				$key = $params['taxonomy'] . '-' . $params['slug'];
			}

			ArchiveBuilder::build([
				'type' => $type,
				'key' => $key,
				'theme' => $params['theme']
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

	/**
	 * Checks params existence and normalizes them
	 * @param $params
	 * @return array
	 */
	private static function checkParams($params)
	{
		// checking existence
		if (!$params['name'] || !$params['taxonomy'] || !$params['theme']) {
			Dialog::write('Error: unable to create term because of missing parameters.', 'red');
			exit;
		}

		// normalizing
		$name = ucwords($params['name']);
		$taxonomy = StringsManager::toKebabCase($params['taxonomy']);
		$slug = StringsManager::toKebabCase($params['slug']);
		$description = ucfrist($params['description']);
		$parent = StringsManager::toKebabCase($params['parent']);
		$theme = StringsManager::toKebabCase($params['theme']);
		$build_archive = $params['build-archive'] || false;

		return [
			'name' => $name,
			'taxonomy' => $taxonomy,
			'slug' => $slug,
			'description' => $description,
			'parent' => $parent,
			'theme' => $theme,
			'build-archive' => $build_archive
		];
	}
}
