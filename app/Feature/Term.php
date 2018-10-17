<?php

namespace Wordrobe\Feature;

use Wordrobe\Helper\StringsManager;

/**
 * Class Term
 * @package Wordrobe\Feature
 */
class Term implements Feature
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
   * @param int $parent
   */
  public function __construct($name, $taxonomy, $slug = null, $description = '', $parent = 0)
  {
    $this->name = ucwords($name);
    $this->description = ucfirst($description);
    $this->taxonomy = StringsManager::toKebabCase($taxonomy);
    $this->slug = $slug ? $slug : StringsManager::toKebabCase($this->name);
    $this->parent = is_int($parent) ? $parent : 0;
    add_action('init', [$this, 'register']);
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
