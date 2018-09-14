<?php

namespace Wordrobe\Builder;

use Wordrobe\Helper\Config;
use Wordrobe\Helper\Dialog;
use Wordrobe\Helper\StringsManager;
use Wordrobe\Entity\Template;

class TaxonomyBuilder extends TemplateBuilder implements Builder
{
  /**
   * Handles taxonomy creation wizard
   */
  public static function startWizard()
  {
    try {
      $theme = self::askForTheme();
      $key = self::askForKey();
      $general_name = self::askForGeneralName($key);
      $singular_name = self::askForSingularName($general_name);
      $text_domain = self::askForTextDomain($theme);
      $post_types = self::askForPostTypes($theme);
      $hierarchical = self::askForHierarchy();
      $build_archive = self::askForArchiveTemplateBuild($key);
      self::build([
        'key' => $key,
        'general-name' => $general_name,
        'singular-name' => $singular_name,
        'text-domain' => $text_domain,
        'post-types' => $post_types,
        'hierarchical' => $hierarchical,
        'theme' => $theme,
        'build-archive' => $build_archive,
        'override' => 'ask'
      ]);
      Dialog::write('Taxonomy added!', 'green');
    } catch (\Exception $e) {
      Dialog::write($e->getMessage(), 'red');
      exit;
    }
  }
  
  /**
   * Builds taxonomy
   * @param array $params
   * @example TaxonomyBuilder::create([
   *  'key' => $key,
   *  'general-name' => $general_name,
   *  'singular-name' => $singular_name,
   *  'text-domain' => $text_domain,
   *  'post-types' => $post_types,
   *  'hierarchical' => $hierarchical,
   *  'theme' => $theme,
   *  'build-archive' => $build_archive,
   *  'override' => 'ask'|'force'|false
   * ]);
   * @throws \Exception
   */
  public static function build($params)
  {
    $params = self::checkParams($params);
    $theme_path = Config::getRootPath() . '/' . Config::get('themes-path', true) . '/' . $params['theme'];
    $taxonomy = new Template('taxonomy', [
      '{KEY}' => $params['key'],
      '{GENERAL_NAME}' => $params['general-name'],
      '{SINGULAR_NAME}' => $params['singular-name'],
      '{TEXT_DOMAIN}' => $params['text-domain'],
      '{POST_TYPES}' => $params['post-types'],
      '{HIERARCHICAL}' => $params['hierarchical']
    ]);
    $taxonomy->save("$theme_path/includes/taxonomies/" . $params['key'] . ".php", $params['override']);
    Config::set('themes.' . $params['theme'] . '.taxonomies.' . $params['key'], [
      'post-types' => explode(',', $params['post-types']),
      'hierarchical' => $params['hierarchical'],
      'terms' => []
    ]);
    
    if ($params['build-archive']) {
      ArchiveBuilder::build([
        'type' => 'taxonomy',
        'key' => $params['key'],
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
   * @param $theme
   * @return mixed
   * @throws \Exception
   */
  private static function askForTextDomain($theme)
  {
    $default = Config::get("themes.$theme.text-domain");
    return Dialog::getAnswer("Text domain [$default]:", $default);
  }
  
  /**
   * Asks for post types
   * @param string $theme
   * @return string
   * @throws \Exception
   */
  private static function askForPostTypes($theme)
  {
    $post_types = Dialog::getChoice('Post types:', Config::get("themes.$theme.post-types", ['type' => 'array']), null, true);
    return implode(',', $post_types);
    
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
   * @param string $key
   * @return mixed
   */
  private static function askForArchiveTemplateBuild($key)
  {
    return Dialog::getConfirmation("Do you want to automatically create an archive template for '$key' taxonomy?", true, 'yellow');
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
    if (!$params['key'] || !$params['general-name'] || !$params['singular-name'] || !$params['text-domain'] || !$params['post-types'] || !$params['theme']) {
      throw new \Exception('Error: unable to create taxonomy because of missing parameters.');
    }
    
    // normalizing
    $key = StringsManager::toKebabCase($params['key']);
    $general_name = ucwords($params['general-name']);
    $singular_name = ucwords($params['singular-name']);
    $text_domain = StringsManager::toKebabCase($params['text-domain']);
    $post_types = strtolower(StringsManager::removeSpaces($params['post-types']));
    $hierarchical = $params['hierarchical'] ? 1 : 0;
    $theme = StringsManager::toKebabCase($params['theme']);
    $build_archive = $params['build-archive'] || false;
    $override = strtolower($params['override']);
  
    if ($override !== 'ask' && $override !== 'force') {
      $override = false;
    }
  
    Config::check("themes.$theme", 'array', "Error: theme '$theme' doesn't exist.");
    
    foreach (explode(',', $post_types) as $post_type) {
      if (!in_array($post_type, Config::get("themes.$theme.post-types", ['type' => 'array']))) {
        throw new \Exception("Error: post type '$post_type' not found in '$theme' theme.");
      }
    }
    
    return [
      'key' => $key,
      'general-name' => $general_name,
      'singular-name' => $singular_name,
      'text-domain' => $text_domain,
      'post-types' => $post_types,
      'hierarchical' => $hierarchical,
      'theme' => $theme,
      'build-archive' => $build_archive,
      'override' => $override
    ];
  }
}
