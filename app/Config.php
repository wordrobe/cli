<?php

namespace Wordrobe;

use Wordrobe\Entity\Template;
use Wordrobe\Helper\Dialog;
use Wordrobe\Helper\FilesManager;

/**
 * Class Config
 * @package Wordrobe\Config
 */
class Config
{
	const FILENAME = 'wordrobe.json';
	const FILEPATH = PROJECT_ROOT . '/' . self::FILENAME;

	/**
	 * Checks Config existence
	 * @return bool
	 */
	public static function exists()
	{
		return FilesManager::fileExists(self::FILEPATH);
	}

	/**
	 * Initializes Config
	 * @param null $params
	 */
	public static function init($params = null)
	{
		if (!self::exists()) {
			$template = new Template('project-config', $params);
			$template->save(self::FILEPATH);
		}
	}

	/**
	 * Gets Config param
	 * @param $key
	 * @param null|string|array $ancestors
	 * @return mixed
	 */
	public static function get($key, $ancestors = null)
	{
		if ($config = self::getContent()) {

			if ($ancestors) {
				return self::getSubsetParam($config, $ancestors, $key);
			}

			return $config[$key];
		}
		return null;
	}

	/**
	 * Set Config param
	 * @param $key
	 * @param $value
	 * @param null|string|array $ancestors
	 */
	public static function set($key, $value, $ancestors = null)
	{
		if ($config = self::getContent()) {

			if ($ancestors) {
				self::setSubsetParam($config, $ancestors, $key, $value);
			} else {
				$config[$key] = $value;
			}

			self::setContent($config);
		}
	}

	/**
	 * Config subset params getter
	 * @param $config
	 * @param $subset
	 * @param $param
	 * @return mixed
	 */
	private static function getSubsetParam(&$config, $subset, $param)
	{
		if (is_array($subset)) {
			$subset = $config;

			foreach ($subset as $key) {
				$subset = $subset[$key];
			}

			return $subset[$param];
		}

		return $config[$subset][$param];
	}

	/**
	 * Config subset param setter
	 * @param $config
	 * @param $subset
	 * @param $param
	 * @param $value
	 * @return mixed
	 */
	private static function setSubsetParam(&$config, $subset, $param, $value)
	{
		if (is_array($subset)) {
			$subset = $config;

			foreach ($subset as $key) {
				$subset = $subset[$key];
			}

			$subset[$param] = $value;
		} else {
			$config[$subset][$param] = $value;
		}
	}

	/**
	 * Gets Config file contents
	 * @return mixed|null
	 */
	private static function getContent()
	{
		$config = FilesManager::readFile(self::FILEPATH);

		if ($config) {
			return json_decode($config, true);
		}

		return null;
	}

	/**
	 * Sets Config file content
	 * @param $content
	 */
	private static function setContent($content)
	{
		try {
			FilesManager::writeFile(self::FILEPATH, json_encode($content, JSON_PRETTY_PRINT), true);
		} catch (\Exception $e) {
			Dialog::write($e->getMessage(), 'red');
			exit();
		}

		Dialog::write(self::FILEPATH . ' updated!', 'cyan');
	}
}