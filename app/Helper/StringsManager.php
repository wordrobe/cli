<?php

namespace Wordrobe\Helper;

/**
 * Class StringsManager
 *
 * @package Wordrobe\Helper
 */
class StringsManager {

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
	public static function normalize($string)
	{
		$string = self::sanitize($string);
		return str_replace(['_', '-'], ' ', $string);
	}

	/**
	 * @param $filename
	 * @return mixed
	 */
	public static function cleanFilename($filename)
	{
		$filename = self::extractFilename($filename);
		return self::toWordsJoinedBy($filename, '-');
	}

	/**
	 * @param $string
	 * @return mixed
	 */
	public static function toCamelCase($string)
	{
		$string = self::normalize($string);
		return str_replace(' ', '', ucwords($string));
	}

	/**
	 * @param $string
	 * @param $delimiter
	 * @return mixed
	 */
	public static function toWordsJoinedBy($string, $delimiter)
	{
		$string = self::normalize($string);
		return str_replace(' ', $delimiter, $string);
	}

	/**
	 * @param $string
	 * @return string
	 */
	public static function extractExtension($string)
	{
		$pathinfo = pathinfo($string);
		return $pathinfo['extension'];
	}

	/**
	 * @param $string
	 * @return mixed
	 */
	public static function extractBasename($string)
	{
		$pathinfo = pathinfo($string);
		return $pathinfo['basename'];
	}

	/**
	 * @param $string
	 * @return mixed
	 */
	public static function extractFilename($string)
	{
		$pathinfo = pathinfo($string);
		return $pathinfo['filename'];
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
}