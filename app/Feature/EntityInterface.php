<?php

namespace Wordrobe\Feature;

use Timber\Post;

/**
 * Interface EntityInterface
 * @package Wordrobe\Feature
 */
interface EntityInterface
{
  public function __construct(Post $post);
}
