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
    $single_ctrl = new Template(
      $params['theme-path'] . '/controllers',
      'single',
      [
        '{NAMESPACE}' => $params['namespace'],
        '{ENTITY_NAME}' => $params['entity-name'],
        '{POST_TYPE}' => $params['post-type'],
        '{VIEW_FILENAME}' => $params['post-type']
      ]
    );
    $single_view = new Template(
      $params['theme-path'] . '/templates/views',
      'view'
    );
    $single_ctrl->save($params['ctrl-filename'], $params['override']);
    $single_view->save($params['view-filename'], $params['override']);
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
    if (!$params['post-type']) {
      throw new \Exception('Error: unable to create single template because of missing parameters.');
    }
    
    // normalizing
    $post_type = StringsManager::toKebabCase($params['post-type']);
    $entity_name = ($post_type !== 'post' && $post_type !== 'page') ? ($params['entity-name'] ? StringsManager::toPascalCase($params['entity-name']) : StringsManager::toPascalCase($params['post-type'])) : '';
    $override = strtolower($params['override']);
  
    if ($override !== 'ask' && $override !== 'force') {
      $override = false;
    }

    if (!Config::get("themes.$theme.post-types.$post_type")) {
      throw new \Exception("Error: post type '$post_type' not found in '$theme' theme.");
    }

    // paths
    $namespace = Config::get("themes.$theme.namespace", true);
    $theme_path = Config::getThemePath($theme, true);
    $ctrl_filename = "$post_type.php";
    $view_filename = "$post_type.html.twig";
    
    return [
      'namespace' => $namespace,
      'post-type' => $post_type,
      'entity-name' => $entity_name,
      'theme-path' => $theme_path,
      'ctrl-filename' => $ctrl_filename,
      'view-filename' => $view_filename,
      'override' => $override,
      'theme' => $theme
    ];
  }
}
