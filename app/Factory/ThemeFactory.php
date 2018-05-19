<?php

namespace Wordrobe\Factory;

use Wordrobe\Helper\Dialog;
use Wordrobe\Entity\Theme;
use Wordrobe\Helper\StringsManager;

/**
 * Class ThemeFactory
 * @package Wordrobe\Factory
 */
class ThemeFactory implements Factory
{
	/**
	 * Handles theme creation wizard
	 */
	public static function startWizard()
	{
		$theme_name = self::askForThemeName();
		$theme_uri = self::askForThemeURI();
		$author = self::askForAuthor();
		$author_uri = self::askForAuthorURI();
		$description = self::askForDescription();
		$version = self::askForVersion();
		$license = self::askForLicense();
		$license_uri = self::askForLicenseURI();
		$text_domain = self::askForTextDomain($theme_name);
		$tags = self::askForTags();
		$folder_name = self::askForFolderName($theme_name);
		$template_engine = self::askForTemplateEngine();
		$theme = new Theme($theme_name, $theme_uri, $author, $author_uri, $description, $version, $license, $license_uri, $text_domain, $tags, $folder_name, $template_engine);
		$theme->install();
		Dialog::write('Theme created!', 'green');
	}

	/**
	 * Ask for theme's name
	 * @return mixed
	 */
	protected static function askForThemeName()
	{
		$theme_name = Dialog::getAnswer('Theme name (e.g. My Theme):');
		if (!$theme_name) {
			return self::askForThemeName();
		}
		return ucwords($theme_name);
	}

	/**
	 * Asks for theme's URI
	 * @return mixed
	 */
	protected static function askForThemeURI()
	{
		return Dialog::getAnswer('Theme URI (e.g. http://my-theme.com):');
	}

	/**
	 * Asks for theme's author
	 * @return mixed
	 */
	protected static function askForAuthor()
	{
		$author = Dialog::getAnswer('Author (e.g. John Doe):');
		return ucwords($author);
	}

	/**
	 * Asks for theme's author URI
	 * @return mixed
	 */
	protected static function askForAuthorURI()
	{
		return Dialog::getAnswer('Author URI (e.g. http://john-doe.com):');
	}

	/**
	 * Asks for theme's description
	 * @return mixed
	 */
	protected static function askForDescription()
	{
		$description = Dialog::getAnswer('Description:');
		return ucfirst($description);
	}

	/**
	 * Asks for theme's version
	 * @return mixed
	 */
	protected static function askForVersion()
	{
		return Dialog::getAnswer('Version [1.0]:', '1.0');
	}

	/**
	 * Asks for theme's license
	 * @return mixed
	 */
	protected static function askForLicense()
	{
		return Dialog::getAnswer('License [GNU General Public License]:', 'GNU General Public License');
	}

	/**
	 * Asks for theme's license URI
	 * @return mixed
	 */
	protected static function askForLicenseURI()
	{
		return Dialog::getAnswer('License URI [http://www.gnu.org/licenses/gpl-2.0.html]:', 'http://www.gnu.org/licenses/gpl-2.0.html');
	}

	/**
	 * Asks for theme's text domain
	 * @param $theme_name
	 * @return mixed
	 */
	protected static function askForTextDomain($theme_name)
	{
		$default = StringsManager::toKebabCase($theme_name);
		$text_domain = Dialog::getAnswer("Text domain [$default]:", $default);
		return StringsManager::toKebabCase($text_domain);
	}

	/**
	 * Asks for theme's tags
	 * @return mixed
	 */
	protected static function askForTags()
	{
		$tags = Dialog::getAnswer('Tags (e.g. modern, flat, simple, e-commerce):');
		return strtolower($tags);
	}

	/**
	 * Asks for theme's folder name
	 * @param $theme_name
	 * @return mixed
	 */
	protected static function askForFolderName($theme_name)
	{
		$default = StringsManager::toKebabCase($theme_name);
		$folder_name = Dialog::getAnswer("Folder name [$default]:", $default);
		return StringsManager::toKebabCase($folder_name);
	}

	/**
	 * Asks for theme's template engine
	 * @return mixed
	 */
	protected static function askForTemplateEngine()
	{
		$template_engines = [
			'Twig (Timber)' => 'timber',
			'PHP (Standard Wordpress)' => 'standard'
		];
		$choice = Dialog::getChoice('Template engine:', array_keys($template_engines), 0);
		return $template_engines[$choice];
	}
}
