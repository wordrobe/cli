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
  /**
   * Builds archive template
   * @param array $params
   * @example ArchiveBuilder::build([
   *  'post-type' => $post_type,
   *  'taxonomy' => $taxonomy,
   *  'term' => $term,
   *  'theme' => $theme,
   *  'override' => 'ask'|'force'|false
   * ]);
   * @throws \Exception
   */
  public static function build($params)
  {
    $params = self::prepareParams($params);
    $archive_ctrl = new Template(
      $params['theme-path'] . '/controllers',
      'archive',
      [
        '{NAMESPACE}' => $params['namespace'],
        '{ENTITY_NAME}' => $params['entity-name'],
        '{VIEW_FILENAME}' => $params['filename']
      ]
    );
    $archive_view = new Template(
      $params['theme-path'] . '/templates/views',
      'view'
    );
    $archive_ctrl->save($params['ctrl-filename'], $params['override']);
    $archive_view->save($params['view-filename'], $params['override']);
  }
  
  /**
   * Checks params existence and normalizes them
   * @param array $params
   * @return array
   * @throws \Exception
   */
  private static function prepareParams($params)
  {
    // checking theme
    $theme = StringsManager::toKebabCase($params['theme']);
    Config::check("themes.$theme", 'array', "Error: theme '$theme' doesn't exist.");

    // checking params
    if (!$params['post-type'] || !$params['theme']) {
      throw new \Exception('Error: unable to create archive template because of missing parameters.');
    }

    // checking post type
    $post_type = StringsManager::toKebabCase($params['post-type']);
    Config::check("themes.$theme.post-types.$post_type", 'array', "Error: post type '$post_type' not found in '$theme' theme.");
    
    // normalizing
    $taxonomy = $params['taxonomy'] ? StringsManager::toKebabCase($params['taxonomy']) : null;
    $term = $params['term'] ? StringsManager::toKebabCase($params['term']) : null;
    $entity_name = Config::get("themes.$theme.post-types.$post_type.entity") ?: '';
    $override = strtolower($params['override']);
  
    if ($override !== 'ask' && $override !== 'force') {
      $override = false;
    }

    // paths
    $namespace = Config::get("themes.$theme.namespace", true);
    $theme_path = Config::getThemePath($theme, true);
    $basename = $taxonomy ? ($taxonomy === 'category' || $taxonomy === 'tag' ? $taxonomy : "taxonomy-$taxonomy") : ($post_type === 'post' ? 'archive' : "archive-$post_type");
    $filename = $term ? "$basename-$term" : $basename;
    $ctrl_filename = "$filename.php";
    $view_filename = "$filename.html.twig";
    
    return [
      'namespace' => $namespace,
      'entity-name' => $entity_name,
      'theme-path' => $theme_path,
      'filename' => $filename,
      'ctrl-filename' => $ctrl_filename,
      'view-filename' => $view_filename,
      'override' => $override,
      'theme' => $theme
    ];
  }
}
