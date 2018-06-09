<?php

namespace Wordrobe\ThemeEntity;

use Wordrobe\Helper\StringsManager;

/**
 * Class AjaxService
 * @package Wordrobe\ThemeEntity
 */
class AjaxService implements ThemeEntity
{
  private $name;
  private $logic;
  
  /**
   * AjaxService constructor.
   * @param string $name
   * @param null|callable $logic
   */
  public function __construct($name, $logic)
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
      call_user_func($this->logic);
    }
  }
}
