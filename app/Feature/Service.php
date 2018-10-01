<?php

namespace Wordrobe\Feature;

/**
 * Class Service
 * @package Wordrobe\Feature
 */
class Service implements Feature
{
  private $namespace;
  private $route;
  private $args;
  private $override;

  /**
   * Service constructor
   * @param string $namespace
   * @param string $route
   * @param array $args
   * @param bool $override
   */
  public function __construct($namespace, $route, $args = [], $override = false)
  {
    $this->namespace = $namespace;
    $this->route = $route;
    $this->args = $args;
    $this->override = $override;
    add_action('rest_api_init', [$this, 'register']);
  }

  /**
   * Registers service
   */
  public function register()
  {
    register_rest_route($this->namespace, $this->route, $this->args, $this->override);
  }
}
