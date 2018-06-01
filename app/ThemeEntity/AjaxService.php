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
  
  /**
   * AjaxService constructor.
   * @param $name
   */
  public function __construct($name)
  {
    $this->name = StringsManager::toSnakeCase($name);
    add_action("wp_ajax_nopriv_$this->name", [$this, 'register']);
    add_action("wp_ajax_$this->name", [$this, 'register']);
  }
  
  /**
   * Defines ajax service logic
   */
  public function register()
  {
    /*
     * Define service's logic here to make a response and send it to client.
     *
     * To build an api-like service, you can use wp_send_json_success($response) and
     * wp_send_json_error($response) if you need to send different data according to
     * your logic's success or error cases; use wp_send_json($response) otherwise.
     *
     * For a non-json response, you can simply echo the generated data. In this case,
     * don't forget to call wp_die() at the end of the function to stop execution.
     *
     * For more details, please check documentation at:
     * https://codex.wordpress.org/Plugin_API/Action_Reference/wp_ajax_(action)
     * https://codex.wordpress.org/Function_Reference/wp_send_json
     * https://codex.wordpress.org/Function_Reference/wp_send_json_success
     * https://codex.wordpress.org/Function_Reference/wp_send_json_error
     */
  }
}
