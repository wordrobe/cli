<?php

namespace Wordrobe\ThemeEntity;

/**
 * Class Factory
 * @package Wordrobe\ThemeEntity
 */
class Factory
{
  /**
   * @param $name
   * @param $args
   * @return null|ThemeEntity
   */
  public static function create($name, $args)
  {
    try {
      $entity = new $name($args);
    } catch (\Exception $e) {
      echo $e->getMessage();
      return null;
    }

    return $entity;
  }
}