<?php

namespace Wordrobe\ThemeEntity;

/**
 * Interface ThemeEntityFactory
 * @package Wordrobe\ThemeEntityFactory
 */
interface ThemeEntityFactory
{
  /**
   * Handles ThemeEntity creation
   * @param $args
   * @return null|ThemeEntity
   */
  public static function create($args);
}