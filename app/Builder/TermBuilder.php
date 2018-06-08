<?php

namespace Wordrobe\Builder;

use Wordrobe\Config;
use Wordrobe\Helper\Dialog;
use Wordrobe\Helper\StringsManager;
use Wordrobe\Wrapper\Template;

class TermBuilder extends TemplateBuilder implements Builder
{
  /**
   * Handles term creation wizard
   */
  public static function startWizard()
  {
    try {
      $theme = self::askForTheme();
      $name = self::askForName();
      $taxonomy = self::askForTaxonomy($theme);
      $slug = self::askForSlug($name);
      $description = self::askForDescription();
      $parent = self::askForParent();
      $build_archive = self::askForArchiveTemplateBuild($slug);
      self::build([
        'name' => $name,
        'taxonomy' => $taxonomy,
        'slug' => $slug,
        'description' => $description,
        'parent' => $parent,
        'theme' => $theme,
        'build-archive' => $build_archive,
        'override' => 'ask'
      ]);
      Dialog::write('Term added!', 'green');
    } catch (\Exception $e) {
      Dialog::write($e->getMessage(), 'red');
      exit;
    }
  }
  
  /**
   * Builds term
   * @param array $params
   * @example TermBuilder::create([
   *  'name' => $name,
   *  'taxonomy' => $taxonomy,
   *  'slug' => $slug,
   *  'description' => $description,
   *  'parent' => $parent,
   *  'theme' => $theme,
   *  'build-archive' => $build_archive
   *  'override' => 'ask'|'force'|false
   * ]);
   * @throws \Exception
   */
  public static function build($params)
  {
    $params = self::checkParams($params);
    $theme_path = PROJECT_ROOT . '/' . Config::get('themes-path', true) . '/' . $params['theme'];
    $term = new Template('term', [
      '{NAME}' => $params['name'],
      '{TAXONOMY}' => $params['taxonomy'],
      '{SLUG}' => $params['slug'],
      '{DESCRIPTION}' => $params['description'],
      '{PARENT}' => $params['parent']
    ]);
    $term->save("$theme_path/includes/terms/" . $params['taxonomy'] . "/" . $params['slug'] . ".php", $params['override']);
    
    if ($params['build-archive']) {
      
      if ($params['taxonomy'] === 'category' || $params['taxonomy'] === 'tag') {
        $type = $params['taxonomy'];
        $key = $params['slug'];
      } else {
        $type = 'taxonomy';
        $key = $params['taxonomy'] . '-' . $params['slug'];
      }
      
      ArchiveBuilder::build([
        'type' => $type,
        'key' => $key,
        'theme' => $params['theme'],
        'override' => $params['override']
      ]);
    }
  }
  
  /**
   * Asks for term name
   * @return string
   */
  private static function askForName()
  {
    $name = Dialog::getAnswer('Term name (e.g. Entertainment):');
    return $name ?: self::askForName();
  }
  
  /**
   * Asks for taxonomy
   * @param $theme
   * @return mixed
   * @throws \Exception
   */
  private static function askForTaxonomy($theme)
  {
    $taxonomies = Config::get("themes.$theme.taxonomies", ['type' => 'array']);
    return Dialog::getChoice('Taxonomy:', $taxonomies, null);
  }
  
  /**
   * Asks for slug
   * @param $name
   * @return mixed
   */
  private static function askForSlug($name)
  {
    $default = StringsManager::toKebabCase($name);
    return Dialog::getAnswer("Slug [$default]:", $default);
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
   * Asks for parent
   * @return mixed|null
   */
  private static function askForParent()
  {
    $parent_slug = Dialog::getAnswer('Parent term slug [null]:');
    return $parent_slug ?: null;
  }
  
  /**
   * Asks for archive template auto-build confirmation
   * @param $slug
   * @return mixed
   */
  private static function askForArchiveTemplateBuild($slug)
  {
    return Dialog::getConfirmation("Do you want to automatically create an archive template for '$slug' term?", true, 'yellow');
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
    if (!$params['name'] || !$params['taxonomy'] || !$params['theme']) {
      throw new \Exception('Error: unable to create term because of missing parameters.');
    }
    
    // normalizing
    $name = ucwords($params['name']);
    $taxonomy = StringsManager::toKebabCase($params['taxonomy']);
    $slug = StringsManager::toKebabCase($params['slug']);
    $description = ucfirst($params['description']);
    $parent = StringsManager::toKebabCase($params['parent']);
    $theme = StringsManager::toKebabCase($params['theme']);
    $build_archive = $params['build-archive'] || false;
    $override = strtolower($params['override']);
    
    if ($override !== 'ask' && $override !== 'force') {
      $override = false;
    }
  
    Config::check("themes.$theme", 'array', "Error: theme '$theme' doesn't exist.");
    
    if (!in_array($taxonomy, Config::get("themes.$theme.taxonomies", ['type' => 'array']))) {
      throw new \Exception("Error: taxonomy '$taxonomy' not found in '$theme' theme.");
    }
    
    return [
      'name' => $name,
      'taxonomy' => $taxonomy,
      'slug' => $slug,
      'description' => $description,
      'parent' => $parent,
      'theme' => $theme,
      'build-archive' => $build_archive,
      'override' => $override
    ];
  }
}
