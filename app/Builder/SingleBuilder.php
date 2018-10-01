<?php

namespace Wordrobe\Builder;

use Wordrobe\Helper\Config;
use Wordrobe\Helper\Dialog;
use Wordrobe\Helper\StringsManager;
use Wordrobe\Entity\Template;

/**
 * Class SingleBuilder
 * @package Wordrobe\Builder
 */
class SingleBuilder extends TemplateBuilder implements Builder
{
  /**
   * Builds single post template
   * @param array $params
   * @example SingleBuilder::build([
   *  'post-type' => $post_type,
   *  'entity-name' => $entity_name,
   *  'theme' => $theme,
   *  'override' => 'ask'|'force'|false
   * ]);
   * @throws \Exception
   */
  public static function build($params)
  {
    $params = self::prepareParams($params);
    $single_ctrl = new Template('single', [
      '{NAMESPACE}' => $params['namespace'],
      '{ENTITY_NAME}' => $params['entity-name'],
      '{POST_TYPE}' => $params['post-type'],
      '{VIEW_FILENAME}' => $params['filename']
    ]);
    $single_view = new Template('view');
    $single_ctrl->save($params['ctrl-filepath'], $params['override']);
    $single_view->save($params['view-filepath'], $params['override']);
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
    if (!$params['post-type'] || !$params['theme']) {
      throw new \Exception('Error: unable to create single template because of missing parameters.');
    }
    
    // normalizing
    $post_type = StringsManager::toKebabCase($params['post-type']);
    $entity_name = $params['entity-name'] ? StringsManager::toPascalCase($params['entity-name']) : StringsManager::toPascalCase($params['post-type']);
    $override = strtolower($params['override']);
  
    if ($override !== 'ask' && $override !== 'force') {
      $override = false;
    }
    
    if (!in_array($post_type, Config::get("themes.$theme.post-types", ['type' => 'array']))) {
      throw new \Exception("Error: post type '$post_type' not found in '$theme' theme.");
    }

    // paths
    $filename = 'single-' . $post_type;
    $theme_path = Config::getRootPath() . '/' . Config::get('themes-path', true) . '/' . $theme;
    $namespace = Config::get("themes.$theme.namespace", true);
    $ctrl_filepath = "$theme_path/$filename.php";
    $view_filepath = "$theme_path/templates/default/$filename.html.twig";
    
    return [
      'namespace' => $namespace,
      'post-type' => $post_type,
      'entity-name' => $entity_name,
      'ctrl-filepath' => $ctrl_filepath,
      'view-filepath' => $view_filepath,
      'override' => $override,
      'theme' => $theme
    ];
  }
}
