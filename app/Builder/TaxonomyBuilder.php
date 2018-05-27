<?php

namespace Wordrobe\Builder;

use Wordrobe\Config;
use Wordrobe\Helper\Dialog;
use Wordrobe\Helper\StringsManager;
use Wordrobe\Entity\Template;

class TaxonomyBuilder extends TemplateBuilder implements Builder
{
	/**
	 * Handles taxonomy creation wizard
	 */
	public static function startWizard()
	{
		$theme = self::askForTheme();
		$key = self::askForKey();
		$general_name = self::askForGeneralName($key);
		$singular_name = self::askForSingularName($general_name);
		$text_domain = self::askForTextDomain($theme);
		$post_types = self::askForPostTypes($theme);
		$hierarchical = self::askForHierarchy();
		$build_archive = self::askForArchiveTemplateBuild($key);
		self::build([
			'key' => $key,
			'general_name' => $general_name,
			'singular_name' => $singular_name,
			'text-domain' => $text_domain,
			'post-types' => $post_types,
			'hierarchical' => $hierarchical,
			'theme' => $theme,
			'build-archive' => $build_archive
		]);
	}

	/**
	 * Builds taxonomy
	 * @param array $params
	 * @example TaxonomyBuilder::create([
	 * 	'key' => $key,
	 *	'general_name' => $general_name,
	 *	'singular_name' => $singular_name,
	 *	'text-domain' => $text_domain,
	 *	'post-types' => $post_types,
	 *	'hierarchical' => $hierarchical,
	 *	'theme' => $theme,
	 * 	'build-archive' => $build_archive
	 * ]);
	 */
	public static function build($params)
	{
		$params = self::checkParams($params);
		$theme_path = PROJECT_ROOT . '/' . Config::expect('themes-path') . '/' . $params['theme'];
		$taxonomy = new Template('taxonomy', [
			'{KEY}' => $params['key'],
			'{GENERAL_NAME}' => $params['general_name'],
			'{SINGULAR_NAME}' => $params['singular_name'],
			'{TEXT_DOMAIN}' => $params['text_domain'],
			'{POST_TYPES}' => $params['post_types'],
			'{HIERARCHICAL}' => $params['hierarchical']
		]);
		$saved = $taxonomy->save("$theme_path/includes/taxonomies/" . $params['key'] . ".php");
		Config::add('themes.' . $params['theme'] . '.taxonomies', $params['key']);

		if ($saved) {
			Dialog::write("Taxonomy '" . $params['key'] . "' added!", 'green');
		}

		if ($params['build_archive']) {
			ArchiveBuilder::build([
				'type' => 'taxonomy',
				'key' => $params['key'],
				'theme' => $params['theme']
			]);
		}
	}

	/**
	 * Asks for taxonomy key
	 * @return mixed
	 */
	private static function askForKey()
	{
		$key = Dialog::getAnswer('Taxonomy key (e.g. type):');

		if (!$key) {
			return self::askForKey();
		}

		return StringsManager::toKebabCase($key);
	}

	/**
	 * Asks for general name
	 * @param $key
	 * @return string
	 */
	private static function askForGeneralName($key)
	{
		$default = ucwords(str_replace('-', ' ', $key)) . 's';
		$general_name = Dialog::getAnswer("General name [$default]:", $default);

		if (!$general_name) {
			return self::askForGeneralName($key);
		}

		return ucwords($general_name);
	}

	/**
	 * Asks for singular name
	 * @param $general_name
	 * @return string
	 */
	private static function askForSingularName($general_name)
	{
		$default = substr($general_name, -1) === 's' ? substr($general_name, 0, -1) : $general_name;
		$singular_name = Dialog::getAnswer("Singular name [$default]:", $default);

		if (!$singular_name) {
			return self::askForSingularName($general_name);
		}

		return ucwords($singular_name);
	}

	/**
	 * Asks for text domain
	 * @param $theme
	 * @return mixed
	 */
	private static function askForTextDomain($theme)
	{
		$text_domain = Dialog::getAnswer("Text domain [$theme]:", $theme);

		if (!$text_domain) {
			return self::askForTextDomain($theme);
		}

		return StringsManager::toKebabCase($text_domain);
	}

	/**
	 * Asks for post types
	 * @param $theme
	 * @return string
	 */
	private static function askForPostTypes($theme)
	{
		$post_types = Dialog::getChoice('Post types:', Config::expect("themes.$theme.post-types", 'array'), null, true);
		return implode(',', $post_types);

	}

	/**
	 * Asks for hierarchy
	 */
	private static function askForHierarchy()
	{
		return Dialog::getConfirmation('Is hierarchical?', true, 'blue');
	}

	/**
	 * Asks for archive template auto-build confirmation
	 * @param $key
	 * @return mixed
	 */
	private static function askForArchiveTemplateBuild($key)
	{
		return Dialog::getConfirmation("Do you want to automatically create an archive template for '$key' taxonomy?", true, 'yellow');
	}

	/**
	 * Checks params existence and normalizes them
	 * @param $params
	 * @return array
	 */
	private static function checkParams($params)
	{
		// checking existence
		if (!$params['key'] || !$params['general_name'] || !$params['singular_name'] || !$params['text_domain'] || !$params['post_types'] || !$params['theme']) {
			Dialog::write('Error: unable to create taxonomy because of missing parameters.', 'red');
			exit;
		}

		// normalizing
		$key = StringsManager::toKebabCase($params['key']);
		$general_name = ucwords($params['general_name']);
		$singular_name = ucwords($params['singular_name']);
		$text_domain = StringsManager::toKebabCase($params['text-domain']);
		$post_types = strtolower(StringsManager::removeSpaces($params['post-types']));
		$hierarchical = $params['hierarchical'];
		$theme = StringsManager::toKebabCase($params['theme']);
		$build_archive = $params['build-archive'] || false;

		return [
			'key' => $key,
			'general_name' => $general_name,
			'singular_name' => $singular_name,
			'text-domain' => $text_domain,
			'post-types' => $post_types,
			'hierarchical' => $hierarchical,
			'theme' => $theme,
			'build-archive' => $build_archive
		];
	}
}
