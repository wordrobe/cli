<?php

namespace Wordrobe\Builder;

use Wordrobe\Helper\Config;
use Wordrobe\Helper\Dialog;
use Wordrobe\Helper\StringsManager;
use Wordrobe\Entity\Template;

/**
 * Class TermBuilder
 * @package Wordrobe\Builder
 */
class TermBuilder extends TemplateBuilder implements WizardBuilder
{
  /**
   * Handles term template build wizard
   */
  public static function startWizard()
  {
    try {
      $theme = self::askForTheme();
      $name = self::askForName();
      $taxonomy = self::askForTaxonomy($theme);
      $slug = self::askForSlug($name);
      $description = self::askForDescription();
      $post_type = Config::get("themes.$theme.taxonomies.$taxonomy.post-type");
      $has_archive = Config::get("themes.$theme.post-types.$post_type.has-archive") ? self::askForArchiveTemplateBuild($slug) : false;
      self::build([
        'name' => $name,
        'taxonomy' => $taxonomy,
        'slug' => $slug,
        'description' => $description,
        'theme' => $theme,
        'has-archive' => $has_archive,
        'override' => 'ask'
      ]);
      Dialog::write('Term added!', 'green');
    } catch (\Exception $e) {
      Dialog::write($e->getMessage(), 'red');
      exit;
    }
  }
  
  /**
   * Builds term template
   * @param array $params
   * @example TermBuilder::build([
   *  'name' => $name,
   *  'taxonomy' => $taxonomy,
   *  'slug' => $slug,
   *  'description' => $description,
   *  'theme' => $theme,
   *  'has-archive' => $has_archive
   *  'override' => 'ask'|'force'|false
   * ]);
   * @throws \Exception
   */
  public static function build($params)
  {
    $params = self::prepareParams($params);
    $term = new Template(
      $params['theme-path'] . '/core/terms',
      'term',
      [
        '{NAME}' => $params['name'],
        '{TAXONOMY}' => $params['taxonomy'],
        '{SLUG}' => $params['slug'],
        '{DESCRIPTION}' => $params['description']
      ]
    );
    $term->save($params['filename'], $params['override']);
    
    if ($params['has-archive']) {
      ArchiveBuilder::build([
        'post-type' => $params['post-type'],
        'taxonomy' => $params['taxonomy'],
        'term' => $params['slug'],
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
    $choices = [];

    foreach ($taxonomies as $key => $data) {
      $choices["(" . $data['post-type'] . ") $key"] = $key;
    }

    $taxonomy = Dialog::getChoice('Taxonomy:', array_keys($choices), null);
    return $taxonomy ? $choices[$taxonomy] : self::askForTaxonomy($theme);
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
   * Asks for archive template auto-build confirmation
   * @param string $term
   * @return mixed
   */
  private static function askForArchiveTemplateBuild($term)
  {
    return Dialog::getConfirmation("Do you want to create a custom archive template for '$term' term?", true, 'yellow');
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
    if (!$params['name'] || !$params['taxonomy']) {
      throw new \Exception('Error: unable to create term because of missing parameters.');
    }

    // checking taxonomy
    $taxonomy = StringsManager::toKebabCase($params['taxonomy']);
    Config::check("themes.$theme.taxonomies.$taxonomy", 'array', "Error: taxonomy '$taxonomy' not found in '$theme' theme.");
    
    // normalizing
    $post_type = Config::get("themes.$theme.taxonomies.$taxonomy.post-type", true);
    $name = ucwords($params['name']);
    $slug = StringsManager::toKebabCase($params['slug']);
    $description = ucfirst($params['description']);
    $has_archive = Config::get("themes.$theme.post-types.$post_type.has-archive") ? (bool) $params['has-archive'] : false;
    $override = strtolower($params['override']);
    
    if ($override !== 'ask' && $override !== 'force') {
      $override = false;
    }

    // paths
    $theme_path = Config::getThemePath($theme, true);
    $filename = "$taxonomy/$slug.php";

    return [
      'name' => $name,
      'post-type' => $post_type,
      'taxonomy' => $taxonomy,
      'slug' => $slug,
      'description' => $description,
      'theme-path' => $theme_path,
      'filename' => $filename,
      'has-archive' => $has_archive,
      'override' => $override,
      'theme' => $theme
    ];
  }
}
