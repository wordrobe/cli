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
  private $general_name;
  private $singular_name;
  private $text_domain;
  private $capability_type;
  private $icon;
  private $description;
  
  /**
   * PostType constructor
   * @param string $key
   * @param string $general_name
   * @param string $singular_name
   * @param string $text_domain
   * @param string $capability_type
   * @param string $icon
   * @param string $description
   */
  public function __construct($key, $general_name, $singular_name, $text_domain, $capability_type = 'post', $icon = 'dashicons-admin-post', $description = '')
  {
    $this->key = StringsManager::toKebabCase($key);
    $this->general_name = ucwords($general_name);
    $this->singular_name = ucwords($singular_name);
    $this->text_domain = StringsManager::toKebabCase($text_domain);
    $this->capability_type = ($capability_type === 'post' || $capability_type === 'page') ? $capability_type : 'post';
    $this->icon = StringsManager::toKebabCase($icon);
    $this->description = ucfirst($description);
    add_action('init', [$this, 'register'], 0);
  }
  
  /**
   * Handles post type registration
   */
  public function register()
  {
    $settings = $this->getSettings();
    register_post_type($this->key, $settings);
  }
  
  /**
   * Post type key getter
   * @return mixed
   */
  public function getKey()
  {
    return $this->key;
  }
  
  /**
   * Returns all post type's supportable features according to capability type
   * @return array
   */
  private function getSupportableFeatures()
  {
    $supports = ['title', 'editor', 'author', 'thumbnail', 'excerpt', 'trackbacks', 'custom-fields', 'comments', 'revisions'];
    
    if ($this->capability_type === 'page') {
      $supports[] = 'page-attributes';
    } else {
      $supports[] = 'post-formats';
    }
    
    return $supports;
  }
  
  /**
   * Returns post type's admin labels
   * @return array
   */
  private function getLabels()
  {
    return [
      'name' => _x($this->general_name, "$this->singular_name General Name", $this->text_domain),
      'singular_name' => _x($this->singular_name, "$this->singular_name Singular Name", $this->text_domain),
      'menu_name' => __($this->general_name, $this->text_domain),
      'name_admin_bar' => __($this->singular_name, $this->text_domain),
      'archives' => __("$this->singular_name Archives", $this->text_domain),
      'attributes' => __("$this->singular_name Attributes", $this->text_domain),
      'parent_item_colon' => __("Parent $this->singular_name:", $this->text_domain),
      'all_items' => __("All $this->general_name", $this->text_domain),
      'add_new_item' => __("Add New $this->singular_name", $this->text_domain),
      'add_new' => __("Add New", $this->text_domain),
      'new_item' => __("New $this->singular_name", $this->text_domain),
      'edit_item' => __("Edit $this->singular_name", $this->text_domain),
      'update_item' => __("Update $this->singular_name", $this->text_domain),
      'view_item' => __("View $this->singular_name", $this->text_domain),
      'view_items' => __("View $this->general_name", $this->text_domain),
      'search_items' => __("Search $this->singular_name", $this->text_domain),
      'not_found' => __("Not $this->singular_name found", $this->text_domain),
      'not_found_in_trash' => __("Not $this->singular_name found in Trash", $this->text_domain),
      'featured_image' => __("Featured Image", $this->text_domain),
      'set_featured_image' => __("Set featured image", $this->text_domain),
      'remove_featured_image' => __("Remove featured image", $this->text_domain),
      'use_featured_image' => __("Use as featured image", $this->text_domain),
      'insert_into_item' => __("Insert into $this->singular_name", $this->text_domain),
      'uploaded_to_this_item' => __("Uploaded to this $this->singular_name", $this->text_domain),
      'items_list' => __("$this->general_name list", $this->text_domain),
      'items_list_navigation' => __("$this->general_name list navigation", $this->text_domain),
      'filter_items_list' => __("Filter $this->general_name list", $this->text_domain),
    ];
  }
  
  /**
   * Returns post type's settings
   * @return array
   */
  private function getSettings()
  {
    return [
      'label' => __($this->general_name, $this->text_domain),
      'description' => __($this->description, $this->text_domain),
      'labels' => $this->getLabels(),
      'capability_type' => $this->capability_type,
      'hierarchical' => $this->capability_type === 'page',
      'has_archive' => true,
      'supports' => $this->getSupportableFeatures(),
      'show_ui' => true,
      'show_in_menu' => true,
      'menu_position' => 5,
      'menu_icon' => $this->icon,
      'show_in_admin_bar' => true,
      'show_in_nav_menus' => true,
      'can_export' => true,
      'public' => true,
      'publicly_queryable' => true,
      'exclude_from_search' => false,
      'rewrite' => [
        'slug' => strtolower($this->general_name),
        'with_front' => false
      ]
    ];
  }
}
