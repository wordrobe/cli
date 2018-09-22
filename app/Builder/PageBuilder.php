<?php

namespace Wordrobe\Builder;

use Wordrobe\Helper\Config;
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
    $params = self::checkParams($params);
    $filename = StringsManager::toKebabCase($params['name']);
    $theme_path = Config::getRootPath() . '/' . Config::get('themes-path', true) . '/' . $params['theme'];
    $page_ctrl = new Template('page', ['{TEMPLATE_NAME}' => $params['name']]);
    $page_ctrl->save("$theme_path/pages/$filename.php", $params['override']);
    self::buildView($page_ctrl, $filename, $theme_path, $params['override']);
  }
  
  /**
   * Builds page view
   * @param Template $controller
   * @param string $filename
   * @param string $theme_path
   * @param mixed $override
   * @throws \Exception
   */
  private static function buildView($controller, $filename, $theme_path, $override)
  {
    $controller->fill('{VIEW_FILENAME}', $filename);
    $view = new Template('view');
    $view->save("$theme_path/templates/pages/$filename.html.twig", $override);
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
  private static function checkParams($params)
  {
    // checking existence
    if (!$params['name'] || !$params['theme']) {
      throw new \Exception('Error: unable to create page template because of missing parameters.');
    }
    
    // normalizing
    $name = ucwords($params['name']);
    $theme = StringsManager::toKebabCase($params['theme']);
    $override = strtolower($params['override']);
  
    if ($override !== 'ask' && $override !== 'force') {
      $override = false;
    }
  
    Config::check("themes.$theme", 'array', "Error: theme '$theme' doesn't exist.");
    
    return [
      'name' => $name,
      'theme' => $theme,
      'override' => $override
    ];
  }
}
