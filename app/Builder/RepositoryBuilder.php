<?php

namespace Wordrobe\Builder;

use Wordrobe\Helper\Config;
use Wordrobe\Helper\StringsManager;
use Wordrobe\Entity\Template;

/**
 * Class RepositoryBuilder
 * @package Wordrobe\Builder
 */
class RepositoryBuilder extends TemplateBuilder implements Builder
{
  /**
   * Builds post handler template
   * @param array $params
   * @example RepositoryBuilder::build([
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
    $template_model = $params['capability-type'] . '-repository';
    $repository = new Template($template_model, [
      '{NAMESPACE}' => $params['namespace'],
      '{ENTITY_NAME}' => $params['entity-name'],
      '{POST_TYPE}' => $params['post-type'],
    ]);
    $repository->save($params['filepath'], $params['override']);
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
    $capability_type = Config::get("themes.$theme.post-types.$post_type.capability-type");
    $override = strtolower($params['override']);

    if ($override !== 'ask' && $override !== 'force') {
      $override = false;
    }

    // paths
    $filename = $entity_name . 'Repository';
    $theme_path = Config::getRootPath() . '/' . Config::get('themes-path', true) . '/' . $theme;
    $namespace = Config::get("themes.$theme.namespace", true);
    $filepath = "$theme_path/core/Repository/$filename.php";

    return [
      'namespace' => $namespace,
      'capability-type' => $capability_type,
      'entity-name' => $entity_name,
      'post-type' => $post_type,
      'filepath' => $filepath,
      'override' => $override,
      'theme' => $theme
    ];
  }
}
