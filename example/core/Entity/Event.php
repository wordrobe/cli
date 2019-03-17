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
    $this->start_date = $post->start_date;
    $this->end_date = $post->end_date;
    $this->location = $post->location;
    $this->address = $post->address;
    $this->zip_code = $post->zip_code;
    $this->city = $post->city;
    $this->description = $post->description;
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
