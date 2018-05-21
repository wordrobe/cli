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

	/**
	 * Checks Config existence
	 * @return bool
	 */
	public static function exists()
	{
		return FilesManager::fileExists(PROJECT_ROOT . '/' . self::FILENAME);
	}

	/**
	 * Initializes Config
	 * @param null $params
	 */
	public static function init($params = null)
	{
		if (!self::exists()) {
			$template = new Template('project-config', $params);
			$template->save(PROJECT_ROOT . '/' . self::FILENAME);
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
		$config = FilesManager::readFile(PROJECT_ROOT . '/' . self::FILENAME);

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
			FilesManager::writeFile(PROJECT_ROOT . '/' . self::FILENAME, json_encode($content, JSON_PRETTY_PRINT));
		} catch (\Exception $e) {
			Dialog::write($e->getMessage(), 'red');
			exit();
		}
	}
}