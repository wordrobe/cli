<?php

namespace Wordrobe\Feature;

/**
 * Class TaxonomyFactory
 * @package Wordrobe\Feature
 */
class TaxonomyFactory implements FeatureFactory
{
  /**
   * @param array $args
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