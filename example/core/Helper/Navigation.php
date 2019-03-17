<?php

namespace Example\Helper;

use Timber\Menu;

/**
 * Class Navigation
 * @package Example\Helper
 */
final class Navigation
{
  /**
   * Retrieves a menu by location
   * @param $location
   * @return array
   */
  public static function getMenu($location)
  {
      $menu = new Menu($location);
      return $menu->get_items();
  }

  /**
   * Cleans admin menu
   * @param $pages
   */
  public static function cleanAdminMenu(array $pages)
  {
    add_action('admin_menu', function() use ($pages) {
      foreach ($pages as $page) {
        remove_menu_page($page);
      }
    });
  }
}
