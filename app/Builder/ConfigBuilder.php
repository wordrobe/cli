<?php

namespace Wordrobe\Builder;

use Wordrobe\Config;
use Wordrobe\Helper\Dialog;

/**
 * Class ConfigBuilder
 * @package Wordrobe\Builder
 */
class ConfigBuilder implements Builder
{
  /**
   * Handles config creation wizard
   */
  public static function startWizard()
  {
    try {
      $themes_path = self::askForThemesPath();
      $plugins_path = self::askForPluginsPath();
      self::build([
        'themes-path' => $themes_path,
        'plugins-path' => $plugins_path
      ]);
      Dialog::write('Configuration completed!', 'green');
    } catch (\Exception $e) {
      Dialog::write($e->getMessage(), 'red');
      exit;
    }
  }
  
  /**
   * Builds config
   * @param array $params
   * @example ConfigBuilder::create([
   *  'themes-path' => $themes_path
   *  'plugins-path' => $plugins_path
   * ]);
   */
  public static function build($params)
  {
    $params = self::checkParams($params);
    Config::init([
      '{THEMES_PATH}' => $params['themes_path'],
      '{PLUGINS_PATH}' => $params['plugins_path']
    ]);
  }
  
  /**
   * Asks for themes path
   * @return mixed
   */
  private static function askForThemesPath()
  {
    $themes_path = Dialog::getAnswer('Please provide themes directory path [wp-content/themes]:', 'wp-content/themes');
    return $themes_path ? $themes_path : self::askForThemesPath();
  }
  
  /**
   * Asks for plugin path
   * @return mixed
   */
  private static function askForPluginsPath()
  {
    $plugins_path = Dialog::getAnswer('Please provide plugins directory path [wp-content/plugins]:', 'wp-content/plugins');
    return $plugins_path ? $plugins_path : self::askForPluginsPath();
  }
  
  /**
   * Checks params existence and normalizes them
   * @param $params
   * @return mixed
   * @throws \Exception
   */
  private static function checkParams($params)
  {
    // checking existence
    if (!$params['themes-path'] || !$params['plugin-path']) {
      throw new \Exception('Error: unable to create config template because of missing parameters.');
    }
    
    return $params;
  }
}
