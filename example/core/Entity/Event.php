<?php

namespace Example\Entity;

use Timber\Post;

/**
 * Class Event
 * @package Example\Entity
 */
class Event extends Entity
{
  /**
   * @var string $start_date
   */
  private $start_date;

  /**
   * @var string $end_date
   */
  private $end_date;

  /**
   * @var string $location
   */
  private $location;

  /**
   * @var string $address
   */
  private $address;

  /**
   * @var string $zip_code
   */
  private $zip_code;

  /**
   * @var string $city
   */
  private $city;

  /**
   * @var string $description
   */
  private $description;

  /**
   * Event constructor
   * @param \Timber\Post $post
   */
  public function __construct(Post $post)
  {
    parent::__construct($post);
    $this->start_date = get_field('start_date', $post->id);
    $this->end_date = get_field('end_date', $post->id);
    $this->location = get_field('location', $post->id);
    $this->address = get_field('address', $post->id);
    $this->zip_code = get_field('zip_code', $post->id);
    $this->city = get_field('city', $post->id);
    $this->description = get_field('description', $post->id);
  }

  /**
   * Event's start date getter
   * @return string
   */
  public function getStartDate()
  {
    return $this->start_date;
  }

  /**
   * Event's end date getter
   * @return string
   */
  public function getEndDate()
  {
    return $this->end_date;
  }

  /**
   * Event's location getter
   * @return string
   */
  public function getLocation()
  {
    return $this->location;
  }

  /**
   * Event's address getter
   * @return string
   */
  public function getAddress()
  {
    return $this->address;
  }

  /**
   * Event's zip code getter
   * @return string
   */
  public function getZipCode()
  {
    return $this->zip_code;
  }

  /**
   * Event's city getter
   * @return string
   */
  public function getCity()
  {
    return $this->city;
  }

  /**
   * Event's description getter
   * @return string
   */
  public function getDescription()
  {
    return $this->description;
  }
}
