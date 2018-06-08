<?php

namespace Wordrobe;

use Wordrobe\Wrapper\Template;
use Wordrobe\Helper\FilesManager;
use Wordrobe\Helper\ArraysManager;

/**
 * Class Config
 * @package Wordrobe\Config
 */
class Config
{
  const FILENAME = 'wordrobe.json';
  const FILEPATH = PROJECT_ROOT . '/' . self::FILENAME;

  private static $params = null;

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
 * @throws \Exception
 */
  public static function init($params = null)
  {
    $template = new Template('project-config', $params);
    $template->save(self::FILEPATH);
  }
  
  /**
   * Check Config param existence
   * @param $path
   * @param null $type
   * @param null $error
   * @return bool
   * @throws \Exception
   */
  public static function check($path, $type = null, $error = null)
  {
    $param = self::get($path);
    $message = $error ? $error : "Error: the required param '$path' is missing or invalid in " . self::FILEPATH . ". Please fix your configuration file in order to continue.";

    if (($type && gettype($param) !== $type) || is_null($param) || (gettype($param) === 'string' && empty($param))) {
      throw new \Exception($message);
    }

    return true;
  }
  
  /**
   * Gets Config param
   * @param $path
   * @param bool|array $strict
   * @return mixed|null
   * @throws \Exception
   */
  public static function get($path, $strict = false)
  {
    self::getContent();
    
    if ($strict === true) {
      self::check($path);
    } else if (is_array($strict) && !empty($strict)) {
      self::check($path, $strict['type'], $strict['error']);
    }
    
    return ArraysManager::get(self::$params, $path);
  }

  /**
   * Sets Config param
   * @param $path
   * @param $value
   * @return array
   * @throws \Exception
   */
  public static function set($path, $value)
  {
    self::getContent();
    ArraysManager::set(self::$params, $path, $value);
    self::updateContent();
  }

  /**
   * Adds Config param
   * @param $path
   * @param $value
   * @return array|null
   * @throws \Exception
   */
  public static function add($path, $value)
  {
    self::getContent();
    ArraysManager::add(self::$params, $path, $value);
    self::updateContent();
  }

  /**
   * Calculates relative root path from given path
   * @param $from_path
   * @return string
   */
  public static function getRelativeRootPath($from_path)
  {
    $subdirs = explode('/', $from_path);
    $root_path = '';

    for ($i = 0; $i < count($subdirs) - 1; $i++) {
      $root_path .= '../';
    }

    return $root_path;
  }

  /**
   * Gets Config file contents
   * @throws \Exception
   */
  private static function getContent()
  {
    $content = FilesManager::readFile(self::FILEPATH);
    if ($content) {
      self::$params = json_decode($content, true);
    } else {
      self::$params = null;
    }
  }

  /**
   * Updates Config file content
   * @throws \Exception
   */
  private static function updateContent()
  {
    FilesManager::writeFile(self::FILEPATH, json_encode(self::$params, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES), true);
  }
}
