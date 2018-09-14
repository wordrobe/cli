<?php

namespace Wordrobe\ThemeEntity;

/**
 * Class MenuFactory
 * @package Wordrobe\ThemeEntity
 */
class MenuFactory implements ThemeEntityFactory
{
  /**
   * @param $args
   * @return null|Menu
   */
  public static function create($args)
  {
    try {
      $menu = new Menu($args['location'], $args['name'], $args['description'], $args['text_domain']);
    } catch (\Exception $e) {
      echo $e->getMessage();
      return null;
    }

    return $menu;
  }
}