<?php

namespace Wordrobe\Feature;

/**
 * Interface FeatureFactory
 * @package Wordrobe\FeatureFactory
 */
interface FeatureFactory
{
  /**
   * Handles Feature creation
   * @param array $args
   * @return null|Feature
   */
  public static function create($args);
}
