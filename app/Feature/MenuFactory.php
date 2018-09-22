<?php

namespace Wordrobe\Feature;

/**
 * Class MenuFactory
 * @package Wordrobe\Feature
 */
class MenuFactory implements FeatureFactory
{
  /**
   * @param array $args
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