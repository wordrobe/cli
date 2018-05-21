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
