<?php

namespace Wordrobe;

use Wordrobe\Entity\Template;
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
 * @return bool
 */
  public static function init($params = null)
  {
    $template = new Template('project-config', $params);
    return $template->save(self::FILEPATH);
  }

  /**
   * Gets Config param
   * @param $path
   * @return mixed|null
   */
  public static function get($path)
  {
    self::getContent();
    return ArraysManager::get(self::$params, $path);
  }

/**
 * Gets Config param strictly
 * @param $path
 * @param null $type
 * @return mixed|null
 * @throws \Exception
 */
  public static function expect($path, $type = null)
  {
    $param = self::get($path);

    if (($type && gettype($param) !== $type) || is_null($param) || (gettype($param) === 'string' && empty($param))) {
      throw new \Exception("Error: the required param '$path' is missing or invalid in " . self::FILEPATH . ". Please fix your configuration file in order to continue.");
    }

    return $param;
  }

  /**
   * Sets Config param
   * @param $path
   * @param $value
   * @return array
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
   */
  public static function add($path, $value)
  {
    self::getContent();
    ArraysManager::add(self::$params, $path, $value);
    self::updateContent();
  }

  /**
   * Gets Config file contents
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
   */
  private static function updateContent()
  {
    FilesManager::writeFile(self::FILEPATH, json_encode(self::$params, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES), true);
  }
}
