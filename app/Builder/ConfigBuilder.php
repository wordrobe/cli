<?php

namespace Wordrobe\Builder;

use Wordrobe\Helper\Config;
use Wordrobe\Helper\Dialog;

/**
 * Class ConfigBuilder
 * @package Wordrobe\Builder
 */
class ConfigBuilder implements WizardBuilder
{
  /**
   * Handles config template build wizard
   */
  public static function startWizard()
  {
    try {
      $themes_path = self::askForThemesPath();
      self::build(['themes-path' => $themes_path]);
      Dialog::write('Configuration completed!', 'green');
    } catch (\Exception $e) {
      Dialog::write($e->getMessage(), 'red');
      exit;
    }
  }
  
  /**
   * Builds config template
   * @param array $params
   * @example ConfigBuilder::build([
   *  'themes-path' => $themes_path
   *  'plugins-path' => $plugins_path
   * ]);
   * @throws \Exception
   */
  public static function build($params)
  {
    $params = self::prepareParams($params);
    Config::init(['{THEMES_PATH}' => $params['themes-path']]);
  }
  
  /**
   * Asks for themes path
   * @return mixed
   */
  private static function askForThemesPath()
  {
    $themes_path = Dialog::getAnswer('Please provide themes directory path [wp-content/themes]:', 'wp-content/themes');
    return $themes_path ?: self::askForThemesPath();
  }
  
  /**
   * Checks params existence and normalizes them
   * @param array $params
   * @return mixed
   * @throws \Exception
   */
  private static function prepareParams($params)
  {
    // checking existence
    if (!$params['themes-path']) {
      throw new \Exception('Error: unable to create config template because of missing parameters.');
    }
    
    return $params;
  }
}
