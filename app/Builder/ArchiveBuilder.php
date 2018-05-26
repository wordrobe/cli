<?php

namespace Wordrobe\Builder;

use Wordrobe\Config;
use Wordrobe\Helper\Dialog;
use Wordrobe\Entity\Template;
use Wordrobe\Helper\StringsManager;

/**
 * Class ConfigBuilder
 * @package Wordrobe\Builder
 */
class ArchiveBuilder extends TemplateBuilder implements Builder
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
        $theme = self::askForTheme(['template-engine']);
        $type = self::askForType();

		switch ($type) {
			case 'post-type':
				$key = self::askForPostType($theme);
				break;
			case 'taxonomy':
				$key = self::askForTaxonomy($theme);
				break;
			default:
				$key = self::askForTerm();
				break;
		}

        self::build([
            'type' => $type,
            'key' => $key,
            'theme' => $theme
        ]);
    }

    /**
     * Builds archive
     * @param array $params
     * @example ArchiveBuilder::create([
     *	'type' => $type,
     *	'key' => $key,
     *	'theme' => $theme
     * ]);
     */
    public static function build($params)
    {
        $type = $params['type'];
        $key = $params['key'];
        $theme = $params['theme'];

        if (!$type || !$key || !$theme) {
            Dialog::write('Error: unable to create archive because of missing parameters.', 'red');
            exit;
        }

        $basename = $type === 'post-type' ? 'archive' : $type;
        $filename = $key ? "$basename-$key" : $basename;
        $template_engine = Config::expect("themes.$theme.template-engine");
        $theme_path = PROJECT_ROOT . '/' . Config::expect('themes-path') . '/' . $theme;
        $type_and_key = trim(str_replace("''", '', "$type '$key'"));
        $archive_ctrl = new Template("$template_engine/archive", ['{TYPE_AND_KEY}' => $type_and_key]);

        if ($template_engine === 'timber') {
            $archive_ctrl->fill('{VIEW_FILENAME}', $filename);
            $archive_view = new Template('timber/view');
            $archive_view->save("$theme_path/views/default/$filename.html.twig");
        }

        $saved = $archive_ctrl->save("$theme_path/$filename.php");

		if ($saved) {
			Dialog::write("Archive template for $type_and_key added!", 'green');
		}
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
	 * @param $theme
     * @return mixed
     */
    private static function askForPostType($theme)
    {
    	$post_types = Config::expect("themes.$theme.post-types", 'array');
		$post_types = array_diff($post_types, ['post']);

		if (!empty($post_types)) {
			return Dialog::getChoice('Post type:', $post_types, null);
		}

		Dialog::write('Error: before creating a post-type based archive, you need to define a custom post type.', 'red');
		exit;
    }

	/**
	 * Asks for taxonomy
	 * @param $theme
	 * @return mixed
	 */
	private static function askForTaxonomy($theme)
	{
		$taxonomies = Config::expect("themes.$theme.taxonomies", 'array');
		$taxonomies = array_diff($taxonomies, ['category', 'tag']);

		if (!empty($taxonomies)) {
			return Dialog::getChoice('Taxonomy:', $taxonomies, null);
		}

		Dialog::write('Error: before creating a taxonomy based archive, you need to define a custom taxonomy.', 'red');
		exit;
	}

    /**
     * Asks for term
     * @return mixed
     */
    private static function askForTerm()
    {
		$term = Dialog::getAnswer('Term:');

		if (!$term) {
			return self::askForTerm();
		}

		return StringsManager::toKebabCase($term);
    }
}
