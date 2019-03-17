<?php

namespace Example\Repository;

use Timber\Timber;
use Wordrobe\Feature\RepositoryInterface;

/**
 * Class Repository
 * @package Example\Repository
 */
class Repository implements RepositoryInterface
{
  /**
   * @var string $base_query
   */
  protected static $base_query = ['post_type' => ['post', 'page'], 'post_status' => 'publish'];

  /**
   * @var string $entity_class
   */
  protected static $entity_class = '\Example\Entity\Entity';

  /**
   * @var string $dto_class
   */
  protected static $dto_class = '\Example\DTO\DTO';

  /**
   * Returns an Entity from a Timber\Post
   * @param null|int $post_id
   * @return false|\Example\Entity\Entity
   */
  public static function getEntity($post_id = null)
  {
    $post_id = $post_id ?: get_the_ID();
    $args = array_merge(static::$base_query, ['post__in' => [$post_id]]);
    $post = Timber::get_post($args);
    return $post ? new static::$entity_class($post) : false;
  }

  /**
   * Returns all Entities from query
   * @param array $query_args (wp_query format)
   * @return \Example\Entity\Entity[]
   */
  public static function getAllEntities(array $query_args = [])
  {
    $args = array_merge($query_args, static::$base_query);
    $posts = Timber::get_posts($args);
    return $posts ? array_map(function($post) {
      return static::getEntity($post->id);
    }, $posts) : [];
  }

 /**
  * Returns a DTO from a Timber\Post
  * @param null|int $post_id
  * @return false|\Example\DTO\DTO
  */
  public static function getDTO($post_id = null)
  {
    $entity = static::getEntity($post_id);
    return $entity ? new static::$dto_class($entity) : false;
  }

  /**
   * Returns all DTOs from query
   * @param array $query_args (wp_query format)
   * @return \Example\DTO\DTO[]
   */
  public static function getAllDTO(array $query_args = [])
  {
    $entities = static::getAllEntities($query_args);
    return !empty($entities) ? array_map(function($entity) {
      return new static::$dto_class($entity);
    }, $entities) : [];
  }

  /**
   * Returns Entity's data formatted by DTO
   * @param null|int $post_id
   * @return array
   */
  public static function getFormattedData($post_id = null)
  {
    $dto = static::getDTO($post_id);
    return $dto ? $dto->getData() : [];
  }

  /**
   * Returns all Entities' data formatted by DTO
   * @param array $query_args (wp_query format)
   * @return array
   */
  public static function getAllFormattedData(array $query_args = [])
  {
    $dtos = static::getAllDTO($query_args);
    return !empty($dtos) ? array_map(function($dto) {
      return $dto->getData();
    }, $dtos) : [];
  }
}
