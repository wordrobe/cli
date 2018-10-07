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
    $template_model = $params['post-type'] ? 'repository-extension' : 'repository';
    $repository = new Template(
      $params['theme-path'] . '/core/Repository',
      $template_model,
      [
        '{NAMESPACE}' => $params['namespace'],
        '{POST_TYPE}' => $params['post-type'],
        '{ENTITY_NAME}' => $params['entity-name']
      ]
    );
    $repository->save($params['filename'], $params['override']);
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

    // normalizing
    $post_type = $params['post-type'] ? StringsManager::toKebabCase($params['post-type']) : null;
    $entity_name = $post_type ? ($params['entity-name'] ? StringsManager::toPascalCase($params['entity-name']) : StringsManager::toPascalCase($params['post-type'])) : null;
    $override = strtolower($params['override']);

    if ($override !== 'ask' && $override !== 'force') {
      $override = false;
    }

    // paths
    $namespace = Config::get("themes.$theme.namespace", true);
    $theme_path = Config::getThemePath($theme, true);
    $filename = $entity_name ? $entity_name . 'Repository.php' : 'Repository.php';

    return [
      'namespace' => $namespace,
      'post-type' => $post_type,
      'entity-name' => $entity_name,
      'theme-path' => $theme_path,
      'filename' => $filename,
      'override' => $override,
      'theme' => $theme
    ];
  }
}
