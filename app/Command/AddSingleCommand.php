<?php

namespace Wordrobe\Command;

/**
 * Class AddSingleCommand
 * @package Wordrobe\Command
 */
class AddSingleCommand extends AddCommand
{
  protected function configure()
  {
    $this->setName('add:single');
    $this->setDescription('Adds a new single template to your theme');
    $this->setBuilder('SingleBuilder');
  }
}
