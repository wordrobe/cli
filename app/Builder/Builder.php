<?php

namespace Wordrobe\Builder;

/**
 * Interface Builder
 * @package Wordrobe\Builder
 */
interface Builder
{
  /**
   * Builds entity
   * @param array $params
   */
  public static function build($params);
}
