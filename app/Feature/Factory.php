<?php

namespace Wordrobe\Feature;

/**
 * Class Factory
 * @package Wordrobe\Feature
 */
class Factory
{
  /**
   * @param string $feature_class
   * @param array $args
   * @return null|Feature
   */
  public static function create($feature_class, $args)
  {
    $factory_class = $feature_class . 'Factory';
    try {
      $feature = call_user_func(__NAMESPACE__ . "\\$factory_class::create", $args);
    } catch (\Exception $e) {
      echo $e->getMessage();
      return null;
    }

    return $feature;
  }
}
