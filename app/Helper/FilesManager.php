<?php

namespace Wordrobe\Helper;

/**
 * Class FilesManager
 *
 * @package Wordrobe\Helper
 */
class FilesManager {

	/**
	 * Checks file existence
	 * @param $filepath
	 * @return bool
	 */
	public static function fileExists($filepath)
	{
		return file_exists($filepath);
	}

	/**
	 * Checks directory existence
	 * @param $path
	 * @return bool
	 */
	public static function directoryExists($path)
	{
		return is_dir($path);
	}

	/**
	 * Asks for override confirmation
	 * @param $filepath
	 * @return mixed
	 */
	public static function getOverrideConfirmation($filepath)
	{
		return Dialog::getConfirmation('Attention: ' . $filepath . ' already exists! Do you want to override it?', false, 'red');
	}

	/**
	 * Handles directory creation
	 * @param $path
	 * @param int $mode
	 * @param bool $recursive
	 * @param bool $log
	 */
	public static function createDirectory($path, $mode = 0755, $recursive = true, $log = false)
	{
		if (!self::directoryExists($path)) {
			$created = mkdir($path, $mode, $recursive);
			if ($log) {
				if ($created) {
					Dialog::write('Done', 'green');
				} else {
					Dialog::write('Fail', 'red');
				}
			}
		}
	}

	/**
	 * Handles file write
	 * @param $filepath
	 */
	public static function writeFile($filepath, $content, $log = false)
	{
		if ($dirname = dirname($filepath)) {
			self::createDirectory($dirname);
		}
		$file = fopen($filepath, 'w');
		if ($log) {
			Dialog::write('Writing ' . $filepath . '...', 'yellow', false);
			if (fwrite($file, $content)) {
				fclose($file);
				Dialog::write('Done', 'green');
			} else {
				Dialog::write('Fail', 'red');
			}
		} else if (fwrite($file, $content)) {
			fclose($file);
		}
	}

	/**
	 * File contents getter
	 * @param $filepath
	 * @return null|string
	 */
	public static function readFile($filepath)
	{
		if (self::fileExists($filepath)) {
			return file_get_contents($filepath);
		}
		return null;
	}

	/**
	 * Handles file/directory permissions modification
	 * @param $path
	 * @param $mode
	 * @param bool $log
	 */
	public static function setPermissions($path, $mode, $log = false)
	{
		if ($log) {
			Dialog::write("Setting $path directory permissions... ", 'yellow', false);
		}
		$changed = chmod($path, $mode);
		if ($log) {
			if ($changed) {
				Dialog::write('Done', 'green');
			} else {
				Dialog::write('Fail', 'red');
			}
		}
	}

	/**
	 * Handles files copy
	 * @param $source
	 * @param $destination
	 * @param bool $log
	 * @param array $errors
	 */
	public static function copyFiles($source, $destination, $log = false, $errors = [])
	{
		$files = scandir($source);
		if ($log) {
			Dialog::write("Copying $source to $destination... ", 'yellow', false);
		}
		self::createDirectory($destination);
		foreach ($files as $file) {
			if ($file != '.' && $file != '..') {
				if (self::directoryExists("$source/$file")) {
					self::copyFiles("$source/$file", "$destination/$file", false, $errors);
				} else {
					$copied = copy("$source/$file", "$destination/$file");
					if (!$copied) {
						$errors[] = $file;
					}
				}
			}
		}
		if ($log) {
			if (count($errors)) {
				Dialog::write('Fail', 'red');
			} else {
				Dialog::write('Done', 'green');
			}
		}
	}
}