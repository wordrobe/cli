<?php

namespace Wordrobe\Builder;

use Wordrobe\Config;
use Wordrobe\Helper\Dialog;
use Wordrobe\Helper\StringsManager;
use Wordrobe\Entity\Template;

class PageBuilder extends TemplateBuilder implements Builder
{
    /**
     * Handles page template creation wizard
     */
    public static function startWizard()
    {
        $theme = self::askForTheme(['template-engine']);
        $name = self::askForName();
        self::build([
            'name' => $name,
            'theme' => $theme
        ]);
    }

    /**
     * Builds page template
     * @param array $params
     * @example PageBuilder::create([
     * 	'name' => $name,
     *	'theme' => $theme
     * ]);
     */
    public static function build($params)
    {
		$params = self::checkParams($params);
        $filename = StringsManager::toKebabCase($params['name']);
        $template_engine = Config::expect('themes.' . $params['theme'] . '.template-engine');
        $theme_path = PROJECT_ROOT . '/' . Config::expect('themes-path') . '/' . $params['theme'];
        $page_ctrl = new Template("$template_engine/page", ['{TEMPLATE_NAME}' => $params['name']]);
		$saved = true;

        if ($template_engine === 'timber') {
            $page_ctrl->fill('{VIEW_FILENAME}', $filename);
            $page_view = new Template('timber/view');
            $saved = $page_view->save("$theme_path/views/pages/$filename.html.twig");
        }

        $saved = $saved && $page_ctrl->save("$theme_path/pages/$filename.php");

		if ($saved) {
			Dialog::write("Page template '" . $params['name'] . "' added!", 'green');
		}
    }

    /**
     * Asks for page template name
     * @return string
     */
    private static function askForName()
    {
        $name = Dialog::getAnswer('Template name (e.g. My Custom Page):');

        if (!$name) {
            return self::askForName();
        }

        return ucwords($name);
    }

	/**
	 * Checks params existence and normalizes them
	 * @param $params
	 * @return array
	 */
	private static function checkParams($params)
	{
		// checking existence
		if (!$params['name'] || !$params['theme']) {
			Dialog::write('Error: unable to create page template because of missing parameters', 'red');
			exit;
		}

		// normalizing
		$name = ucwords($params['name']);
		$theme = StringsManager::toKebabCase($params['theme']);

		return [
			'name' => $name,
			'theme' => $theme
		];
	}
}
