<?php

namespace Wordrobe\ThemeEntity;

/**
 * Class AjaxServiceFactory
 * @package Wordrobe\ThemeEntity
 */
class AjaxServiceFactory implements ThemeEntityFactory
{
  /**
   * @param array $args
   * @return null|AjaxService
   */
  public static function create($args)
  {
    try {
      $ajax_service = new AjaxService($args['action'], $args['logic']);
    } catch (\Exception $e) {
      echo $e->getMessage();
      return null;
    }

    return $ajax_service;
  }
}