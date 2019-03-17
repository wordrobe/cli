<?php

namespace Example\Repository;

class EventRepository extends Repository
{
  /**
   * @var string $base_query
   */
  protected static $base_query = ['post_type' => 'event', 'post_status' => 'publish'];

  /**
   * @var string $entity_class
   */
  protected static $entity_class = '\Example\Entity\Event';

  /**
   * @var string $dto_class
   */
  protected static $dto_class = '\Example\DTO\EventDTO';
}
