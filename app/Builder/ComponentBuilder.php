<?php

namespace Wordrobe\Builder;

use Wordrobe\Helper\Config;
use Wordrobe\Helper\Dialog;
use Wordrobe\Helper\StringsManager;
use Wordrobe\Entity\Template;

/**
 * Class ComponentBuilder
 * @package Wordrobe\Builder
 */
class ComponentBuilder extends TemplateBuilder implements WizardBuilder
{
  /**
   * Handles component template build wizard
   */
  public static function startWizard()
  {
    try {
      $theme = self::askForTheme();
      $class_name = self::askForClassName();
      self::build([
        'class-name' => $class_name,
        'theme' => $theme,
        'override' => 'ask'
      ]);
      Dialog::write('Component template added!', 'green');
    } catch (\Exception $e) {
      Dialog::write($e->getMessage(), 'red');
      exit;
    }
  }
  
  /**
   * Builds component template
   * @param array $params
   * @example ComponentBuilder::build([
   *  'class-name' => $class_name,
   *  'theme' => $theme,
   *  'override' => 'ask'|'force'|false
   * ]);
   * @throws \Exception
   */
  public static function build($params)
  {
    $params = self::prepareParams($params);
    $component = new Template(
      $params['theme-path'] . '/templates/components',
      'component', [
        '{CLASS_NAME}' => $params['class-name'],
        '{CONTENT}' => $params['content']
      ]
    );
    $component->save($params['filename'], $params['override']);
  }
  
  /**
   * Asks for component class name
   * @return string
   */
  private static function askForClassName()
  {
    $class_name = Dialog::getAnswer('CSS class (e.g. my-component):');
    return $class_name ?: self::askForClassName();
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
    if (!$params['class-name']) {
      throw new \Exception('Error: unable to create component template because of missing parameters.');
    }
    
    // normalizing
    $content = $params['content'] || '';
    $override = strtolower($params['override']);
  
    if ($override !== 'ask' && $override !== 'force') {
      $override = false;
    }

    // paths
    $filename = StringsManager::toKebabCase($params['class-name']) . '.html.twig';
    $theme_path = Config::getThemePath($theme, true);
    
    return [
      'class-name' => $params['class-name'],
      'content' => $content,
      'theme-path' => $theme_path,
      'filename' => $filename,
      'override' => $override,
      'theme' => $theme
    ];
  }
}
