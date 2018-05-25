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
        $theme = self::askForTheme(['template_engine']);
        $type = self::askForType();
        $term = $type === 'post-type' ? self::askForPostType() : self::askForTerm();
        self::build([
            'type' => $type,
            'term' => $term,
            'theme' => $theme
        ]);
    }

    /**
     * Builds archive
     * @param array $params
     * @example ArchiveBuilder::create([
     *	'type' => $type,
     *	'term' => $term,
     *	'theme' => $theme
     * ]);
     */
    public static function build($params)
    {
        $type = $params['type'];
        $term = $params['term'];
        $theme = $params['theme'];

        if (!$type || !$term || !$theme) {
            Dialog::write('Error: unable to create archive because of missing parameters.', 'red');
            exit;
        }

        $basename = $type === 'post-type' ? 'archive' : $type;
        $filename = $term ? "$basename-$term" : $basename;
        $template_engine = Config::expect("themes.$theme.template_engine");
        $theme_path = PROJECT_ROOT . '/' . Config::expect('themes_path') . '/' . $theme;
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
