<?php

namespace Wordrobe\Feature;

/**
 * Interface RepositoryInterface
 * @package Wordrobe\Feature
 */
interface RepositoryInterface
{
  /**
   * Returns an entity from a Timber\Post
   * @param mixed $post_id
   * @return EntityInterface
   */
  public static function getEntity($post_id = null);

  /**
   * Returns a DTO from an Entity
   * @param mixed $post_id
   * @return DTOInterface
   */
  public static function getDTO($post_id = null);

  /**
   * Returns Entity data formatted by its DTO
   * @param mixed $post_id
   * @return mixed
   */
  public static function getFormattedData($post_id = null);
}