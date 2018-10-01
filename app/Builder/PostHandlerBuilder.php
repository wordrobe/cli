<?php

namespace Wordrobe\Builder;

use Wordrobe\Helper\Config;
use Wordrobe\Helper\Dialog;
use Wordrobe\Helper\StringsManager;
use Wordrobe\Entity\Template;

/**
 * Class PostHandlerBuilder
 * @package Wordrobe\Builder
 */
class PostHandlerBuilder extends TemplateBuilder implements Builder
{
  /**
   * Builds post handler template
   * @param array $params
   * @example PostHandlerBuilder::build([
   *  'entity-name' => $entity_name,
   *  'post-type' => $post_type,
   *  'theme' => $theme,
   *  'override' => 'ask'|'force'|false
   * ]);
   * @throws \Exception
   */
  public static function build($params)
  {
    $params = self::prepareParams($params);
    $handler = new Template('post-handler', [
      '{NAMESPACE}' => $params['namespace'],
      '{ENTITY_NAMESPACE}' => $params['entity-namespace'],
      '{ENTITY_NAME}' => $params['entity-name'],
      '{POST_TYPE}' => $params['post-type'],
    ]);
    $handler->save($params['filepath'], $params['override']);
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
    if (!$params['entity-name'] || !$params['post-type'] || !$params['theme']) {
      throw new \Exception('Error: unable to create post handler because of missing parameters.');
    }

    // normalizing
    $entity_name = StringsManager::toPascalCase($params['entity-name']);
    $post_type = StringsManager::toKebabCase($params['post-type']);
    $override = strtolower($params['override']);

    if ($override !== 'ask' && $override !== 'force') {
      $override = false;
    }

    // paths
    $filename = $entity_name . 'Handler';
    $theme_path = Config::getRootPath() . '/' . Config::get('themes-path', true) . '/' . $theme;
    $namespace = Config::get("themes.$theme.namespace", true);
    $entity_namespace = $entity_name === 'Post' ? 'Timber' : $namespace . '\Entity';
    $filepath = "$theme_path/app/Handler/$filename.php";

    return [
      'namespace' => $namespace,
      'entity-namespace' => $entity_namespace,
      'entity-name' => $entity_name,
      'post-type' => $post_type,
      'filepath' => $filepath,
      'override' => $override,
      'theme' => $theme
    ];
  }
}
