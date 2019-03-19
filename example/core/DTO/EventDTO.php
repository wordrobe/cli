<?php

namespace Example\DTO;

use Example\Entity\Event;

class EventDTO extends DTO
{
  /**
   * EventDTO constructor
   * @param \Example\Entity\Event $entity
   */
  public function __construct(Event $entity)
  {
    parent::__construct($entity);
  }

  /**
   * EventDTO's data getter
   * @return array
   */
  public function getData()
  {
    return [
      'id' => $this->entity->getId(),
      'slug' => $this->entity->getSlug(),
      'title' => $this->entity->getTitle(),
      'url' => $this->entity->getUrl(),
      'date' => $this->entity->getStartDate() . ' - ' . $this->entity->getEndDate(),
      'location' => $this->entity->getLocation(),
      'address' => $this->entity->getAddress() . ', ' . $this->entity->getZipCode() . ' - ' . $this->entity->getCity(),
      'description' => $this->entity->getDescription()
    ];
  }
}
