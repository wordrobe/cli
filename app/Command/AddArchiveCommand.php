<?php

namespace Wordrobe\Command;

/**
 * Class AddArchiveCommand
 * @package Wordrobe\Command
 */
class AddArchiveCommand extends AddCommand
{
  protected function configure()
  {
    $this->setName('add:archive');
    $this->setDescription('Adds a new archive template to your theme');
    $this->setBuilder('ArchiveBuilder');
  }
}
