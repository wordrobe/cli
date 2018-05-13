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
		$string = iconv('UTF-8', 'ASCII//TRANSLIT', $string);
		$string = preg_replace('/\s+/', ' ', $string);
		$string = str_replace(' ', '_', $string);
		$string = preg_replace('/[^a-zA-Z0-9\-\._]/', '', $string);
		return strtolower($string);
	}

	/**
	 * @param $string
	 * @return string
	 */
	public static function dashesToSpace($string)
	{
		return str_replace(['_', '-'], ' ', $string);
	}

	/**
	 * @param $filename
	 * @return mixed
	 */
	public static function normalizeFilename($filename)
	{
		$filename = self::sanitize($filename);
		$filename = self::dashesToSpace($filename);
		return self::joinWordsBy($filename, '-');
	}

	/**
	 * @param $string
	 * @return mixed
	 */
	public static function toCamelCase($string)
	{
		$string = self::dashesToSpace($string);
		return str_replace(' ', '', ucwords($string));
	}

	/**
	 * @param $string
	 * @param $delimiter
	 * @return mixed
	 */
	public static function joinWordsBy($string, $delimiter)
	{
		$string = self::dashesToSpace($string);
		return str_replace(' ', $delimiter, $string);
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
		return self::normalizeFilename($basename);
	}

	/**
	 * @param $string
	 * @return mixed
	 */
	public static function extractFilename($string)
	{
		$pathinfo = pathinfo($string);
		$filename = $pathinfo['filename'];
		return self::normalizeFilename($filename);
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
