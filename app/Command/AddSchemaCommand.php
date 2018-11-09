<?php

namespace Wordrobe\Command;

use Symfony\Component\Console\Input\InputArgument;

/**
 * Class AddSchemaCommand
 * @package Wordrobe\Command
 */
class AddSchemaCommand extends AddCommand
{
  protected function configure()
  {
    $this->setName('add:schema');
    $this->addArgument('schema', InputArgument::REQUIRED, 'Path to json schema file');
    $this->setDescription('Adds theme features from schema');
    $this->setBuilder('SchemaBuilder');
  }
}
