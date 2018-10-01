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
   */
  public static function startWizard()
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
    $page_ctrl = new Template('page', [
      '{TEMPLATE_NAME}' => $params['name'],
      '{NAMESPACE}' => $params['namespace'],
      '{ENTITY_NAME}' => $params['entity-name'],
      '{VIEW_FILENAME}' => $params['filename']
    ]);
    $page_view = new Template('view');
    $page_ctrl->save($params['ctrl-filepath'], $params['override']);
    $page_view->save($params['view-filepath'], $params['override']);

    PostDTOBuilder::build([
      'entity-name' => $params['entity-name'],
      'theme' => $params['theme'],
      'override' => $params['override']
    ]);

    PostEntityBuilder::build([
      'name' => $params['entity-name'],
      'theme' => $params['theme'],
      'override' => $params['override']
    ]);

    PageHandlerBuilder::build([
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
    if (!$params['name'] || !$params['theme']) {
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
    $filename = StringsManager::toKebabCase($name);
    $theme_path = Config::getRootPath() . '/' . Config::get('themes-path', true) . '/' . $theme;
    $namespace = Config::get("themes.$theme.namespace", true);
    $ctrl_filepath = "$theme_path/pages/$filename.php";
    $view_filepath = "$theme_path/templates/pages/$filename.html.twig";
    
    return [
      'name' => $name,
      'namespace' => $namespace,
      'entity-name' => $entity_name,
      'filename' => $filename,
      'ctrl-filepath' => $ctrl_filepath,
      'view-filepath' => $view_filepath,
      'override' => $override,
      'theme' => $theme
    ];
  }
}
