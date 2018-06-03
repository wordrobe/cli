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
    try {
      $theme = self::askForTheme();
      $post_type = self::askForPostType($theme);
      self::build([
        'post-type' => $post_type,
        'theme' => $theme,
        'override' => 'ask'
      ]);
      Dialog::write('Single template added!', 'green');
    } catch (\Exception $e) {
      Dialog::write($e->getMessage(), 'red');
      exit;
    }
  }
  
  /**
   * Builds single template
   * @param array $params
   * @example SingleBuilder::create([
   *  'post-type' => $post_type,
   *  'theme' => $theme,
   *  'override' => 'ask'|'force'|false
   * ]);
   */
  public static function build($params)
  {
    $params = self::checkParams($params);
    $filename = 'single-' . $params['post-type'];
    $template_engine = Config::get('themes.' . $params['theme'] . '.template-engine', true);
    $theme_path = PROJECT_ROOT . '/' . Config::get('themes-path', true) . '/' . $params['theme'];
    $single_ctrl = new Template("$template_engine/single", ['{POST_TYPE}' => $params['post-type']]);
    
    if ($template_engine === 'timber') {
      self::buildView($single_ctrl, $filename, $theme_path, $params['override']);
    }
  
    $single_ctrl->save("$theme_path/$filename.php", $params['override']);
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
    $post_types = Config::get("themes.$theme.post-types", ['type' => 'array']);
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
    if (!$params['post-type'] || !$params['theme']) {
      throw new \Exception('Error: unable to create single template because of missing parameters.');
    }
    
    // normalizing
    $post_type = StringsManager::toKebabCase($params['post-type']);
    $theme = StringsManager::toKebabCase($params['theme']);
    $override = strtolower($params['override']);
  
    if ($override !== 'ask' && $override !== 'force') {
      $override = false;
    }
  
    Config::check("themes.$theme", 'array', "Error: theme '$theme' doesn't exist.");
    
    if (!in_array($post_type, Config::get("themes.$theme.post-types", ['type' => 'array']))) {
      throw new \Exception("Error: post type '$post_type' not found in '$theme' theme.");
    }
    
    return [
      'post-type' => $post_type,
      'theme' => $theme,
      'override' => $override
    ];
  }
}
