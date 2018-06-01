<?php

namespace Wordrobe\Builder;

use Wordrobe\Config;
use Wordrobe\Helper\Dialog;
use Wordrobe\Helper\StringsManager;
use Wordrobe\Entity\Template;

class SingleBuilder extends TemplateBuilder implements Builder
{
  /**
   * Handles single template creation wizard
   */
  public static function startWizard()
  {
    $theme = self::askForTheme(['template-engine']);
    $post_type = self::askForPostType($theme);
  
    try {
      self::build([
        'post_type' => $post_type,
        'theme' => $theme,
        'override' => 'ask'
      ]);
    } catch (\Exception $e) {
      Dialog::write($e->getMessage(), 'red');
      exit;
    }
  
    Dialog::write('Single template added!', 'green');
  }
  
  /**
   * Builds single template
   * @param array $params
   * @example SingleBuilder::create([
   *  'post_type' => $post_type,
   *  'theme' => $theme,
   *  'override' => 'ask'|'force'|false
   * ]);
   */
  public static function build($params)
  {
    $params = self::checkParams($params);
    $filename = 'single-' . $params['post_type'];
    $template_engine = Config::get('themes.' . $params['theme'] . '.template-engine');
    $theme_path = PROJECT_ROOT . '/' . Config::get('themes-path') . '/' . $params['theme'];
    $single_ctrl = new Template("$template_engine/single", ['{POST_TYPE}' => $params['post_type']]);
    $single_ctrl->save("$theme_path/$filename.php", $params['override']);
    
    if ($template_engine === 'timber') {
      self::buildView($single_ctrl, $filename, $theme_path, $params['override']);
    }
  }
  
  /**
   * Builds single view
   * @param Template $controller
   * @param string $filename
   * @param string $theme_path
   * @param mixed $override
   */
  private static function buildView($controller, $filename, $theme_path, $override)
  {
    $controller->fill('{VIEW_FILENAME}', $filename);
    $view = new Template('timber/view');
    $view->save("$theme_path/views/default/$filename.html.twig", $override);
  }
  
  /**
   * Asks for post type
   * @param $theme
   * @return string
   */
  private static function askForPostType($theme)
  {
    $post_types = Config::expect("themes.$theme.post-types", 'array');
    $post_types = array_diff($post_types, ['post']);
    
    if (!empty($post_types)) {
      return Dialog::getChoice('Post type:', array_values($post_types), null);
    }
    
    Dialog::write('Error: before creating a single, you need to define a custom post type.', 'red');
    exit;
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
    if (!$params['post_type'] || !$params['theme']) {
      throw new \Exception('Error: unable to create single template because of missing parameters.');
    }
    
    // normalizing
    $post_type = StringsManager::toKebabCase($params['post_type']);
    $theme = StringsManager::toKebabCase($params['theme']);
    $override = ($params['override'] === 'ask' || $params['override'] === 'force') ? $params['override'] : false;
    
    if (!Config::get("themes.$theme")) {
      throw new \Exception("Error: theme '$theme' doesn't exist.");
    }
    
    return [
      'post_type' => $post_type,
      'theme' => $theme,
      'override' => $override
    ];
  }
}
