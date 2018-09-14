<?php

namespace Wordrobe\ThemeEntity;

/**
 * Class PostTypeFactory
 * @package Wordrobe\ThemeEntity
 */
class PostTypeFactory implements ThemeEntityFactory
{
  /**
   * @param $args
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