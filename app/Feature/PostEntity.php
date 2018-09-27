<?php

namespace Wordrobe\Feature;

use Timber\Post;

/**
 * Class PostEntity
 * @package Wordrobe\Feature
 */
abstract class PostEntity extends Post
{
  /**
   * Handles entity to dto conversion
   * @return array
   */
  public abstract function toDTO();
}