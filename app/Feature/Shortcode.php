<?php

namespace Wordrobe\Feature;

use Wordrobe\Helper\StringsManager;

/**
 * Class Shortcode
 * @package Wordrobe\Feature
 */
class Shortcode implements Feature
{
  private $key;
  private $logic;

  /**
   * Shortcode constructor.
   * @param string $key
   * @param callable $logic
   */
  public function __construct($key = '', $logic)
  {
    $this->key = !empty($key) ? StringsManager::toKebabCase($key) : null;
    $this->logic = is_callable($logic) ? $logic : null;

    if (!is_null($this->key) && !is_null($this->logic)) {
      add_action('init', [$this, 'register']);
    }
  }

  /**
   * Handles shortcode registration
   */
  public function register()
  {
    if (!is_null($this->key)) {
      add_shortcode($this->key, [$this, 'registerShortcode']);
      add_filter('mce_external_plugins', [$this, 'registerPlugin']);
      add_filter('mce_buttons', [$this, 'registerButton']);
    }
  }

  /**
   * Handles shortcode registration
   * @param array $atts
   * @param null|string $content
   * @return string
   */
  public function registerShortcode($atts, $content = null)
  {
    if (!is_null($this->logic)) {
      call_user_func($this->logic, [$atts, $content]);
    }
  }

  /**
   * Handles shortcode js plugin registration
   * @param array $plugin_array
   * @return mixed
   */
  public function registerPlugin($plugin_array)
  {
    if (!is_null($this->key)) {
      $plugin_array[$this->key] = get_template_directory_uri() . '/core/shortcodes/' . $this->key . '/index.js';
    }

    return $plugin_array;
  }

  /**
   * Handles shortcode mce button registration
   * @param array $buttons
   * @return array
   */
  public function registerButton($buttons)
  {
    if (!is_null($this->key)) {
      $buttons[] = $this->key;
    }

    return $buttons;
  }
}
