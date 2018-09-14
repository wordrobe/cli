<?php

namespace Wordrobe\ThemeEntity;

/**
 * Class TermFactory
 * @package Wordrobe\ThemeEntity
 */
class TermFactory implements ThemeEntityFactory
{
  /**
   * @param $args
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