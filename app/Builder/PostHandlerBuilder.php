<?php

namespace Wordrobe\Builder;

use Wordrobe\Helper\Config;
use Wordrobe\Helper\Dialog;
use Wordrobe\Helper\StringsManager;
use Wordrobe\Entity\Template;

class PostHandlerBuilder extends TemplateBuilder implements Builder
{
  /**
   * Builds post handler
   * @param array $params
   * @example PostHandlerBuilder::create([
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
    // checking existence
    if (!$params['entity-name'] || !$params['post-type'] || !$params['theme']) {
      throw new \Exception('Error: unable to create post handler because of missing parameters.');
    }

    // normalizing
    $entity_name = StringsManager::toPascalCase($params['entity-name']);
    $post_type = StringsManager::toKebabCase($params['post-type']);
    $theme = StringsManager::toKebabCase($params['theme']);
    $override = strtolower($params['override']);

    if ($override !== 'ask' && $override !== 'force') {
      $override = false;
    }

    Config::check("themes.$theme", 'array', "Error: theme '$theme' doesn't exist.");

    // paths
    $filename = $entity_name . 'Handler';
    $theme_path = Config::getRootPath() . '/' . Config::get('themes-path', true) . '/' . $theme;
    $namespace = Config::get("themes.$theme.namespace", true);
    $entity_namespace = $entity_name === 'Post' ? 'Timber' : $namespace . '\Entity';
    $filepath = "$theme_path/app/handler/$filename.php";

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
