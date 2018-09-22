<?php

namespace Wordrobe\Builder;

use Wordrobe\Helper\Config;
use Wordrobe\Helper\Dialog;
use Wordrobe\Entity\Template;
use Wordrobe\Helper\StringsManager;

/**
 * Class ConfigBuilder
 * @package Wordrobe\Builder
 */
class ArchiveBuilder extends TemplateBuilder implements Builder
{
  const TYPES = [
    'post-type',
    'category',
    'taxonomy',
    'tag'
  ];
  
  /**
   * Handles config creation wizard
   */
  public static function startWizard()
  {
    try {
      $theme = self::askForTheme();
      $type = self::askForType();
  
      switch ($type) {
        case 'post-type':
          $key = self::askForPostType($theme);
          break;
        case 'taxonomy':
          $key = self::askForTaxonomy($theme);
          break;
        default:
          $key = self::askForTerm();
          break;
      }
      
      self::build([
        'type' => $type,
        'key' => $key,
        'theme' => $theme,
        'override' => 'ask'
      ]);
      Dialog::write('Archive template added!', 'green');
    } catch (\Exception $e) {
      Dialog::write($e->getMessage(), 'red');
      exit;
    }
  }
  
  /**
   * Builds archive
   * @param array $params
   * @example ArchiveBuilder::create([
   *  'type' => $type,
   *  'key' => $key,
   *  'theme' => $theme,
   *  'override' => 'ask'|'force'|false
   * ]);
   * @throws \Exception
   */
  public static function build($params)
  {
    $params = self::checkParams($params);
    $basename = $params['type'] === 'post-type' ? 'archive' : $params['type'];
    $filename = $params['key'] ? $basename . '-' . $params['key'] : $basename;
    $theme_path = Config::getRootPath() . '/' . Config::get('themes-path', true) . '/' . $params['theme'];
    $type_and_key = trim(str_replace("''", '', $params['type'] . ($params['type'] === 'taxonomy' ? '(-term)' : '') . " '" . $params['key'] . "'"));
    $archive_ctrl = new Template('archive', ['{TYPE_AND_KEY}' => $type_and_key]);
    $archive_ctrl->save("$theme_path/$filename.php", $params['override']);
    self::buildView($archive_ctrl, $filename, $theme_path, $params['override']);
  }
  
  /**
   * Builds archive view
   * @param Template $controller
   * @param string $filename
   * @param string $theme_path
   * @param mixed $override
   * @throws \Exception
   */
  private static function buildView($controller, $filename, $theme_path, $override)
  {
    $controller->fill('{VIEW_FILENAME}', $filename);
    $view = new Template('view');
    $view->save("$theme_path/templates/default/$filename.html.twig", $override);
  }
  
  /**
   * Asks for archive type
   * @return mixed
   */
  private static function askForType()
  {
    return Dialog::getChoice('What type of archive do you want to add?', self::TYPES, null);
  }
  
  /**
   * Asks for post type
   * @param string $theme
   * @return mixed
   * @throws \Exception
   */
  private static function askForPostType($theme)
  {
    $post_types = Config::get("themes.$theme.post-types", ['type' => 'array']);
    $post_types = array_diff($post_types, ['post']);
    
    if (!empty($post_types)) {
      return Dialog::getChoice('Post type:', array_values($post_types), null);
    }
    
    Dialog::write('Error: before creating a post-type based archive, you need to define a custom post type.', 'red');
    exit;
  }
  
  /**
   * Asks for taxonomy
   * @param string $theme
   * @return mixed
   * @throws \Exception
   */
  private static function askForTaxonomy($theme)
  {
    $taxonomies = Config::get("themes.$theme.taxonomies", ['tyep' => 'array']);
    $taxonomies = array_diff($taxonomies, ['category', 'tag']);
    
    if (!empty($taxonomies)) {
      return Dialog::getChoice('Taxonomy:', array_values($taxonomies), null);
    }
    
    Dialog::write('Error: before creating a taxonomy based archive, you need to define a custom taxonomy.', 'red');
    exit;
  }
  
  /**
   * Asks for term
   * @return mixed
   */
  private static function askForTerm()
  {
    $term = Dialog::getAnswer('Term:');
    return $term ?: self::askForTerm();
  }
  
  /**
   * Checks params existence and normalizes them
   * @param array $params
   * @return array
   * @throws \Exception
   */
  private static function checkParams($params)
  {
    // checking existence
    if (!$params['type'] || !$params['key'] || !$params['theme']) {
      throw new \Exception('Error: unable to create archive template because of missing parameters.');
    }
    
    // normalizing
    $type = StringsManager::toKebabCase($params['type']);
    $key = StringsManager::toKebabCase($params['key']);
    $theme = StringsManager::toKebabCase($params['theme']);
    $override = strtolower($params['override']);
  
    if ($override !== 'ask' && $override !== 'force') {
      $override = false;
    }
    
    if (!in_array($type, self::TYPES)) {
      throw new \Exception("Error: archive type '$type' not found.");
    }
  
    Config::check("themes.$theme", 'array', "Error: theme '$theme' doesn't exist.");
    
    if ($type === 'archive' && !in_array($key, Config::get("themes.$theme.post-types", ['type' => 'array']))) {
      throw new \Exception("Error: post type '$key' not found in '$theme' theme.");
    }
    
    return [
      'type' => $type,
      'key' => $key,
      'theme' => $theme,
      'override' => $override
    ];
  }
}
