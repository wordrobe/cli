<?php

namespace Wordrobe\Builder;

use Wordrobe\Helper\Config;
use Wordrobe\Helper\Dialog;
use Wordrobe\Helper\StringsManager;
use Wordrobe\Entity\Template;

/**
 * Class EntityBuilder
 * @package Wordrobe\Builder
 */
class EntityBuilder extends TemplateBuilder implements Builder
{
  /**
   * Builds post entity template
   * @param array $params
   * @example EntityBuilder::build([
   *  'name' => $name,
   *  'theme' => $theme,
   *  'override' => 'ask'|'force'|false
   * ]);
   * @throws \Exception
   */
  public static function build($params)
  {
    $params = self::prepareParams($params);
    $template_model = $params['name'] ? 'entity-extension' : 'entity';
    $entity = new Template(
      $params['theme-path'] . '/core/Entity',
      $template_model, [
        '{NAMESPACE}' => $params['namespace'],
        '{NAME}' => $params['name']
      ]
    );
    $entity->save($params['filename'], $params['override']);
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
    $name = $params['name'] ? StringsManager::toPascalCase($params['name']) : null;
    $override = strtolower($params['override']);

    if ($override !== 'ask' && $override !== 'force') {
      $override = false;
    }

    // paths
    $namespace = Config::get("themes.$theme.namespace", true);
    $theme_path = Config::getThemePath($theme, true);
    $filename = $name ? "$name.php" : 'Entity.php';

    return [
      'namespace' => $namespace,
      'name' => $name,
      'theme-path' => $theme_path,
      'filename' => $filename,
      'override' => $override,
      'theme' => $theme
    ];
  }
}
