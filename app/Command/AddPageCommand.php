<?php

namespace Wordrobe\Command;

/**
 * Class AddPageCommand
 * @package Wordrobe\Command
 */
class AddPageCommand extends AddCommand
{
  protected function configure()
  {
    $this->setName('add:page');
    $this->setDescription('Adds a new page template to your theme');
    $this->setBuilder('PageBuilder');
  }
}
