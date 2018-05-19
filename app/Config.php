<?php

namespace Wordrobe;

use Wordrobe\Entity\Template;

/**
 * Class Config
 * @package Wordrobe\Config
 */
class Config
{
	private static $template;
	private static $params;

	/**
	 * Initializes Config
	 * @param null $params
	 */
	public static function init($params = NULL)
	{
		if (!self::$template) {
			self::$template = new Template('project-config', $params);
			self::$params = json_decode(self::$template->getContent(), true);
			self::write();
		}
	}

	/**
	 * Gets Config param
	 * @param null $key
	 * @return mixed
	 */
	public static function get($key)
	{
		return self::$params[$key];
	}

	/**
	 * Set Config param
	 * @param $key
	 * @param $value
	 * @param null $parentKey
	 */
	public static function set($key, $value, $parentKey = NULL)
	{
		if ($parentKey) {
			self::$params[$parentKey][$key] = $value;
		} else {
			self::$params[$key] = $value;
		}
		self::write();
	}

	/**
	 * Writes Config file
	 */
	private static function write()
	{
		if (self::$template) {
			self::$template->setContent(json_encode(self::$params));
			self::$template->save(PROJECT_ROOT . '/wordrobe.json');
		}
	}
}