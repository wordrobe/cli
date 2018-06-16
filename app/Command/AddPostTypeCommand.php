<?php

namespace Wordrobe\Command;

/**
 * Class AddPostTypeCommand
 * @package Wordrobe\Command
 */
class AddPostTypeCommand extends AddCommand
{
  protected function configure()
  {
    $this->setName('add:post-type');
    $this->setDescription('Adds a new post type to your theme');
    $this->setBuilder('PostTypeBuilder');
  }
}
