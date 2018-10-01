<?php

namespace Wordrobe\Builder;

use Wordrobe\Helper\Config;
use Wordrobe\Helper\StringsManager;
use Wordrobe\Entity\Template;

/**
 * Class PageHandlerBuilder
 * @package Wordrobe\Builder
 */
class PageHandlerBuilder extends TemplateBuilder implements Builder
{
  /**
   * Builds page handler template
   * @param array $params
   * @example PageHandlerBuilder::build([
   *  'entity-name' => $entity_name,
   *  'theme' => $theme,
   *  'override' => 'ask'|'force'|false
   * ]);
   * @throws \Exception
   */
  public static function build($params)
  {
    $params = self::prepareParams($params);
    $handler = new Template('page-handler', [
      '{NAMESPACE}' => $params['namespace'],
      '{ENTITY_NAMESPACE}' => $params['namespace'] . '\Entity',
      '{ENTITY_NAME}' => $params['entity-name']
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
    if (!$params['entity-name'] || !$params['theme']) {
      throw new \Exception('Error: unable to create page handler because of missing parameters.');
    }

    // normalizing
    $entity_name = StringsManager::toPascalCase($params['entity-name']);
    $override = strtolower($params['override']);

    if ($override !== 'ask' && $override !== 'force') {
      $override = false;
    }

    // paths
    $filename = $entity_name . 'Handler';
    $theme_path = Config::getRootPath() . '/' . Config::get('themes-path', true) . '/' . $theme;
    $namespace = Config::get("themes.$theme.namespace", true);
    $filepath = "$theme_path/core/Handler/$filename.php";

    return [
      'namespace' => $namespace,
      'entity-name' => $entity_name,
      'filepath' => $filepath,
      'override' => $override,
      'theme' => $theme
    ];
  }
}
