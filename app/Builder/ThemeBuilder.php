<?php

namespace Wordrobe\Builder;

use Wordrobe\Config;
use Wordrobe\Helper\Dialog;
use Wordrobe\Entity\Theme;
use Wordrobe\Helper\StringsManager;

/**
 * Class ThemeBuilder
 * @package Wordrobe\Builder
 */
class ThemeBuilder implements Builder
{
    /**
     * Handles theme creation wizard
     */
    public static function startWizard()
    {
        Config::expect('themes_path');
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
        self::build([
            'theme_name' => $theme_name,
            'theme_uri' => $theme_uri,
            'author' => $author,
            'author_uri' => $author_uri,
            'description' => $description,
            'version' => $version,
            'license' => $license,
            'license_uri' => $license_uri,
            'text_domain' => $text_domain,
            'tags' => $tags,
            'folder_name' => $folder_name,
            'template_engine' => $template_engine
        ]);
    }

    /**
     * Builds theme
     * @param array $params
     * @example ThemeBuilder::create([
     *	'theme_name' => $theme_name,
     *	'theme_uri' => $theme_uri,
     *	'author' => $author,
     *	'author_uri' => $author_uri,
     *	'description' => $description,
     *	'version' => $version,
     *	'license' => $license,
     *	'license_uri' => $license_uri,
     *	'text_domain' => $text_domain,
     *	'tags' => $tags,
     *	'folder_name' => $folder_name,
     *	'template_engine' => $template_engine
     * ]);
     */
    public static function build($params)
    {
        $theme_name = $params['theme_name'];
        $theme_uri = $params['theme_uri'];
        $author = $params['author'];
        $author_uri = $params['author_uri'];
        $description = $params['description'];
        $version = $params['version'];
        $license = $params['license'];
        $license_uri = $params['license_uri'];
        $text_domain = $params['text_domain'];
        $tags = $params['tags'];
        $folder_name = $params['folder_name'];
        $template_engine = $params['template_engine'];

        if (!$theme_name || !$text_domain || !$folder_name || !$template_engine) {
            Dialog::write('Error: unable to create archive because of missing parameters.', 'red');
            exit;
        }

        $theme = new Theme($theme_name, $theme_uri, $author, $author_uri, $description, $version, $license, $license_uri, $text_domain, $tags, $folder_name, $template_engine);
        $installed = $theme->install();

		if ($installed) {
			Dialog::write('Theme installed!', 'green');
		}
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
        $choice = Dialog::getChoice('Template engine:', array_keys($template_engines), null);
        return $template_engines[$choice];
    }
}
