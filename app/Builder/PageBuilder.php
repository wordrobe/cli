<?php

namespace Wordrobe\Builder;

use Wordrobe\Helper\Config;
use Wordrobe\Helper\Dialog;
use Wordrobe\Helper\StringsManager;
use Wordrobe\Entity\Template;

/**
 * Class PageBuilder
 * @package Wordrobe\Builder
 */
class PageBuilder extends TemplateBuilder implements WizardBuilder
{
  /**
   * Handles page template build wizard
   * @param null|array $args
   */
  public static function startWizard($args = null)
  {
    try {
      $theme = self::askForTheme();
      $name = self::askForName();
      self::build([
        'name' => $name,
        'theme' => $theme,
        'override' => 'ask'
      ]);
      Dialog::write('Page template added!', 'green');
    } catch (\Exception $e) {
      Dialog::write($e->getMessage(), 'red');
      exit;
    }
  }

  /**
   * Builds page template
   * @param array $params
   * @example PageBuilder::create([
   *  'name' => $name,
   *  'theme' => $theme,
   *  'override' => 'ask'|'force'|false
   * ]);
   * @throws \Exception
   */
  public static function build($params)
  {
    $params = self::prepareParams($params);
    $page_ctrl = new Template(
      $params['theme-path'] . '/controllers',
      'page-ctrl',
      [
        '{TEMPLATE_NAME}' => $params['name'],
        '{NAMESPACE}' => $params['namespace'],
        '{ENTITY_NAME}' => $params['entity-name'],
        '{VIEW_FILENAME}' => $params['filename']
      ]
    );
    $page_view = new Template(
      $params['theme-path'] . '/templates/views',
      'view'
    );
    $page_ctrl->save($params['ctrl-filename'], $params['override']);
    $page_view->save($params['view-filename'], $params['override']);

    Config::add($params['config-path'], $params['filename']);

    EntityBuilder::build([
      'name' => $params['entity-name'],
      'theme' => $params['theme'],
      'override' => $params['override']
    ]);

    DTOBuilder::build([
      'entity-name' => $params['entity-name'],
      'theme' => $params['theme'],
      'override' => $params['override']
    ]);

    RepositoryBuilder::build([
      'post-type' => 'page',
      'meta-query' => "['key' => '_wp_page_template', 'value' => 'controllers/" . $params['filename'] . ".php']",
      'entity-name' => $params['entity-name'],
      'theme' => $params['theme'],
      'override' => $params['override']
    ]);
  }
  
  /**
   * Asks for page template name
   * @return string
   */
  private static function askForName()
  {
    $name = Dialog::getAnswer('Template name (e.g. My Custom Page):');
    return $name ?: self::askForName();
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
    if (!$params['name']) {
      throw new \Exception('Error: unable to create page template because of missing parameters.');
    }
    
    // normalizing
    $name = ucwords($params['name']);
    $entity_name = StringsManager::toPascalCase($params['name']);
    $override = strtolower($params['override']);
  
    if ($override !== 'ask' && $override !== 'force') {
      $override = false;
    }

    // paths
    $config_path = "themes.$theme.templates";
    $theme_path = Config::getThemePath($theme, true);
    $namespace = Config::get("themes.$theme.namespace", true);
    $filename = StringsManager::toKebabCase($name);
    $ctrl_filename = "$filename.php";
    $view_filename = "$filename.html.twig";
    
    return [
      'name' => $name,
      'namespace' => $namespace,
      'entity-name' => $entity_name,
      'config-path' => $config_path,
      'theme-path' => $theme_path,
      'filename' => $filename,
      'ctrl-filename' => $ctrl_filename,
      'view-filename' => $view_filename,
      'override' => $override,
      'theme' => $theme
    ];
  }
}
