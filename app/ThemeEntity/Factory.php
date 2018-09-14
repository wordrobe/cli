<?php

namespace Wordrobe\ThemeEntity;

/**
 * Class Factory
 * @package Wordrobe\ThemeEntity
 */
class Factory
{
  /**
   * @param $entity_class
   * @param $args
   * @return null|ThemeEntity
   */
  public static function create($entity_class, $args)
  {
    $factory_class = $entity_class . 'Factory';
    try {
      $entity = call_user_func(__NAMESPACE__ . "\\$factory_class::create", $args);
    } catch (\Exception $e) {
      echo $e->getMessage();
      return null;
    }

    return $entity;
  }
}