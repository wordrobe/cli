<?php

namespace Wordrobe\Builder;

use Wordrobe\Helper\Config;
use Wordrobe\Helper\Dialog;
use Wordrobe\Helper\StringsManager;
use Wordrobe\Entity\Template;

/**
 * Class PostDTOBuilder
 * @package Wordrobe\Builder
 */
class PostDTOBuilder extends TemplateBuilder implements Builder
{
  /**
   * Builds post DTO template
   * @param array $params
   * @example PostDTOBuilder::build([
   *  'entity-name' => $entity_name,
   *  'theme' => $theme,
   *  'override' => 'ask'|'force'|false
   * ]);
   * @throws \Exception
   */
  public static function build($params)
  {
    $params = self::prepareParams($params);
    $dto = new Template('post-dto', [
      '{NAMESPACE}' => $params['namespace'],
      '{ENTITY_NAMESPACE}' => $params['entity-namespace'],
      '{ENTITY_NAME}' => $params['entity-name']
    ]);
    $dto->save($params['filepath'], $params['override']);
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
      throw new \Exception('Error: unable to create post DTO because of missing parameters.');
    }

    // normalizing
    $entity_name = StringsManager::toPascalCase($params['entity-name']);
    $override = strtolower($params['override']);

    if ($override !== 'ask' && $override !== 'force') {
      $override = false;
    }

    // paths
    $filename = $entity_name . 'DTO';
    $theme_path = Config::getRootPath() . '/' . Config::get('themes-path', true) . '/' . $theme;
    $namespace = Config::get("themes.$theme.namespace", true);
    $entity_namespace = $entity_name === 'Post' ? 'Timber' : $namespace . '\Entity';
    $filepath = "$theme_path/core/DTO/$filename.php";

    return [
      'namespace' => $namespace,
      'entity-namespace' => $entity_namespace,
      'entity-name' => $entity_name,
      'filepath' => $filepath,
      'override' => $override,
      'theme' => $theme
    ];
  }
}
