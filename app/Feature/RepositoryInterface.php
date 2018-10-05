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
   * @param mixed $post
   * @return EntityInterface
   */
  public static function getEntityByPost($post = null);

  /**
   * Returns a DTO from an Entity
   * @param mixed $post
   * @return DTOInterface
   */
  public static function getDTOByPost($post = null);

  /**
   * Returns Entity data formatted by its DTO
   * @param mixed $post
   * @return mixed
   */
  public static function getFormattedDataByPost($post = null);
}