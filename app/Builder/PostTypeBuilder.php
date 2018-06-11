<?php

namespace Wordrobe\Builder;

use Wordrobe\Config;
use Wordrobe\Helper\Dialog;
use Wordrobe\Helper\StringsManager;
use Wordrobe\Entity\Template;

class PostTypeBuilder extends TemplateBuilder implements Builder
{
  /**
   * Handles post type creation wizard
   */
  public static function startWizard()
  {
    try {
      $theme = self::askForTheme();
      $key = self::askForKey();
      $general_name = self::askForGeneralName($key);
      $singular_name = self::askForSingularName($general_name);
      $text_domain = self::askForTextDomain($theme);
      $capability_type = self::askForCapabilityType();
      $icon = self::askForIcon();
      $description = self::askForDescription();
      $build_single = self::askForSingleTemplateBuild($key);
      $build_archive = self::askForArchiveTemplateBuild($key);
      self::build([
        'key' => $key,
        'general-name' => $general_name,
        'singular-name' => $singular_name,
        'text-domain' => $text_domain,
        'capability-type' => $capability_type,
        'icon' => $icon,
        'description' => $description,
        'theme' => $theme,
        'build-single' => $build_single,
        'build-archive' => $build_archive,
        'override' => 'ask'
      ]);
      Dialog::write('Post type added!', 'green');
    } catch (\Exception $e) {
      Dialog::write($e->getMessage(), 'red');
      exit;
    }
  }
  
  /**
   * Builds post type
   * @param array $params
   * @example PostTypeBuilder::create([
   *  'key' => $key,
   *  'general-name' => $general_name,
   *  'singular-name' => $singular_name,
   *  'text-domain' => $text_domain,
   *  'capability-type' => $capability_type,
   *  'icon' => $icon,
   *  'description' => $description,
   *  'theme' => $theme,
   *  'build-single' => $build_single,
   *  'build-archive' => $build_archive,
   *  'override' => 'ask'|'force'|false
   * ]);
   * @throws \Exception
   */
  public static function build($params)
  {
    $params = self::checkParams($params);
    $theme_path = PROJECT_ROOT . '/' . Config::get('themes-path', true) . '/' . $params['theme'];
    $post_type = new Template('post-type', [
      '{KEY}' => $params['key'],
      '{GENERAL_NAME}' => $params['general-name'],
      '{SINGULAR_NAME}' => $params['singular-name'],
      '{TEXT_DOMAIN}' => $params['text-domain'],
      '{CAPABILITY_TYPE}' => $params['capability-type'],
      '{ICON}' => $params['icon'],
      '{DESCRIPTION}' => $params['description']
    ]);
    $post_type->save("$theme_path/includes/post-types/" . $params['key'] . ".php", $params['override']);
    Config::add('themes.' . $params['theme'] . '.post-types', $params['key']);
    
    if ($params['build-single']) {
      SingleBuilder::build([
        'post-type' => $params['key'],
        'theme' => $params['theme'],
        'override' => $params['override']
      ]);
    }
    
    if ($params['build-archive']) {
      ArchiveBuilder::build([
        'type' => 'post-type',
        'key' => $params['key'],
        'theme' => $params['theme'],
        'override' => $params['override']
      ]);
    }
  }
  
  /**
   * Asks for post type key
   * @return mixed
   */
  private static function askForKey()
  {
    $key = Dialog::getAnswer('Post type key (e.g. event):');
    return $key ? $key : self::askForKey();
  }
  
  /**
   * Asks for general name
   * @param string $key
   * @return string
   */
  private static function askForGeneralName($key)
  {
    $default = ucwords(str_replace('-', ' ', $key)) . 's';
    $general_name = Dialog::getAnswer("General name [$default]:", $default);
    return $general_name ?: self::askForGeneralName($key);
  }
  
  /**
   * Asks for singular name
   * @param string $general_name
   * @return string
   */
  private static function askForSingularName($general_name)
  {
    $default = substr($general_name, -1) === 's' ? substr($general_name, 0, -1) : $general_name;
    $singular_name = Dialog::getAnswer("Singular name [$default]:", $default);
    return $singular_name ?: self::askForSingularName($general_name);
  }
  
  /**
   * Asks for text domain
   * @param string $theme
   * @return mixed
   */
  private static function askForTextDomain($theme)
  {
    $text_domain = Dialog::getAnswer("Text domain [$theme]:", $theme);
    return $text_domain ?: self::askForTextDomain($theme);
  }
  
  /**
   * Asks for capability type
   * @return mixed
   */
  private static function askForCapabilityType()
  {
    return Dialog::getChoice('Capability type:', ['post', 'page'], null);
  }
  
  /**
   * Asks for icon
   * @return mixed
   */
  private static function askForIcon()
  {
    return Dialog::getAnswer('Icon [dashicons-admin-post]:', 'dashicons-admin-post');
  }
  
  /**
   * Asks for description
   * @return string
   */
  private static function askForDescription()
  {
    return Dialog::getAnswer('Description:');
  }
  
  /**
   * Asks for single template auto-build confirmation
   * @param string $key
   * @return mixed
   */
  private static function askForSingleTemplateBuild($key)
  {
    return Dialog::getConfirmation("Do you want to automatically create a single template for '$key' post type?", true, 'yellow');
  }
  
  /**
   * Asks for archive template auto-build confirmation
   * @param string $key
   * @return mixed
   */
  private static function askForArchiveTemplateBuild($key)
  {
    return Dialog::getConfirmation("Do you want to automatically create an archive template for '$key' post type?", true, 'yellow');
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
    if (!$params['key'] || !$params['general-name'] || !$params['singular-name'] || !$params['text-domain'] || !$params['capability-type'] || !$params['theme']) {
      throw new \Exception('Error: unable to create post type because of missing parameters.');
    }
    
    // normalizing
    $key = StringsManager::toKebabCase($params['key']);
    $general_name = ucwords($params['general-name']);
    $singular_name = ucwords($params['singular-name']);
    $text_domain = StringsManager::toKebabCase($params['text-domain']);
    $capability_type = strtolower($params['capability-type']);
    $icon = StringsManager::toKebabCase($params['icon']);
    $description = ucfirst($params['description']);
    $theme = StringsManager::toKebabCase($params['theme']);
    $build_single = $params['build-single'] || false;
    $build_archive = $params['build-archive'] || false;
    $override = strtolower($params['override']);
  
    if ($override !== 'ask' && $override !== 'force') {
      $override = false;
    }
  
    Config::check("themes.$theme", 'array', "Error: theme '$theme' doesn't exist.");
    
    return [
      'key' => $key,
      'general-name' => $general_name,
      'singular-name' => $singular_name,
      'text-domain' => $text_domain,
      'capability-type' => $capability_type,
      'icon' => $icon,
      'description' => $description,
      'theme' => $theme,
      'build-single' => $build_single,
      'build-archive' => $build_archive,
      'override' => $override
    ];
  }
}
