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
        $themes_path = self::askForThemesPath();
        self::build([
            'themes_path' => $themes_path
        ]);
    }

    /**
     * Builds config
     * @param array $params
     * @example ConfigBuilder::create([
     * 	'themes_path' => $themes_path
     * ]);
     */
    public static function build($params)
    {
        $themes_path = $params['themes_path'];

        if (!$themes_path) {
            Dialog::write('Error: unable to create config because of missing parameters.', 'red');
            exit;
        }

        Config::init(['{THEMES_PATH}' => $themes_path]);
        Dialog::write('Configuration completed!', 'green');
    }

    /**
     * Asks for themes path
     * @return mixed
     */
    private static function askForThemesPath()
    {
        $themes_path = Dialog::getAnswer('Please provide themes directory path (e.g. wp-content/themes):');

        if (!$themes_path) {
            return self::askForThemesPath();
        }

        return $themes_path;
    }
}
