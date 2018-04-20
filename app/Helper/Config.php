<?php

namespace Wordrobe\Helper;

/**
 * Class Config
 * @package Wordrobe\Helper
 */
class Config
{
	const FILENAME = 'wordrobe-config.json';
	private static $config;

	/**
	 * Project config file reader
	 */
	public static function read()
	{
		if (FilesManager::fileExists(PROJECT_ROOT . '/' . self::FILENAME)) {
			self::$config = json_decode(file_get_contents(PROJECT_ROOT . '/' . self::FILENAME), true);
		}
	}

	/**
	 * Project config getter
	 *
	 * @param $key
	 * @return bool|mixed
	 */
	public static function get($key = NULL)
	{
		if (self::$config) {
			if ($key) {
				return self::$config[$key];
			}
			return self::$config;
		}
		return false;
	}
}