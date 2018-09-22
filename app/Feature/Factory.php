<?php

namespace Wordrobe\Feature;

/**
 * Class Factory
 * @package Wordrobe\Feature
 */
class Factory
{
  /**
   * @param string $entity_class
   * @param array $args
   * @return null|Feature
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