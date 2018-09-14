<?php

namespace Wordrobe\ThemeEntity;

use Wordrobe\Helper\StringsManager;

/**
 * Class PostType
 * @package Wordrobe\ThemeEntity
 */
class PostType implements ThemeEntity
{
  private $key;
  private $settings;

  /**
   * PostType constructor
   * @param string $key
   * @param array $settings
   */
  public function __construct($key, $settings)
  {
    $this->key = StringsManager::toKebabCase($key);
    $this->settings = $settings;
    add_action('init', [$this, 'register'], 0);
  }
  
  /**
   * Handles post type registration
   */
  public function register()
  {
    register_post_type($this->key, $this->settings);
  }
  
  /**
   * Post type key getter
   * @return mixed
   */
  public function getKey()
  {
    return $this->key;
  }
}
