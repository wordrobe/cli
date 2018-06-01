<?php

namespace Wordrobe\Builder;

use Wordrobe\Config;
use Wordrobe\Helper\Dialog;
use Wordrobe\Helper\StringsManager;
use Wordrobe\Entity\Template;

class PageBuilder extends TemplateBuilder implements Builder
{
  /**
   * Handles page template creation wizard
   */
  public static function startWizard()
  {
    $theme = self::askForTheme(['template-engine']);
    $name = self::askForName();
  
    try {
      self::build([
        'name' => $name,
        'theme' => $theme
      ]);
    } catch (\Exception $e) {
      Dialog::write($e->getMessage(), 'red');
      exit;
    }
  
    Dialog::write('Page template added!', 'green');
  }
  
  /**
   * Builds page template
   * @param array $params
   * @example PageBuilder::create([
   *  'name' => $name,
   *  'theme' => $theme
   * ]);
   */
  public static function build($params)
  {
    $params = self::checkParams($params);
    $filename = StringsManager::toKebabCase($params['name']);
    $template_engine = Config::get('themes.' . $params['theme'] . '.template-engine');
    $theme_path = PROJECT_ROOT . '/' . Config::get('themes-path') . '/' . $params['theme'];
    $page_ctrl = new Template("$template_engine/page", ['{TEMPLATE_NAME}' => $params['name']]);
    $page_ctrl->save("$theme_path/pages/$filename.php");
    
    if ($template_engine === 'timber') {
      self::buildView($page_ctrl, $filename, $theme_path);
    }
  }
  
  /**
   * Builds page view
   * @param Template $controller
   * @param string $filename
   * @param string $theme_path
   */
  private static function buildView($controller, $filename, $theme_path)
  {
    $controller->fill('{VIEW_FILENAME}', $filename);
    $view = new Template('timber/view');
    $view->save("$theme_path/views/pages/$filename.html.twig");
  }
  
  /**
   * Asks for page template name
   * @return string
   */
  private static function askForName()
  {
    $name = Dialog::getAnswer('Template name (e.g. My Custom Page):');
    return $name ? $name : self::askForName();
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
    if (!$params['name'] || !$params['theme']) {
      throw new \Exception('Error: unable to create page template because of missing parameters.');
    }
    
    // normalizing
    $name = ucwords($params['name']);
    $theme = StringsManager::toKebabCase($params['theme']);
    
    if (!Config::get("themes.$theme")) {
      throw new \Exception("Error: theme '$theme' doesn't exist.");
    }
    
    return [
      'name' => $name,
      'theme' => $theme
    ];
  }
}
