<?php

namespace Wordrobe\Command;

/**
 * Class AddTermCommand
 * @package Wordrobe\Command
 */
class AddTermCommand extends AddCommand
{
  protected function configure()
  {
    $this->setName('add:term');
    $this->setDescription('Adds a new term to your theme');
    $this->setBuilder('TermBuilder');
  }
}
