<?php

namespace Wordrobe\Feature;

use Wordrobe\Helper\StringsManager;

/**
 * Class AjaxService
 * @package Wordrobe\Feature
 */
class AjaxService implements Feature
{
  private $action;
  private $logic;
  
  /**
   * AjaxService constructor.
   * @param string $action
   * @param null|callable $logic
   */
  public function __construct($action = '', $logic)
  {
    if (!empty($action)) {
      $this->action = StringsManager::toSnakeCase($action);
      $this->logic = is_callable($logic) ? $logic : null;
      add_action("wp_ajax_nopriv_$this->action", [$this, 'register']);
      add_action("wp_ajax_$this->action", [$this, 'register']);
    }
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
