<?php

namespace Wordrobe\Builder;

use Wordrobe\Helper\Config;
use Wordrobe\Helper\Dialog;
use Wordrobe\Helper\StringsManager;
use Wordrobe\Entity\Template;

class ShortcodeBuilder extends TemplateBuilder implements Builder
{
  /**
   * Handles single template creation wizard
   */
  public static function startWizard()
  {
    try {
      $theme = self::askForTheme();
      $key = self::askForKey();
      $attributes = self::askForAttributes();
      $title = self::askForTitle($key);
      $icon = self::askForIcon();
      self::build([
        'key' => $key,
        'attributes' => $attributes,
        'title' => $title,
        'icon' => $icon,
        'theme' => $theme,
        'override' => 'ask'
      ]);
      Dialog::write('Shortcode added!', 'green');
    } catch (\Exception $e) {
      Dialog::write($e->getMessage(), 'red');
      exit;
    }
  }

  /**
   * Builds shortcode
   * @param array $params
   * @example ShortcodeBuilder::create([
   *  'key' => $key,
   *  'attributes' => $attributes,
   *  'title' => $title,
   *  'icon' => $icon,
   *  'theme' => $theme,
   *  'override' => 'ask'|'force'|false
   * ]);
   * @throws \Exception
   */
  public static function build($params)
  {
    $params = self::checkParams($params);
    $theme_path = Config::get('project-root') . '/' . Config::get('themes-path', true) . '/' . $params['theme'];
    $shortcode_ctrl = new Template('shortcode', ['{KEY}' => $params['key']]);
    $shortcode_plugin = new Template('shortcode-plugin', [
      '{TITLE}' => $params['title'],
      '{KEY}' => $params['plugin-key'],
      '{ICON}' => $params['icon'],
      '{SHORTCODE}' => $params['key'],
      '{ATTRIBUTES}' => $params['attributes']
    ]);
    $shortcode_ctrl->save("$theme_path/includes/shortcodes/" . $params['key'] . "/index.php", $params['override']);
    $shortcode_plugin->save("$theme_path/includes/shortcodes/" . $params['key'] . "/index.js", $params['override']);
  }

  /**
   * Asks for shortcode key
   * @return string
   */
  private static function askForKey()
  {
    $name = Dialog::getAnswer('Key (e.g. my-shortcode):');
    return $name ?: self::askForKey();
  }

  /**
   * Asks for shortcode's attributes
   * @return string
   */
  private static function askForAttributes()
  {
    return Dialog::getAnswer('Attributes (comma separated):');
  }

  /**
   * Asks for shortcode title
   * @param string $key
   * @return mixed
   */
  private static function askForTitle($key)
  {
    $default = ucwords(StringsManager::removeDashes($key, ' '));
    return Dialog::getAnswer("Title [$default]:", $default);
  }

  /**
   * Asks for shortcode icon
   * @return mixed
   */
  private static function askForIcon()
  {
    return Dialog::getAnswer('Icon [dashicons-editor-code]:', 'dashicons-editor-code');
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
    if (!$params['key'] || !$params['theme']) {
      throw new \Exception('Error: unable to create shortcode because of missing parameters.');
    }

    // normalizing
    $key = StringsManager::toKebabCase($params['key']);
    $plugin_key = StringsManager::toSnakeCase($key);
    $title = $params['title'] ? StringsManager::removeMultipleSpaces($params['title']) : StringsManager::removeDashes($key, ' ');
    $icon = $params['icon'] ? StringsManager::toKebabCase($params['icon']) : 'dashicons-editor-code';
    $theme = StringsManager::toKebabCase($params['theme']);
    $override = strtolower($params['override']);
    $attributes = '';

    if (!empty($params['attributes'])) {
      $attributesList = explode(',', $params['attributes']);

      foreach ($attributesList as $attr) {
        if (!empty(trim($attr))) {
          $attributes .= ' ' . StringsManager::toKebabCase($attr) . '=""';
        }
      }
    }

    if ($override !== 'ask' && $override !== 'force') {
      $override = false;
    }

    Config::check("themes.$theme", 'array', "Error: theme '$theme' doesn't exist.");

    return [
      'key' => $key,
      'plugin-key' => $plugin_key,
      'attributes' => $attributes,
      'title' => ucwords($title),
      'icon' => $icon,
      'theme' => $theme,
      'override' => $override
    ];
  }
}
