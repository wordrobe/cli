<?php

namespace Wordrobe\Feature;

/**
 * Class ServiceFactory
 * @package Wordrobe\Feature
 */
class ServiceFactory implements FeatureFactory
{
  /**
   * @param array $args
   * @return null|Service
   */
  public static function create($args)
  {
    try {
      $service = new Service($args['namespace'], $args['route'], $args['options'], $args['override']);
    } catch (\Exception $e) {
      echo $e->getMessage();
      return null;
    }

    return $service;
  }
}
