<?php

namespace Wordrobe\Feature;

use Wordrobe\Helper\StringsManager;

/**
 * Class Menu
 * @package Wordrobe\Feature
 */
class Menu implements Feature
{
  private $location;
  private $name;
  private $description;
  private $object;

  /**
   * Menu constructor.
   * @param string $location
   * @param string $name
   * @param string $description
   * @param string $text_domain
   */
  public function __construct($location, $name, $description, $text_domain = 'default')
  {
    $this->location = StringsManager::toKebabCase($location);
    $this->name = ucwords($name);
    $this->description = __(ucfirst($description), StringsManager::toKebabCase($text_domain));
    add_action('init', [$this, 'register']);
  }

  /**
   * Handles menu registration
   */
  public function register()
  {
    register_nav_menu($this->location, $this->description);

    if (!wp_get_nav_menu_object($this->name)) {
      $this->create();
    }
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
    $locations[$this->location] = wp_create_nav_menu($this->name);
    set_theme_mod('nav_menu_locations', $locations);
    $this->object = wp_get_nav_menu_object($this->name);
  }
}
