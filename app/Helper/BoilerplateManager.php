<?php

namespace Wordrobe\Helper;

/**
 * Class BoilerplateManager
 * @package Wordrobe\Helper
 */
class BoilerplateManager
{
    /**
     * Copies theme boilerplate files
     *
     * @return bool
     */
    public static function copyFiles()
    {
		$themePath = PROJECT_ROOT . '/' . Config::get('theme_root');
		$commonsFilesPath = __DIR__ . '/commons';
        $specificFilesPath = __DIR__ . '/' . Config::get('template_engine');
		if (FilesManager::directoryExists($specificFilesPath)) {
			FilesManager::copyFiles($commonsFilesPath, $themePath);
			FilesManager::copyFiles($specificFilesPath, $themePath);
		}
    }
}