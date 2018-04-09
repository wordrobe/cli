<?php

namespace Wordress\Helper;

/**
 * Class Boilerplate
 * @package Wordress\Helper
 */
class Boilerplate
{
    /**
     * Copies theme boilerplate files
     *
     * @return bool
     */
    public static function copy()
    {
		$themePath = Config::projectRootPath() . Config::get('themes_path') . Config::get('theme_name');
		$boilerplateCommonsPath = __DIR__ . '/commons';
        $boilerplateTypePath = __DIR__ . '/' . Config::get('template_engine');
		if (!$boilerplateTypePath) {
			return false;
		}
        $command = 'cp -R ' . $boilerplateCommonsPath . '/* ' . $themePath . '/ && cp -R ' . $boilerplateTypePath . '/* ' . $themePath . '/';
        exec($command, $out, $error);
        if ($error) {
            return false;
        }
        return true;
    }
}