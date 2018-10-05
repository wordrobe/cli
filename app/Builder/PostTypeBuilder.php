<?php

namespace Wordrobe\Builder;

use Wordrobe\Helper\Config;
use Wordrobe\Helper\Dialog;
use Wordrobe\Helper\StringsManager;
use Wordrobe\Entity\Template;

/**
 * Class PostTypeBuilder
 * @package Wordrobe\Builder
 */
class PostTypeBuilder extends TemplateBuilder implements WizardBuilder
{
  /**
   * Handles post type build wizard
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
      $public = self::askForPublicity();
      $has_archive = $capability_type === 'post' && $public ? self::askForArchive() : false;
      $icon = self::askForIcon();
      $description = self::askForDescription();
      self::build([
        'key' => $key,
        'general-name' => $general_name,
        'singular-name' => $singular_name,
        'text-domain' => $text_domain,
        'capability-type' => $capability_type,
        'public' => $public,
        'has-archive' => $has_archive,
        'icon' => $icon,
        'description' => $description,
        'theme' => $theme,
        'override' => 'ask'
      ]);
      Dialog::write('Post type added!', 'green');
    } catch (\Exception $e) {
      Dialog::write($e->getMessage(), 'red');
      exit;
    }
  }
  
  /**
   * Builds post type template
   * @param array $params
   * @example PostTypeBuilder::build([
   *  'key' => $key,
   *  'general-name' => $general_name,
   *  'singular-name' => $singular_name,
   *  'text-domain' => $text_domain,
   *  'capability-type' => $capability_type,
   *  'public' => $public,
   *  'has-archive' => $has_archive,
   *  'icon' => $icon,
   *  'description' => $description,
   *  'theme' => $theme,
   *  'override' => 'ask'|'force'|false
   * ]);
   * @throws \Exception
   */
  public static function build($params)
  {
    $params = self::prepareParams($params);
    $post_type = new Template('post-type', [
      '{KEY}' => $params['key'],
      '{GENERAL_NAME}' => $params['general-name'],
      '{SINGULAR_NAME}' => $params['singular-name'],
      '{DESCRIPTION}' => $params['description'],
      '{REWRITE}' => $params['rewrite'],
      '{TEXT_DOMAIN}' => $params['text-domain'],
      '{CAPABILITY_TYPE}' => $params['capability-type'],
      '{PUBLIC}' => $params['public'] ? 'true' : 'false',
      '{HIERARCHICAL}' => $params['hierarchical'] ? 'true' : 'false',
      '{HAS_ARCHIVE}' => $params['has-archive'],
      '{ICON}' => $params['icon'],
      '{SUPPORTS}' => $params['supports']
    ]);
    $post_type->save($params['filepath'], $params['override']);

    Config::set($params['config-path'], [
      'capability-type' => $params['capability-type'],
      'has-archive' => (bool) $params['has-archive']
    ]);

    DTOBuilder::build([
      'entity-name' => $params['entity-name'],
      'theme' => $params['theme'],
      'override' => $params['override']
    ]);

    EntityBuilder::build([
      'name' => $params['entity-name'],
      'theme' => $params['theme'],
      'override' => $params['override']
    ]);

    RepositoryBuilder::build([
      'post-type' => $params['key'],
      'entity-name' => $params['entity-name'],
      'theme' => $params['theme'],
      'override' => $params['override']
    ]);

    if ($params['public']) {
      SingleBuilder::build([
        'post-type' => $params['key'],
        'entity-name' => $params['entity-name'],
        'theme' => $params['theme'],
        'override' => $params['override']
      ]);

      if ($params['has-archive']) {
        ArchiveBuilder::build([
          'type' => 'post-type',
          'key' => $params['key'],
          'entity-name' => $params['entity-name'],
          'theme' => $params['theme'],
          'override' => $params['override']
        ]);
      }
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
   * @throws \Exception
   */
  private static function askForTextDomain($theme)
  {
    $default = Config::get("themes.$theme.text-domain");
    return Dialog::getAnswer("Text domain [$default]:", $default);
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
   * Asks for publicity
   * @return bool
   */
  private static function askForPublicity()
  {
    return Dialog::getConfirmation('Is post type public?', true, 'yellow');
  }

  /**
   * Asks for archive
   * @return bool
   */
  private static function askForArchive()
  {
    return Dialog::getConfirmation('Has archive?', true, 'yellow');
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
    if (!$params['key'] || !$params['general-name'] || !$params['singular-name'] || !$params['text-domain'] || !$params['capability-type'] || !$params['theme']) {
      throw new \Exception('Error: unable to create post type because of missing parameters.');
    }
    
    // normalizing
    $key = StringsManager::toKebabCase($params['key']);
    $entity_name = StringsManager::toPascalCase($params['key']);
    $general_name = ucwords($params['general-name']);
    $singular_name = ucwords($params['singular-name']);
    $text_domain = StringsManager::toKebabCase($params['text-domain']);
    $capability_type = strtolower($params['capability-type']);
    $public = $params['public'];
    $hierarchical = $params['capability-type'] === 'page';
    $has_archive = $public && !$hierarchical && $params['has-archive'] ? 'true' : 'false';
    $icon = StringsManager::toKebabCase($params['icon']);
    $description = ucfirst($params['description']);
    $rewrite = $public ? '["slug" => "' . StringsManager::toKebabCase($params['general-name']) . '", "with_front" => false]' : false;
    $supports = '["title", "editor", "author", "thumbnail", "excerpt", "trackbacks", "custom-fields", "comments", "revisions", "post-formats", "page-attributes"]';
    $override = strtolower($params['override']);
  
    if ($override !== 'ask' && $override !== 'force') {
      $override = false;
    }

    // paths
    $theme_path = Config::getRootPath() . '/' . Config::get('themes-path', true) . '/' . $theme;
    $filepath = "$theme_path/core/post-types/$key.php";
    $config_path = "themes.$theme.post-types.$key";
    
    return [
      'key' => $key,
      'entity-name' => $entity_name,
      'general-name' => $general_name,
      'singular-name' => $singular_name,
      'text-domain' => $text_domain,
      'capability-type' => $capability_type,
      'public' => $public,
      'has-archive' => $has_archive,
      'hierarchical' => $hierarchical,
      'icon' => $icon,
      'description' => $description,
      'rewrite' => $rewrite,
      'supports' => $supports,
      'filepath' => $filepath,
      'override' => $override,
      'config-path' => $config_path,
      'theme' => $theme
    ];
  }
}
