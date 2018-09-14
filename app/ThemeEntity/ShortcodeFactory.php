<?php

namespace Wordrobe\ThemeEntity;

/**
 * Class ShortcodeFactory
 * @package Wordrobe\ThemeEntity
 */
class ShortcodeFactory implements ThemeEntityFactory
{
  /**
   * @param $args
   * @return null|Shortcode
   */
  public static function create($args)
  {
    try {
      $shortcode = new Shortcode($args['key'], $args['logic']);
    } catch (\Exception $e) {
      echo $e->getMessage();
      return null;
    }

    return $shortcode;
  }
}