<?php

namespace Wordrobe\Entity;

use Wordrobe\Helper\StringsManager;

/**
 * Class AjaxService
 * @package Wordrobe\Entity
 */
class AjaxService
{
  private $name;
  private $logic;
  
  /**
   * AjaxService constructor.
   * @param $name
   * @param null|callable $logic
   */
  public function __construct($name, $logic = null)
  {
    $this->name = StringsManager::toSnakeCase($name);
    $this->logic = is_callable($logic) ? $logic : null;
    add_action("wp_ajax_nopriv_$this->name", [$this, 'register']);
    add_action("wp_ajax_$this->name", [$this, 'register']);
  }
  
  /**
   * Defines ajax service logic
   */
  public function register()
  {
    if (!is_null($this->logic)) {
      $this->logic();
    }
  }
}
