<?php

namespace Wordrobe\Builder;

use Wordrobe\Helper\Config;
use Wordrobe\Helper\Dialog;
use Wordrobe\Helper\StringsManager;
use Wordrobe\Entity\Template;

/**
 * Class PartialBuilder
 * @package Wordrobe\Builder
 */
class PartialBuilder extends TemplateBuilder implements WizardBuilder
{
  /**
   * Handles partial template build wizard
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
      Dialog::write('Partial template added!', 'green');
    } catch (\Exception $e) {
      Dialog::write($e->getMessage(), 'red');
      exit;
    }
  }
  
  /**
   * Builds partial template
   * @param array $params
   * @example PartialBuilder::build([
   *  'class-name' => $class_name,
   *  'theme' => $theme,
   *  'override' => 'ask'|'force'|false
   * ]);
   * @throws \Exception
   */
  public static function build($params)
  {
    $params = self::prepareParams($params);
    $partial = new Template('partial', [
      '{CLASS_NAME}' => $params['class-name'],
      '{CONTENT}' => $params['content']
    ]);
    $partial->save($params['filepath'], $params['override']);
  }
  
  /**
   * Asks for partial class name
   * @return string
   */
  private static function askForClassName()
  {
    $class_name = Dialog::getAnswer('Class name (e.g. my-partial):');
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
    if (!$params['class-name'] || !$params['theme']) {
      throw new \Exception('Error: unable to create partial template because of missing parameters.');
    }
    
    // normalizing
    $content = $params['content'] || '';
    $override = strtolower($params['override']);
  
    if ($override !== 'ask' && $override !== 'force') {
      $override = false;
    }

    // paths
    $filename = StringsManager::toKebabCase($params['class-name']);
    $theme_path = Config::getRootPath() . '/' . Config::get('themes-path', true) . '/' . $theme;
    $filepath = "$theme_path/templates/partials/$filename.html.twig";
    
    return [
      'class-name' => $params['class-name'],
      'content' => $content,
      'filepath' => $filepath,
      'override' => $override,
      'theme' => $theme
    ];
  }
}
