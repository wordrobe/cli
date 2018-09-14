<?php

namespace Wordrobe\ThemeEntity;

/**
 * Class TaxonomyFactory
 * @package Wordrobe\ThemeEntity
 */
class TaxonomyFactory implements ThemeEntityFactory
{
  /**
   * @param $args
   * @return null|Taxonomy
   */
  public static function create($args)
  {
    try {
      $taxonomy = new Taxonomy($args['key'], $args['post_types'], $args['settings']);
    } catch (\Exception $e) {
      echo $e->getMessage();
      return null;
    }

    return $taxonomy;
  }
}