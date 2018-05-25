<?php

namespace Wordrobe\Builder;

use Wordrobe\Config;
use Wordrobe\Helper\Dialog;
use Wordrobe\Helper\StringsManager;
use Wordrobe\Entity\Template;

class SingleBuilder extends TemplateBuilder implements Builder
{
    /**
     * Handles single template creation wizard
     */
    public static function startWizard()
    {
        $theme = self::askForTheme(['template_engine']);
        $post_type = self::askForPostType();
        self::build([
            'post_type' => $post_type,
            'theme' => $theme
        ]);
    }

    /**
     * Builds single template
     * @param array $params
     * @example SingleBuilder::create([
     * 	'post_type' => $post_type,
     *	'theme' => $theme
     * ]);
     */
    public static function build($params)
    {
        $post_type = $params['post_type'];
        $theme = $params['theme'];

        if (!$post_type || !$theme) {
            Dialog::write('Error: unable to create single template because of missing parameters.', 'red');
            exit;
        }

        $filename = "single-$post_type";
        $template_engine = Config::expect("themes.$theme.template_engine");
        $theme_path = PROJECT_ROOT . '/' . Config::expect('themes_path') . '/' . $theme;
        $single_ctrl = new Template("$template_engine/single", ['{POST_TYPE}' => $post_type]);

        if ($template_engine === 'timber') {
            $single_ctrl->fill('{VIEW_FILENAME}', $filename);
            $single_view = new Template('timber/view');
            $single_view->save("$theme_path/views/default/$filename.html.twig");
        }

        $single_ctrl->save("$theme_path/$filename.php");
        Dialog::write("Single template for post type '$post_type' added!", 'green');
    }

    /**
     * Asks for post type
     * @return string
     */
    private static function askForPostType()
    {
        $post_type = Dialog::getAnswer('Post type (e.g. event):');

        if (!$post_type) {
            return self::askForPostType();
        }

        return StringsManager::toKebabCase($post_type);
    }
}
