<?php

namespace Wordress\Helper;

/**
 * Class Config
 * @package Wordress\Helper
 */
class Config
{

	private static $config;

	/**
	 * Project config file reader
	 */
	public static function read()
	{
		$rootPath = self::projectRootPath();
		if (file_exists($rootPath . 'wordress-config.json')) {
			self::$config = json_decode(file_get_contents($rootPath . 'wordress-config.json'), true);
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

	/**
	 * Project root path getter
	 *
	 * @return string
	 */
	public static function projectRootPath()
	{
		return realpath(__DIR__ . '/../../../../') . '/'; // Helper <-- app <-- wordress <-- vendor <-- root
	}
}