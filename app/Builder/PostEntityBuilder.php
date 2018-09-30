<?php

namespace Wordrobe\Builder;

use Wordrobe\Helper\Config;
use Wordrobe\Helper\Dialog;
use Wordrobe\Helper\StringsManager;
use Wordrobe\Entity\Template;

class PostEntityBuilder extends TemplateBuilder implements Builder
{
  /**
   * Builds post entity
   * @param array $params
   * @example PostEntityBuilder::create([
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
    // checking existence
    if (!$params['name'] || !$params['theme']) {
      throw new \Exception('Error: unable to create post entity because of missing parameters.');
    }

    // normalizing
    $name = StringsManager::toPascalCase($params['name']);
    $theme = StringsManager::toKebabCase($params['theme']);
    $override = strtolower($params['override']);

    if ($override !== 'ask' && $override !== 'force') {
      $override = false;
    }

    Config::check("themes.$theme", 'array', "Error: theme '$theme' doesn't exist.");

    // paths
    $theme_path = Config::getRootPath() . '/' . Config::get('themes-path', true) . '/' . $theme;
    $namespace = Config::get("themes.$theme.namespace", true);
    $filepath = "$theme_path/app/entity/" . $name . ".php";

    return [
      'namespace' => $namespace,
      'name' => $name,
      'filepath' => $filepath,
      'override' => $override,
      'theme' => $theme
    ];
  }
}
