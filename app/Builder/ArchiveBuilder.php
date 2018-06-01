<?php

namespace Wordrobe\Builder;

use Wordrobe\Config;
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
    $theme = self::askForTheme(['template-engine']);
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
    
    try {
      self::build([
        'type' => $type,
        'key' => $key,
        'theme' => $theme,
        'override' => 'ask'
      ]);
    } catch (\Exception $e) {
      Dialog::write($e->getMessage(), 'red');
      exit;
    }
  
    Dialog::write('Archive template added!', 'green');
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
   * @return bool
   */
  public static function build($params)
  {
    $params = self::checkParams($params);
    $basename = $params['type'] === 'post-type' ? 'archive' : $params['type'];
    $filename = $params['key'] ? $basename . '-' . $params['key'] : $basename;
    $template_engine = Config::get('themes.' . $params['theme'] . '.template-engine');
    $theme_path = PROJECT_ROOT . '/' . Config::get('themes-path') . '/' . $params['theme'];
    $type_and_key = trim(str_replace("''", '', $params['type'] . "' " . $params['key'] . "'"));
    $archive_ctrl = new Template("$template_engine/archive", ['{TYPE_AND_KEY}' => $type_and_key]);
    $archive_ctrl->save("$theme_path/$filename.php", $params['override']);
    
    if ($template_engine === 'timber') {
       self::buildView($archive_ctrl, $filename, $theme_path, $params['override']);
    }
  }
  
  /**
   * Builds archive view
   * @param Template $controller
   * @param string $filename
   * @param string $theme_path
   * @param mixed $override
   */
  private static function buildView($controller, $filename, $theme_path, $override)
  {
    $controller->fill('{VIEW_FILENAME}', $filename);
    $view = new Template('timber/view');
    $view->save("$theme_path/views/default/$filename.html.twig", $override);
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
   * @param $theme
   * @return mixed
   */
  private static function askForPostType($theme)
  {
    $post_types = Config::expect("themes.$theme.post-types", 'array');
    $post_types = array_diff($post_types, ['post']);
    
    if (!empty($post_types)) {
      return Dialog::getChoice('Post type:', $post_types, null);
    }
    
    Dialog::write('Error: before creating a post-type based archive, you need to define a custom post type.', 'red');
    exit;
  }
  
  /**
   * Asks for taxonomy
   * @param $theme
   * @return mixed
   */
  private static function askForTaxonomy($theme)
  {
    $taxonomies = Config::expect("themes.$theme.taxonomies", 'array');
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
    return $term ? $term : self::askForTerm();
  }
  
  /**
   * Checks params existence and normalizes them
   * @param $params
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
    $override = ($params['override'] === 'ask' || $params['override'] === 'force') ? $params['override'] : false;
    
    if (!in_array($type, self::TYPES)) {
      throw new \Exception("Error: archive type '$type' not found.");
    }
    
    if (!Config::get("themes.$theme")) {
      throw new \Exception("Error: theme '$theme' doesn't exist.");
    }
    
    return [
      'type' => $type,
      'key' => $key,
      'theme' => $theme,
      'override' => $override
    ];
  }
}
