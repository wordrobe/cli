<?php

namespace Wordrobe\Builder;

use Wordrobe\Helper\Config;
use Wordrobe\Helper\Dialog;
use Wordrobe\Helper\StringsManager;
use Wordrobe\Entity\Template;

/**
 * Class DTOBuilder
 * @package Wordrobe\Builder
 */
class DTOBuilder extends TemplateBuilder implements Builder
{
  /**
   * Builds post DTO template
   * @param array $params
   * @example DTOBuilder::build([
   *  'entity-name' => $entity_name,
   *  'theme' => $theme,
   *  'override' => 'ask'|'force'|false
   * ]);
   * @throws \Exception
   */
  public static function build($params)
  {
    $params = self::prepareParams($params);
    $template_model = $params['entity-name'] ? 'dto-extension' : 'dto';
    $dto = new Template(
      $params['theme-path'] . '/core/DTO',
      $template_model,
      [
        '{NAMESPACE}' => $params['namespace'],
        '{ENTITY_NAME}' => $params['entity-name']
      ]
    );
    $dto->save($params['filename'], $params['override']);
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
    $entity_name = $params['entity-name'] ? StringsManager::toPascalCase($params['entity-name']) : null;
    $override = strtolower($params['override']);

    if ($override !== 'ask' && $override !== 'force') {
      $override = false;
    }

    // paths
    $namespace = Config::get("themes.$theme.namespace", true);
    $theme_path = Config::getThemePath($theme, true);
    $filename = $entity_name ? $entity_name . 'DTO.php' : 'DTO.php';

    return [
      'namespace' => $namespace,
      'entity-name' => $entity_name,
      'theme-path' => $theme_path,
      'filename' => $filename,
      'override' => $override,
      'theme' => $theme
    ];
  }
}
