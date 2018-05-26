<?php

namespace Wordrobe\Builder;

use Wordrobe\Config;
use Wordrobe\Helper\Dialog;
use Wordrobe\Helper\StringsManager;
use Wordrobe\Entity\Template;

class PostTypeBuilder extends TemplateBuilder implements Builder
{
    /**
     * Handles post type creation wizard
     */
    public static function startWizard()
    {
        $theme = self::askForTheme();
        $key = self::askForKey();
        $general_name = self::askForGeneralName($key);
        $singular_name = self::askForSingularName($general_name);
        $text_domain = self::askForTextDomain($theme);
        $capability_type = self::askForCapabilityType();
        $taxonomies = self::askForTaxonomies();
        $icon = self::askForIcon();
        $description = self::askForDescription();
		$build_single = self::askForSingleTemplateBuild($key);
		$build_archive = self::askForArchiveTemplateBuild($key);
        self::build([
            'key' => $key,
            'general_name' => $general_name,
            'singular_name' => $singular_name,
            'text-domain' => $text_domain,
            'capability_type' => $capability_type,
            'taxonomies' => $taxonomies,
            'icon' => $icon,
            'description' => $description,
            'theme' => $theme,
			'build-single' => $build_single,
			'build-archive' => $build_archive
        ]);
    }

    /**
     * Builds post type
     * @param array $params
     * @example PostTypeBuilder::create([
     * 	'key' => $key,
     *	'general_name' => $general_name,
     *	'singular_name' => $singular_name,
     *	'text-domain' => $text_domain,
     *	'capability_type' => $capability_type,
     *	'taxonomies' => $taxonomies,
     *	'icon' => $icon,
     *	'description' => $description,
     *	'theme' => $theme,
	 *	'build-single' => $build_single,
	 *	'build-archive' => $build_archive
     * ]);
     */
    public static function build($params)
    {
        $key = $params['key'];
        $general_name = $params['general_name'];
        $singular_name = $params['singular_name'];
        $text_domain = $params['text-domain'];
        $capability_type = $params['capability_type'];
        $taxonomies = $params['taxonomies'];
        $icon = $params['icon'];
        $description = $params['description'];
        $theme = $params['theme'];
		$build_single = $params['build-single'] || false;
		$build_archive = $params['build-archive'] || false;

        if (!$key || !$general_name || !$singular_name || !$text_domain || !$capability_type || !$theme) {
            Dialog::write('Error: unable to create post type because of missing parameters.', 'red');
            exit;
        }

        $theme_path = PROJECT_ROOT . '/' . Config::expect('themes-path') . '/' . $theme;
        $post_type = new Template('post-type', [
            '{KEY}' => $key,
            '{GENERAL_NAME}' => $general_name,
            '{SINGULAR_NAME}' => $singular_name,
            '{TEXT_DOMAIN}' => $text_domain,
            '{CAPABILITY_TYPE}' => $capability_type,
            '{TAXONOMIES}' => $taxonomies,
            '{ICON}' => $icon,
            '{DESCRIPTION}' => $description
        ]);
		$saved = $post_type->save("$theme_path/includes/post-types/$key.php");
        Config::add("themes.$theme.post-types", $key);

		if ($saved) {
			Dialog::write("Post type '$key' added!", 'green');
		}

		if ($build_single) {
			SingleBuilder::build([
				'post_type' => $key,
				'theme' => $theme
			]);
		}

		if ($build_archive) {
			ArchiveBuilder::build([
				'type' => 'post-type',
				'key' => $key,
				'theme' => $theme
			]);
		}
    }

    /**
     * Asks for post type key
     * @return mixed
     */
    private static function askForKey()
    {
        $key = Dialog::getAnswer('Post type key (e.g. event):');

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
     * Asks for capability type
     * @return mixed
     */
    private static function askForCapabilityType()
    {
        return Dialog::getChoice('Capability type:', ['post', 'page'], null);
    }

    /**
     * Asks for taxonomies
     * @return array|mixed
     */
    private static function askForTaxonomies()
    {
        $taxonomies = Dialog::getAnswer('Taxonomies (comma separated):');
        $filter = function ($entry) {
            return StringsManager::toKebabCase($entry);
        };

        if ($taxonomies) {
            $taxonomies = array_map($filter, explode(',', $taxonomies));
            $taxonomies = implode(',', $taxonomies);
        }

        return $taxonomies;
    }

    /**
     * Asks for icon
     * @return mixed
     */
    private static function askForIcon()
    {
        $icon = Dialog::getAnswer('Icon [dashicons-admin-post]:', 'dashicons-admin-post');
        return StringsManager::toKebabCase($icon);
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
	 * Asks for single template auto-build confirmation
	 * @param $key
	 * @return mixed
	 */
    private static function askForSingleTemplateBuild($key)
	{
		return Dialog::getConfirmation("Do you want to automatically create a single template for '$key' post type?", true);
	}

	/**
	 * Asks for archive template auto-build confirmation
	 * @param $key
	 * @return mixed
	 */
	private static function askForArchiveTemplateBuild($key)
	{
		return Dialog::getConfirmation("Do you want to automatically create an archive template for '$key' post type?", true);
	}
}
