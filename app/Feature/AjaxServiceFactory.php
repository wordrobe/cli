<?php

namespace Wordrobe\Feature;

/**
 * Class AjaxServiceFactory
 * @package Wordrobe\Feature
 */
class AjaxServiceFactory implements FeatureFactory
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
