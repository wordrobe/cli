<?php

namespace Wordrobe\ThemeEntity;

use Wordrobe\Helper\StringsManager;

/**
 * Class AjaxService
 * @package Wordrobe\ThemeEntity
 */
class AjaxService implements ThemeEntity
{
  private $action;
  private $logic;
  
  /**
   * AjaxService constructor.
   * @param string $action
   * @param null|callable $logic
   */
  public function __construct($action, $logic)
  {
    $this->action = StringsManager::toSnakeCase($action);
    $this->logic = is_callable($logic) ? $logic : null;
    add_action("wp_ajax_nopriv_$this->action", [$this, 'register']);
    add_action("wp_ajax_$this->action", [$this, 'register']);
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
