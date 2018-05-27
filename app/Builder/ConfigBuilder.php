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
            'themes-path' => $themes_path
        ]);
    }

    /**
     * Builds config
     * @param array $params
     * @example ConfigBuilder::create([
     * 	'themes-path' => $themes_path
     * ]);
     */
    public static function build($params)
    {
		$params = self::checkParams($params);
        $completed = Config::init(['{THEMES_PATH}' => $params['themes_path']]);

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
        $themes_path = Dialog::getAnswer('Please provide themes directory path [wp-content/themes]:', 'wp-content/themes');

        if (!$themes_path) {
            return self::askForThemesPath();
        }

        return $themes_path;
    }

	/**
	 * Checks params existence
	 * @param $params
	 * @return mixed
	 */
    private static function checkParams($params)
	{
		// checking existence
		if (!$params['themes-path']) {
			Dialog::write('Error: unable to create config because of missing parameters.', 'red');
			exit;
		}

		return $params;
	}
}
