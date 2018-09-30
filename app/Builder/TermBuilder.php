<?php

namespace Wordrobe\Builder;

use Wordrobe\Helper\Config;
use Wordrobe\Helper\Dialog;
use Wordrobe\Helper\StringsManager;
use Wordrobe\Entity\Template;

class TermBuilder extends TemplateBuilder implements WizardBuilder
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
      $parent = Config::get("themes.$theme.taxonomies.$taxonomy.hierarchical") ? self::askForParent() : null;
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
    $params = self::prepareParams($params);
    $term = new Template('term', [
      '{NAME}' => $params['name'],
      '{TAXONOMY}' => $params['taxonomy'],
      '{SLUG}' => $params['slug'],
      '{DESCRIPTION}' => $params['description'],
      '{PARENT}' => $params['parent']
    ]);
    $term->save($params['filepath'], $params['override']);
    
    if ($params['build-archive']) {
      ArchiveBuilder::build([
        'type' => $params['type'],
        'key' => $params['key'],
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
   * @param string $theme
   * @return mixed
   * @throws \Exception
   */
  private static function askForTaxonomy($theme)
  {
    $taxonomies = Config::get("themes.$theme.taxonomies", ['type' => 'array']);
    return Dialog::getChoice('Taxonomy:', array_keys($taxonomies), null);
  }
  
  /**
   * Asks for slug
   * @param string $name
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
   * @param string $slug
   * @return mixed
   */
  private static function askForArchiveTemplateBuild($slug)
  {
    return Dialog::getConfirmation("Do you want to automatically create an archive template for '$slug' term?", true, 'yellow');
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
    if (!$params['name'] || !$params['taxonomy'] || !$params['theme']) {
      throw new \Exception('Error: unable to create term because of missing parameters.');
    }
    
    // normalizing
    $name = ucwords($params['name']);
    $taxonomy = StringsManager::toKebabCase($params['taxonomy']);
    $slug = StringsManager::toKebabCase($params['slug']);
    $description = ucfirst($params['description']);
    $parent = Config::get("themes." . $params['theme'] . ".taxonomies.$taxonomy.hierarchical") ? StringsManager::toKebabCase($params['parent']) : '';
    $theme = StringsManager::toKebabCase($params['theme']);
    $build_archive = $params['build-archive'] || false;
    $override = strtolower($params['override']);
    
    if ($override !== 'ask' && $override !== 'force') {
      $override = false;
    }
  
    Config::check("themes.$theme", 'array', "Error: theme '$theme' doesn't exist.");
    
    if (!in_array($taxonomy, array_keys(Config::get("themes.$theme.taxonomies", ['type' => 'array'])))) {
      throw new \Exception("Error: taxonomy '$taxonomy' not found in '$theme' theme.");
    }

    // paths
    $theme_path = Config::getRootPath() . '/' . Config::get('themes-path', true) . '/' . $theme;
    $filepath = "$theme_path/app/term/$taxonomy/$slug.php";

    return [
      'name' => $name,
      'taxonomy' => $taxonomy,
      'slug' => $slug,
      'description' => $description,
      'parent' => $parent,
      'type' => $taxonomy === 'category' || $taxonomy === 'tag' ? $taxonomy : 'taxonomy',
      'key' => $taxonomy === 'category' || $taxonomy === 'tag' ? $slug : $taxonomy . '-' . $slug,
      'filepath' => $filepath,
      'build-archive' => $build_archive,
      'override' => $override,
      'theme' => $theme
    ];
  }
}
