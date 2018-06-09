<?php

namespace Wordrobe\ThemeEntity;

use Wordrobe\Helper\StringsManager;

/**
 * Class Term
 * @package Wordrobe\ThemeEntity
 */
class Term implements ThemeEntity
{
  private $name;
  private $description;
  private $taxonomy;
  private $slug;
  private $parent;
  
  /**
   * Term constructor.
   * @param string $name
   * @param string $taxonomy
   * @param null|string $slug
   * @param string $description
   * @param null|string $parent
   */
  public function __construct($name, $taxonomy, $slug = null, $description = '', $parent = null)
  {
    $this->name = ucwords($name);
    $this->description = ucfirst($description);
    $this->taxonomy = StringsManager::toKebabCase($taxonomy);
    $this->slug = $slug ? $slug : StringsManager::toKebabCase($this->name);
    
    if ($parent) {
      $parent = StringsManager::toKebabCase($parent);
      $this->parent = term_exists($parent, $this->taxonomy) ? $parent : null;
    } else {
      $this->parent = null;
    }
    
    $this->register();
  }
  
  /**
   * Handles term registration
   */
  public function register()
  {
    if (!term_exists($this->slug, $this->taxonomy, $this->parent)) {
      wp_insert_term($this->name, $this->taxonomy, [
        'description' => $this->description,
        'slug' => $this->slug,
        'parent' => $this->parent
      ]);
    }
  }
}
