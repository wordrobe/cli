<?php

namespace Wordrobe\Command;

/**
 * Class AddServiceCommand
 * @package Wordrobe\Command
 */
class AddServiceCommand extends AddCommand
{
  protected function configure()
  {
    $this->setName('add:service');
    $this->setDescription('Adds a new service to your theme');
    $this->setBuilder('ServiceBuilder');
  }
}
