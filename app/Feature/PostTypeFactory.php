<?php

namespace Wordrobe\Feature;

/**
 * Class PostTypeFactory
 * @package Wordrobe\Feature
 */
class PostTypeFactory implements FeatureFactory
{
  /**
   * @param array $args
   * @return null|PostType
   */
  public static function create($args)
  {
    try {
      $post_type = new PostType($args['key'], $args['settings']);
    } catch (\Exception $e) {
      echo $e->getMessage();
      return null;
    }

    return $post_type;
  }
}