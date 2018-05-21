<?php

namespace Wordrobe\Factory;

use Wordrobe\Helper\Dialog;
use Wordrobe\Helper\StringsManager;
use Wordrobe\Entity\ChildTheme;

/**
 * Class ChildThemeFactory
 * @package Wordrobe\Factory
 */
class ChildThemeFactory extends ThemeFactory
{
	/**
	 * Handles child theme creation wizard
	 */
	public static function startWizard()
	{
		$theme_name = parent::askForThemeName();
		$theme_uri = parent::askForThemeURI();
		$author = parent::askForAuthor();
		$author_uri = parent::askForAuthorURI();
		$description = parent::askForDescription();
		$version = parent::askForVersion();
		$license = parent::askForLicense();
		$license_uri = parent::askForLicenseURI();
		$text_domain = parent::askForTextDomain($theme_name);
		$tags = parent::askForTags();
		$folder_name = parent::askForFolderName($theme_name);
		$parent = self::askForParentTheme();
		self::create($theme_name, $theme_uri, $author, $author_uri, $description, $version, $license, $license_uri, $text_domain, $tags, $folder_name, $parent);
	}

	/**
	 * Creates child theme
	 * @param mixed ...$args
	 * @example ChildThemeFactory::create($theme_name, $theme_uri, $author, $author_uri, $description, $version, $license, $license_uri, $text_domain, $tags, $folder_name, $parent);
	 */
	public static function create(...$args)
	{
		if (func_num_args() < 12) {
			Dialog::write("Error: unable to create theme because of missing parameters");
			exit;
		}

		$theme_name = func_get_arg(0);
		$theme_uri = func_get_arg(1);
		$author = func_get_arg(2);
		$author_uri = func_get_arg(3);
		$description = func_get_arg(4);
		$version = func_get_arg(5);
		$license = func_get_arg(6);
		$license_uri = func_get_arg(7);
		$text_domain = func_get_arg(8);
		$tags = func_get_arg(9);
		$folder_name = func_get_arg(10);
		$parent = func_get_arg(11);

		$theme = new ChildTheme($theme_name, $theme_uri, $author, $author_uri, $description, $version, $license, $license_uri, $text_domain, $tags, $folder_name, $parent);
		$theme->install();
		Dialog::write('Child theme installed!', 'green');
	}

	/**
	 * Asks for child theme's parent
	 * @return mixed
	 */
	protected static function askForParentTheme()
	{
		$parent_theme = Dialog::getAnswer('Parent theme:');

		if (!$parent_theme) {
			return self::askForParentTheme();
		}

		return StringsManager::toKebabCase($parent_theme);
	}
}
