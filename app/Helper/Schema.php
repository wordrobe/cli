<?php

namespace Wordrobe\Helper;

/**
 * Class Schema
 * @package Wordrobe\Helper
 */
final class Schema
{
  const FILENAME = 'schema.json';

  /**
   * @var array $contents
   */
  private static $contents = [];

  /**
   * Adds Schema param
   * @param string $theme
   * @param string $path
   * @param array $data
   * @throws \Exception
   */
  public static function add($theme, $path, $data)
  {
    self::readContent($theme);
    ArraysManager::add(self::$contents, $path, $data);
    self::updateContent($theme);
  }

  /**
   * Reads Schema file contents
   * @param string $theme
   * @throws \Exception
   */
  private static function readContent($theme)
  {
    $content = FilesManager::readFile(Config::getThemePath($theme) . '/' . self::FILENAME);
    if ($content) {
      self::$contents = json_decode($content, true);
    } else {
      self::$contents = [];
    }
  }

  /**
   * Updates Schema file content
   * @param string $theme
   * @throws \Exception
   */
  private static function updateContent($theme)
  {
    FilesManager::writeFile(Config::getThemePath($theme) . '/' . self::FILENAME, json_encode(self::$contents, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES), true);
  }
}
