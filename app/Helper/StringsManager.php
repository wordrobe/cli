<?php

namespace Wordrobe\Helper;

/**
 * Class StringsManager
 *
 * @package Wordrobe\Helper
 */
class StringsManager
{
  
  /**
   * Removes accented chars in a string and replaces them with relative no-accent version
   * @param $string
   * @return string
   */
  public static function removeAccents($string)
  {
    return iconv('UTF-8', 'ASCII//TRANSLIT', $string);
  }
  
  /**
   * Removes dashes from a string
   * @param $string
   * @param string $replacement
   * @return mixed
   */
  public static function removeDashes($string, $replacement = ' ')
  {
    return preg_replace('/[-_]/', $replacement, $string);
  }
  
  /**
   * Removes all spaces from a string
   * @param $string
   * @return string
   */
  public static function removeSpaces($string)
  {
    return trim(preg_replace('/\s/', '', $string));
  }
  
  /**
   * Replaces multiple spaces in a string with a single space
   * @param $string
   * @return string
   */
  public static function removeMultipleSpaces($string)
  {
    return trim(preg_replace('/\s+/', ' ', $string));
  }
  
  /**
   * Sanitizes a string
   * @param $string
   * @return string
   */
  public static function sanitize($string)
  {
    $string = self::removeAccents($string);
    $string = self::removeDashes($string);
    $string = self::removeMultipleSpaces($string);
    $string = preg_replace('/[^a-zA-Z0-9\.\s]/', '', $string);
    return strtolower($string);
  }
  
  /**
   * Formats a string in kebab-case
   * @param $string
   * @return mixed
   */
  public static function toKebabCase($string)
  {
    $sanitized = self::sanitize($string);
    return str_replace(' ', '-', $sanitized);
  }
  
  /**
   * Formats a string in snake_case
   * @param $string
   * @return mixed
   */
  public static function toSnakeCase($string)
  {
    $sanitized = self::sanitize($string);
    return str_replace(' ', '_', $sanitized);
  }
  
  /**
   * Formats a string in PascalCase
   * @param $string
   * @return mixed
   */
  public static function toPascalCase($string)
  {
    $sanitized = self::sanitize($string);
    return str_replace(' ', '', ucwords($sanitized));
  }
  
  /**
   * Extracts dirname from a string
   * @param $string
   * @return mixed
   */
  public static function extractDirname($string)
  {
    $pathinfo = pathinfo($string);
    return $pathinfo['dirname'];
  }
  
  /**
   * Extracts basename from a string
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
   * Extracts filename from a string
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
   * Extracts file extension from a string
   * @param $string
   * @return string
   */
  public static function extractFileExtension($string)
  {
    $pathinfo = pathinfo($string);
    return strtolower($pathinfo['extension']);
  }
}
