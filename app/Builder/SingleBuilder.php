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
        $theme = self::askForTheme(['template-engine']);
        $post_type = self::askForPostType($theme);
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
		$params = self::checkParams($params);
        $filename = 'single-' . $params['post_type'];
        $template_engine = Config::expect('themes.' . $params['theme'] . '.template-engine');
        $theme_path = PROJECT_ROOT . '/' . Config::expect('themes-path') . '/' . $params['theme'];
        $single_ctrl = new Template("$template_engine/single", ['{POST_TYPE}' => $params['post_type']]);
		$saved = true;

        if ($template_engine === 'timber') {
            $single_ctrl->fill('{VIEW_FILENAME}', $filename);
            $single_view = new Template('timber/view');
            $saved = $single_view->save("$theme_path/views/default/$filename.html.twig");
        }

        $saved = $saved && $single_ctrl->save("$theme_path/$filename.php");

		if ($saved) {
			Dialog::write("Single template for post type '" . $params['post_type'] . "' added!", 'green');
		}
    }

    /**
     * Asks for post type
	 * @param $theme
     * @return string
     */
    private static function askForPostType($theme)
    {
		$post_types = Config::expect("themes.$theme.post-types", 'array');
		$post_types = array_diff($post_types, ['post']);

		if (!empty($post_types)) {
			return Dialog::getChoice('Post type:', array_values($post_types), null);
		}

		Dialog::write('Error: before creating a single, you need to define a custom post type.', 'red');
		exit;
    }

	/**
	 * Checks params existence and normalizes them
	 * @param $params
	 * @return array
	 */
	private static function checkParams($params)
	{
		// checking existence
		if (!$params['post_type'] || !$params['theme']) {
			Dialog::write('Error: unable to create single template because of missing parameters.', 'red');
			exit;
		}

		// normalizing
		$post_type = StringsManager::toKebabCase($params['post_type']);
		$theme = StringsManager::toKebabCase($params['theme']);

		return [
			'post_type' => $post_type,
			'theme' => $theme
		];
	}
}
