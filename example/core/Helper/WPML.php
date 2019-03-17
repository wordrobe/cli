<?php

namespace Example\Helper;

/**
 * Class WPML
 * @package Example\Helper
 */
final class WPML
{
  /**
   * Available languages getter
   * @return array
   */
  public static function getLanguages()
  {
    return function_exists('icl_get_languages') ? array_values(icl_get_languages('skip_missing=0')) : [];
  }
}
