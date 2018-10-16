<?php

namespace Wordrobe\Builder;

use Wordrobe\Helper\Config;
use Wordrobe\Helper\Dialog;
use Wordrobe\Helper\StringsManager;
use Wordrobe\Entity\Template;

/**
 * Class TaxonomyBuilder
 * @package Wordrobe\Builder
 */
class TaxonomyBuilder extends TemplateBuilder implements WizardBuilder
{
  /**
   * Handles taxonomy build wizard
   */
  public static function startWizard()
  {
    try {
      $theme = self::askForTheme();
      $key = self::askForKey();
      $general_name = self::askForGeneralName($key);
      $singular_name = self::askForSingularName($general_name);
      $text_domain = self::askForTextDomain($theme);
      $post_type = self::askForPostType($theme);
      $hierarchical = self::askForHierarchy();
      $has_archive = Config::get("themes.$theme.post-types.$post_type.has-archive") ? self::askForArchiveTemplateBuild($key) : false;
      self::build([
        'key' => $key,
        'general-name' => $general_name,
        'singular-name' => $singular_name,
        'text-domain' => $text_domain,
        'post-type' => $post_type,
        'hierarchical' => $hierarchical,
        'has-archive' => $has_archive,
        'theme' => $theme,
        'override' => 'ask'
      ]);
      Dialog::write('Taxonomy added!', 'green');
    } catch (\Exception $e) {
      Dialog::write($e->getMessage(), 'red');
      exit;
    }
  }
  
  /**
   * Builds taxonomy template
   * @param array $params
   * @example TaxonomyBuilder::build([
   *  'key' => $key,
   *  'general-name' => $general_name,
   *  'singular-name' => $singular_name,
   *  'text-domain' => $text_domain,
   *  'post-type' => $post_type,
   *  'hierarchical' => $hierarchical,
   *  'theme' => $theme,
   *  'override' => 'ask'|'force'|false
   * ]);
   * @throws \Exception
   */
  public static function build($params)
  {
    $params = self::prepareParams($params);
    $taxonomy = new Template(
      $params['theme-path'] . '/core/taxonomies',
      'taxonomy',
      [
        '{KEY}' => $params['key'],
        '{GENERAL_NAME}' => $params['general-name'],
        '{SINGULAR_NAME}' => $params['singular-name'],
        '{TEXT_DOMAIN}' => $params['text-domain'],
        '{POST_TYPE}' => $params['post-type'],
        '{HIERARCHICAL}' => $params['hierarchical'] ? 'true' : 'false'
      ]
    );
    $taxonomy->save($params['filename'], $params['override']);

    Config::set($params['config-path'], ['post-type' => $params['post-type']]);
    
    if ($params['has-archive']) {
      ArchiveBuilder::build([
        'post-type' => $params['post-type'],
        'taxonomy' => $params['key'],
        'theme' => $params['theme'],
        'override' => $params['override']
      ]);
    }
  }
  
  /**
   * Asks for taxonomy key
   * @return mixed
   */
  private static function askForKey()
  {
    $key = Dialog::getAnswer('Taxonomy key (e.g. type):');
    return $key ?: self::askForKey();
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
   * Asks for post type
   * @param string $theme
   * @return string
   * @throws \Exception
   */
  private static function askForPostType($theme)
  {
    $post_types = array_diff_key(Config::get("themes.$theme.post-types", ['type' => 'array']), ['page']);
    $post_type = Dialog::getChoice('Post type:', array_keys($post_types), null);
    return $post_type ?: self::askForPostType($theme);
  }
  
  /**
   * Asks for hierarchy
   */
  private static function askForHierarchy()
  {
    return Dialog::getConfirmation('Is hierarchical?', true, 'blue');
  }

  /**
   * Asks for archive template auto-build confirmation
   * @param string $taxonomy
   * @return mixed
   */
  private static function askForArchiveTemplateBuild($taxonomy)
  {
    return Dialog::getConfirmation("Do you want to create a custom archive template for '$taxonomy' taxonomy?", true, 'yellow');
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
    if (!$params['key'] || !$params['general-name'] || !$params['singular-name'] || !$params['text-domain'] || !$params['post-type']) {
      throw new \Exception('Error: unable to create taxonomy because of missing parameters.');
    }

    // checking post type
    $post_type = StringsManager::toKebabCase($params['post-type']);
    Config::check("themes.$theme.post-types.$post_type", 'array', "Error: post type '$post_type' not found in '$theme' theme.");
    
    // normalizing
    $key = StringsManager::toKebabCase($params['key']);
    $general_name = ucwords($params['general-name']);
    $singular_name = ucwords($params['singular-name']);
    $text_domain = StringsManager::toKebabCase($params['text-domain']);
    $hierarchical = (bool) $params['hierarchical'];
    $has_archive = Config::get("themes.$theme.post-types.$post_type.has-archive") ? (bool) $params['has-archive'] : false;
    $override = strtolower($params['override']);
  
    if ($override !== 'ask' && $override !== 'force') {
      $override = false;
    }

    // paths
    $namespace = Config::get("themes.$theme.namespace", true);
    $config_path = "themes.$theme.taxonomies.$key";
    $theme_path = Config::getThemePath($theme, true);
    $filename = "$key.php";
    
    return [
      'key' => $key,
      'general-name' => $general_name,
      'singular-name' => $singular_name,
      'text-domain' => $text_domain,
      'post-type' => $post_type,
      'hierarchical' => $hierarchical,
      'namespace' => $namespace,
      'has-archive' => $has_archive,
      'config-path' => $config_path,
      'theme-path' => $theme_path,
      'filename' => $filename,
      'override' => $override,
      'theme' => $theme
    ];
  }
}
