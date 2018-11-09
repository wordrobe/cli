<?php

namespace Wordrobe\Builder;

use Wordrobe\Helper\Config;
use Wordrobe\Helper\StringsManager;
use Wordrobe\Entity\Template;

/**
 * Class BlankTemplateBuilder
 * @package Wordrobe\Builder
 */
class BlankTemplateBuilder extends TemplateBuilder implements Builder
{
  /**
   * Builds single post template
   * @param array $params
   * @example BlankTemplateBuilder::build([
   *  'theme' => $theme,
   *  'override' => 'ask'|'force'|false
   * ]);
   * @throws \Exception
   */
  public static function build($params)
  {
    $params = self::prepareParams($params);
    $blank_ctrl = new Template(
      $params['theme-path'] . '/controllers',
      'blank-ctrl',
      [
        '{NAMESPACE}' => $params['namespace'],
        '{VIEW_FILENAME}' => $params['filename']
      ]
    );
    $blank_view = new Template(
      $params['theme-path'] . '/templates/views',
      'view'
    );
    $blank_ctrl->save($params['ctrl-filename'], $params['override']);
    $blank_view->save($params['view-filename'], $params['override']);
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
    if (!$params['filename']) {
      throw new \Exception('Error: unable to create blank template because of missing parameters.');
    }

    // normalizing
    $filename = StringsManager::toKebabCase($params['filename']);
    $override = strtolower($params['override']);

    if ($override !== 'ask' && $override !== 'force') {
      $override = false;
    }

    // paths
    $namespace = Config::get("themes.$theme.namespace", true);
    $theme_path = Config::getThemePath($theme, true);
    $ctrl_filename = "$filename.php";
    $view_filename = "$filename.html.twig";

    return [
      'namespace' => $namespace,
      'theme-path' => $theme_path,
      'ctrl-filename' => $ctrl_filename,
      'view-filename' => $view_filename,
      'override' => $override,
      'theme' => $theme
    ];
  }
}
