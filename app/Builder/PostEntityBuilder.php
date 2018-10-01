<?php

namespace Wordrobe\Builder;

use Wordrobe\Helper\Config;
use Wordrobe\Helper\Dialog;
use Wordrobe\Helper\StringsManager;
use Wordrobe\Entity\Template;

/**
 * Class PostEntityBuilder
 * @package Wordrobe\Builder
 */
class PostEntityBuilder extends TemplateBuilder implements Builder
{
  /**
   * Builds post entity template
   * @param array $params
   * @example PostEntityBuilder::build([
   *  'post-type' => $post_type,
   *  'name' => $name,
   *  'theme' => $theme,
   *  'override' => 'ask'|'force'|false
   * ]);
   * @throws \Exception
   */
  public static function build($params)
  {
    $params = self::prepareParams($params);
    $entity = new Template('post-entity', [
      '{NAMESPACE}' => $params['namespace'],
      '{NAME}' => $params['name']
    ]);
    $entity->save($params['filepath'], $params['override']);
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
    if (!$params['name'] || !$params['theme']) {
      throw new \Exception('Error: unable to create post entity because of missing parameters.');
    }

    // normalizing
    $name = StringsManager::toPascalCase($params['name']);
    $override = strtolower($params['override']);

    if ($override !== 'ask' && $override !== 'force') {
      $override = false;
    }

    // paths
    $theme_path = Config::getRootPath() . '/' . Config::get('themes-path', true) . '/' . $theme;
    $namespace = Config::get("themes.$theme.namespace", true);
    $filepath = "$theme_path/app/Entity/" . $name . ".php";

    return [
      'namespace' => $namespace,
      'name' => $name,
      'filepath' => $filepath,
      'override' => $override,
      'theme' => $theme
    ];
  }
}
