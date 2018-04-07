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
        $boilerplatePath = realpath(__DIR__ . '/' . Config::get('template_engine'));
		if (!$boilerplatePath) {
			return false;
		}
        $command = 'cp -R ' . $boilerplatePath . '/* ' . $themePath . '/';
        exec($command, $out, $error);
        if ($error) {
            return false;
        }
        return true;
    }
}