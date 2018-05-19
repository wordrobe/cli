<?php

namespace Wordrobe\Helper;

/**
 * Class StringsManager
 *
 * @package Wordrobe\Helper
 */
class StringsManager {

	/**
	 * @param $string
	 * @return string
	 */
	public static function sanitize($string)
	{
		$string = iconv('UTF-8', 'ASCII//TRANSLIT', $string); // parsing accented chars
		$string = preg_replace('/[-_]/', ' ', $string); // replacing dashes with space
		$string = preg_replace('/\s+/', ' ', $string); // removing double spaces
		$string = preg_replace('/[^a-zA-Z0-9\.\s]/', '', $string); // removing other symbols
		$string = trim($string);
		return strtolower($string);
	}

	/**
	 * @param $string
	 * @return mixed
	 */
	public static function toKebabCase($string)
	{
		$sanitized = self::sanitize($string);
		return str_replace(' ', '-', $sanitized);
	}

	/**
	 * @param $string
	 * @return mixed
	 */
	public static function toSnakeCase($string)
	{
		$sanitized = self::sanitize($string);
		return str_replace(' ', '_', $sanitized);
	}

	/**
	 * @param $string
	 * @return mixed
	 */
	public static function toPascalCase($string)
	{
		$sanitized = self::sanitize($string);
		return str_replace(' ', '', ucwords($sanitized));
	}

	/**
	 * @param $string
	 * @return mixed
	 */
	public static function extractDirname($string)
	{
		$pathinfo = pathinfo($string);
		return $pathinfo['dirname'];
	}

	/**
	 * @param $string
	 * @return mixed
	 */
	public static function extractBasename($string)
	{
		$pathinfo = pathinfo($string);
		$basename = $pathinfo['basename'];
		return self::toKebabCase($basename);
	}

	/**
	 * @param $string
	 * @return mixed
	 */
	public static function extractFilename($string)
	{
		$pathinfo = pathinfo($string);
		$filename = $pathinfo['filename'];
		return self::toKebabCase($filename);
	}

	/**
	 * @param $string
	 * @return string
	 */
	public static function extractFileExtension($string)
	{
		$pathinfo = pathinfo($string);
		return strtolower($pathinfo['extension']);
	}
}
