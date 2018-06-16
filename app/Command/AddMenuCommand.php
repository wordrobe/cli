<?php

namespace Wordrobe\Command;

/**
 * Class AddMenuCommand
 * @package Wordrobe\Command
 */
class AddMenuCommand extends AddCommand
{
  protected function configure()
  {
    $this->setName('add:menu');
    $this->setDescription('Adds a new menu to your theme');
    $this->setBuilder('MenuBuilder');
  }
}
