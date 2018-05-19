<?php

namespace Wordrobe;

use Wordrobe\Entity\Template;
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
	 * @param null $subkey
	 * @return mixed
	 */
	public static function get($key, $subkey = null)
	{
		if ($config = self::getContent()) {
			if ($subkey) {
				return $config[$key][$subkey];
			}
			return $config[$key];
		}
		return null;
	}

	/**
	 * Set Config param
	 * @param $key
	 * @param $value
	 * @param null $parentKey
	 */
	public static function set($key, $value, $parentKey = null)
	{
		if ($config = self::getContent()) {
			if ($parentKey) {
				$config[$parentKey][$key] = $value;
			} else {
				$config[$key] = $value;
			}
			self::setContent($config);
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
		FilesManager::writeFile(PROJECT_ROOT . '/' . self::FILENAME, json_encode($content, JSON_PRETTY_PRINT));
	}
}