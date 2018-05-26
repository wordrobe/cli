<?php

namespace Wordrobe\Builder;

use Wordrobe\Config;
use Wordrobe\Helper\Dialog;

/**
 * Class ConfigBuilder
 * @package Wordrobe\Builder
 */
class ConfigBuilder implements Builder
{
    /**
     * Handles config creation wizard
     */
    public static function startWizard()
    {
        $themes-path = self::askForThemesPath();
        self::build([
            'themes-path' => $themes-path
        ]);
    }

    /**
     * Builds config
     * @param array $params
     * @example ConfigBuilder::create([
     * 	'themes-path' => $themes-path
     * ]);
     */
    public static function build($params)
    {
        $themes-path = $params['themes-path'];

        if (!$themes-path) {
            Dialog::write('Error: unable to create config because of missing parameters.', 'red');
            exit;
        }

        $completed = Config::init(['{THEMES_PATH}' => $themes-path]);

		if ($completed) {
			Dialog::write('Configuration completed!', 'green');
		}
    }

    /**
     * Asks for themes path
     * @return mixed
     */
    private static function askForThemesPath()
    {
        $themes-path = Dialog::getAnswer('Please provide themes directory path [wp-content/themes]:', 'wp-content/themes');

        if (!$themes-path) {
            return self::askForThemesPath();
        }

        return $themes-path;
    }
}
