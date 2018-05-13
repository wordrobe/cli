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
	 *
	 * @return mixed|null
	 */
	public static function read()
	{
		if (FilesManager::fileExists(Config::get('project_root') . '/' . self::FILENAME)) {
			if (!self::$config) {
				self::$config = json_decode(file_get_contents(Config::get('project_root') . '/' . self::FILENAME), true);
			}
			return self::$config;
		}
		return null;
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