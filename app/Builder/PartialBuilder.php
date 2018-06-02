<?php

namespace Wordrobe\Builder;

use Wordrobe\Config;
use Wordrobe\Helper\Dialog;
use Wordrobe\Helper\StringsManager;
use Wordrobe\Entity\Template;

class PartialBuilder extends TemplateBuilder implements Builder
{
  /**
   * Handles partial template creation wizard
   */
  public static function startWizard()
  {
    $theme = self::askForTheme(['template-engine']);
    $class_name = self::askForClassName();
  
    try {
      self::build([
        'class_name' => $class_name,
        'theme' => $theme,
        'override' => 'ask'
      ]);
    } catch (\Exception $e) {
      Dialog::write($e->getMessage(), 'red');
      exit;
    }
  
    Dialog::write('Partial template added!', 'green');
  }
  
  /**
   * Builds partial template
   * @param array $params
   * @example PartialBuilder::create([
   *  'class_name' => $class_name,
   *  'theme' => $theme,
   *  'override' => 'ask'|'force'|false
   * ]);
   */
  public static function build($params)
  {
    $params = self::checkParams($params);
    $filename = StringsManager::toKebabCase($params['class_name']);
    $template_engine = Config::get('themes.' . $params['theme'] . '.template-engine');
    $theme_path = PROJECT_ROOT . '/' . Config::get('themes-path') . '/' . $params['theme'];
    $partial = new Template('partial', ['{CLASS_NAME}' => $params['class_name']]);
    
    if ($template_engine === 'timber') {
      $file_type = 'html.twig';
      $partials_path = 'views/partials';
    } else {
      $file_type = 'php';
      $partials_path = 'partials';
    }
    
    $partial->save("$theme_path/$partials_path/$filename.$file_type", $params['override']);
  }
  
  /**
   * Asks for partial class name
   * @return string
   */
  private static function askForClassName()
  {
    $class_name = Dialog::getAnswer('Class name (e.g. my-partial):');
    return $class_name ? $class_name : self::askForClassName();
  }
  
  /**
   * Checks params existence and normalizes them
   * @param $params
   * @return mixed
   * @throws \Exception
   */
  private static function checkParams($params)
  {
    // checking existence
    if (!$params['class_name'] || !$params['theme']) {
      throw new \Exception('Error: unable to create partial template because of missing parameters.');
    }
    
    // normalizing
    $theme = StringsManager::toKebabCase($params['theme']);
    $override = strtolower($params['override']);
  
    if ($override !== 'ask' && $override !== 'force') {
      $override = false;
    }
    
    if (!Config::get("themes.$theme")) {
      throw new \Exception("Error: theme '$theme' doesn't exist.");
    }
    
    return [
      'class-name' => $params['class_name'],
      'theme' => $theme,
      'override' => $override
    ];
  }
}
