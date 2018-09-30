<?php

namespace Wordrobe\Feature;

/**
 * Class ShortcodeFactory
 * @package Wordrobe\Feature
 */
class ShortcodeFactory implements FeatureFactory
{
  /**
   * @param array $args
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
