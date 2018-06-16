<?php

namespace Wordrobe\Command;

/**
 * Class AddPartialCommand
 * @package Wordrobe\Command
 */
class AddPartialCommand extends AddCommand
{
  protected function configure()
  {
    $this->setName('add:partial');
    $this->setDescription('Adds a new partial template to your theme');
    $this->setBuilder('PartialBuilder');
  }
}
