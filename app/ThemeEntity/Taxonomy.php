<?php

namespace Wordrobe\ThemeEntity;

use Wordrobe\Helper\StringsManager;

/**
 * Class Taxonomy
 * @package Wordrobe\ThemeEntity
 */
class Taxonomy implements ThemeEntity
{
  private $key;
  private $post_types;
  private $settings;

  /**
   * Taxonomy constructor
   * @param $key
   * @param $post_types
   * @param $settings
   */
  public function __construct($key, $post_types, $settings)
  {
    $this->key = StringsManager::toKebabCase($key);
    $this->post_types = explode(',', StringsManager::removeSpaces($post_types));
    $this->settings = $settings;
    add_action('init', [$this, 'register'], 0);
  }
  
  /**
   * Handles taxonomy registration
   */
  public function register()
  {
    register_taxonomy($this->key, $this->post_types, $this->settings);
  }
  
  /**
   * Taxonomy key getter
   * @return string
   */
  public function getKey()
  {
    return $this->key;
  }
  
  /**
   * Taxonomy post types getter
   * @return string
   */
  public function getPostTypes()
  {
    return $this->post_types;
  }
}
