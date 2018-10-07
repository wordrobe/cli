<?php

namespace Wordrobe\Command;

/**
 * Class AddComponentCommand
 * @package Wordrobe\Command
 */
class AddComponentCommand extends AddCommand
{
  protected function configure()
  {
    $this->setName('add:component');
    $this->setDescription('Adds a new component template to your theme');
    $this->setBuilder('ComponentBuilder');
  }
}
