<?php

namespace Wordrobe\Helper;

/**
 * Class ThemeManager
 * @package Wordrobe\Helper
 */
class ThemeManager {

	/**
	 * Copies theme boilerplate files
	 *
	 * @param $engine
	 * @param $path
	 */
	public static function copyBoilerplate($engine, $path)
	{
		$commonsFilesPath = BOILERPLATES_PATH . '/commons';
		$specificFilesPath = BOILERPLATES_PATH . '/' . $engine;
		if (FilesManager::directoryExists($specificFilesPath)) {
			FilesManager::copyFiles($commonsFilesPath, $path);
			FilesManager::copyFiles($specificFilesPath, $path);
		}
	}

}