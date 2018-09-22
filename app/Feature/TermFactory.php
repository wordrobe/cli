<?php

namespace Wordrobe\Feature;

/**
 * Class TermFactory
 * @package Wordrobe\Feature
 */
class TermFactory implements FeatureFactory
{
  /**
   * @param array $args
   * @return null|Term
   */
  public static function create($args)
  {
    try {
      $term = new Term($args['name'], $args['taxonomy'], $args['slug'], $args['description'], $args['parent']);
    } catch (\Exception $e) {
      echo $e->getMessage();
      return null;
    }

    return $term;
  }
}