<?php

namespace Wordrobe\Entity;

use Wordrobe\Helper\StringsManager;

/**
 * Class Menu
 * @package Wordrobe\Entity
 */
class Menu
{
  private $location;
  private $name;
  private $description;
  private $object;
  
  public function __construct($location, $name, $description = '')
  {
    $this->location = StringsManager::toKebabCase($location);
    $this->name = ucwords($name);
    $this->description = ucfirst($description);
    add_action('init', [$this, 'register']);
  }
  
  /**
   * Handles menu registration
   */
  public function register()
  {
    register_nav_menu($this->location, $this->description);
    $this->create();
  }
  
  /**
   * Menu object getter
   * @return mixed
   */
  public function get()
  {
    return $this->object;
  }
  
  /**
   * Creates nav menu
   */
  private function create()
  {
    $locations = get_nav_menu_locations();
    
    if (empty($locations) || !array_key_exists($this->location, $locations) || $locations[$this->location] === null) {
      $id = wp_create_nav_menu($this->name);
      $locations[$this->location] = $id;
      set_theme_mod('nav_menu_locations', $locations);
    }
    
    $this->object = wp_get_nav_menu_object($this->name);
  }
}
