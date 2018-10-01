<?php

namespace Wordrobe\Builder;

use Wordrobe\Helper\Config;
use Wordrobe\Helper\Dialog;
use Wordrobe\Helper\StringsManager;
use Wordrobe\Entity\Template;

/**
 * Class MenuBuilder
 * @package Wordrobe\Builder
 */
class MenuBuilder extends TemplateBuilder implements WizardBuilder
{
  /**
   * Handles menu template build wizard
   */
  public static function startWizard()
  {
    try {
      $theme = self::askForTheme();
      $location = self::askForLocation();
      $name = self::askForName($location);
      $description = self::askForDescription($name);
      $text_domain = self::askForTextDomain($theme);
      self::build([
        'location' => $location,
        'name' => $name,
        'description' => $description,
        'text-domain' => $text_domain,
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
   * @example MenuBuilder::build([
   *  'location' => $location,
   *  'name' => $name,
   *  'description' => $description,
   *  'text-domain' => $text_domain,
   *  'theme' => $theme,
   *  'override' => 'ask'|'force'|false
   * ]);
   * @throws \Exception
   */
  public static function build($params)
  {
    $params = self::prepareParams($params);
    $menu = new Template('menu', [
      '{LOCATION}' => $params['location'],
      '{NAME}' => $params['name'],
      '{DESCRIPTION}' => $params['description'],
      '{TEXT_DOMAIN}' => $params['text-domain']
    ]);
    $menu->save($params['filepath'], $params['override']);
  }
  
  /**
   * Asks for location
   * @return mixed
   */
  private static function askForLocation()
  {
    return Dialog::getAnswer('Location (e.g. main-menu):');
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
  private static function askForDescription($name)
  {
    $default = ucwords(StringsManager::removeDashes($name));
    return Dialog::getAnswer("Description [$default]:", $default);
  }

  /**
   * Asks for text domain
   * @param string $theme
   * @return mixed
   * @throws \Exception
   */
  private static function askForTextDomain($theme)
  {
    $default = Config::get("themes.$theme.text-domain");
    return Dialog::getAnswer("Text domain [$default]:", $default);
  }
  
  /**
   * Checks params existence and normalizes them
   * @param array $params
   * @return mixed
   * @throws \Exception
   */
  private static function prepareParams($params)
  {
    // checking theme
    $theme = StringsManager::toKebabCase($params['theme']);
    Config::check("themes.$theme", 'array', "Error: theme '$theme' doesn't exist.");

    // checking params
    if (!$params['location'] || !$params['name'] || !$params['theme'] || !$params['description']) {
      throw new \Exception('Error: unable to create menu because of missing parameters.');
    }
    
    // normalizing
    $location = StringsManager::toKebabCase($params['location']);
    $name = ucwords($params['name']);
    $description = ucfirst($params['description']);
    $text_domain = $params['text-domain'] ? StringsManager::toKebabCase($params['text-domain']) : 'default';
    $override = strtolower($params['override']);
  
    if ($override !== 'ask' && $override !== 'force') {
      $override = false;
    }

    // paths
    $theme_path = Config::getRootPath() . '/' . Config::get('themes-path', true) . '/' . $theme;
    $filepath = "$theme_path/core/menu/$location.php";
    
    return [
      'location' => $location,
      'name' => $name,
      'description' => $description,
      'text-domain' => $text_domain,
      'filepath' => $filepath,
      'override' => $override,
      'theme' => $theme
    ];
  }
}
