<?php

namespace Wordrobe\Builder;

use Wordrobe\Config;
use Wordrobe\Helper\Dialog;
use Wordrobe\Helper\StringsManager;
use Wordrobe\Entity\Template;

class MenuBuilder extends TemplateBuilder implements Builder
{
  /**
   * Handles menu template creation wizard
   */
  public static function startWizard()
  {
    try {
      $theme = self::askForTheme();
      $location = self::askForLocation();
      $name = self::askForName($location);
      $description = self::askForDescription();
      self::build([
        'location' => $location,
        'name' => $name,
        '$description' => $description,
        'theme' => $theme,
        'override' => 'ask'
      ]);
      Dialog::write('Menu added!', 'green');
    } catch (\Exception $e) {
      Dialog::write($e->getMessage(), 'red');
      exit;
    }
  }
  
  /**
   * Builds menu template
   * @param array $params
   * @example MenuBuilder::create([
   *  'location' => $location,
   *  'name' => $name,
   *  '$description' => $description,
   *  'theme' => $theme,
   *  'override' => 'ask'|'force'|false
   * ]);
   * @throws \Exception
   */
  public static function build($params)
  {
    $params = self::checkParams($params);
    $filename = StringsManager::toKebabCase($params['location']);
    $theme_path = PROJECT_ROOT . '/' . Config::get('themes-path', true) . '/' . $params['theme'];
    $menu = new Template('menu', [
      '{LOCATION}' => $params['location'],
      '{NAME}' => $params['name'],
      '{DESCRIPTION}' => $params['description']
    ]);
    $menu->save("$theme_path/includes/menu/$filename.php", $params['override']);
  }
  
  /**
   * Asks for location
   * @return mixed
   */
  private static function askForLocation()
  {
    return Dialog::getAnswer('Location (e.g. main_menu):');
  }
  
  /**
   * Asks for name
   * @param string $location
   * @return mixed
   */
  private static function askForName($location)
  {
    $default = ucwords(StringsManager::removeDashes($location));
    return Dialog::getAnswer("Name [$default]:", $default);
  }
  
  /**
   * Asks for description
   * @return mixed
   */
  private static function askForDescription()
  {
    return Dialog::getAnswer('Description:');
  }
  
  /**
   * Checks params existence and normalizes them
   * @param array $params
   * @return mixed
   * @throws \Exception
   */
  private static function checkParams($params)
  {
    // checking existence
    if (!$params['location'] || !$params['name'] || !$params['theme']) {
      throw new \Exception('Error: unable to create menu because of missing parameters.');
    }
    
    // normalizing
    $location = StringsManager::toSnakeCase($params['location']);
    $name = ucwords($params['name']);
    $description = ucfirst($params['description']);
    $theme = StringsManager::toKebabCase($params['theme']);
    $override = strtolower($params['override']);
  
    if ($override !== 'ask' && $override !== 'force') {
      $override = false;
    }
  
    Config::check("themes.$theme", 'array', "Error: theme '$theme' doesn't exist.");
    
    return [
      'location' => $location,
      'name' => $name,
      '$description' => $description,
      'theme' => $theme,
      'override' => $override
    ];
  }
}
