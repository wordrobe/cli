<?php

namespace Wordrobe\Entity;

use Wordrobe\Helper\StringsManager;

/**
 * Class Shortcode
 * @package Wordrobe\Entity
 */
class Shortcode
{
  private $key;
  private $logic;

  /**
   * Shortcode constructor.
   * @param $key
   * @param null|callable $logic
   */
  public function __construct($key, $logic = null)
  {
    $this->key = StringsManager::toKebabCase($key);
    $this->logic = is_callable($logic) ? $logic : null;
    add_action('init', [$this, 'register']);
  }

  /**
   * Handles shortcode registration
   */
  public function register()
  {
    add_shortcode($this->key, [$this, 'registerShortcode']);
    add_filter('mce_external_plugins', [$this, 'registerPlugin']);
    add_filter('mce_buttons', [$this, 'registerButton']);
  }

  /**
   * Handles shortcode registration
   * @param $atts
   * @param null $content
   * @return string
   */
  private function registerShortcode($atts, $content = null)
  {
    if (!is_null($this->logic)) {
      $this->logic($atts, $content);
    }
  }

  /**
   * Handles shortcode js plugin registration
   * @param $plugin_array
   * @return mixed
   */
  private function registerPlugin($plugin_array)
  {
    $plugin_array[$this->key] =  get_template_directory_uri() . '/includes/shortcodes/' . $this->key . '/plugin.js';
    return $plugin_array;
  }

  /**
   * Handles shortcode mce button registration
   * @param $buttons
   * @return array
   */
  private function registerButton($buttons)
  {
    $buttons[] = $this->key;
    return $buttons;
  }
}
