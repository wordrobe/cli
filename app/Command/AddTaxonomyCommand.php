<?php

namespace Wordrobe\Command;

/**
 * Class AddTaxonomyCommand
 * @package Wordrobe\Command
 */
class AddTaxonomyCommand extends AddCommand
{
  protected function configure()
  {
    $this->setName('add:taxonomy');
    $this->setDescription('Adds a new taxonomy to your theme');
    $this->setBuilder('TaxonomyBuilder');
  }
}
