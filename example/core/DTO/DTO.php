<?php

namespace Example\DTO;

use Wordrobe\Feature\DTOInterface;
use Wordrobe\Feature\EntityInterface;

class DTO implements DTOInterface
{
  /**
   * @var \Wordrobe\Feature\EntityInterface $entity
   */
  protected $entity;

  /**
   * DTO's constructor
   * @param \Wordrobe\Feature\EntityInterface $entity
   */
  public function __construct(EntityInterface $entity)
  {
    $this->entity = $entity;
  }

  /**
   * DTO's data getter
   * @return array
   */
  public function getData()
  {
    return [
      'id' => $this->entity->getId(),
      'slug' => $this->entity->getSlug(),
      'title' => $this->entity->getTitle(),
      'url' => $this->entity->getUrl(),
      'content' => $this->entity->getContent(),
      'fields' => $this->entity->getCustomFields()
    ];
  }
}
