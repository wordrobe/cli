<?php

namespace Wordrobe\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Wordrobe\Helper\SetupManager;

/**
 * Class SetupCommand
 * @package Wordrobe\Command
 */
class SetupCommand extends BaseCommand
{
  protected function configure()
  {
    $this->setName('setup');
    $this->setDescription('Configures your project in order to use Wordrobe');
  }

  /**
   * @param InputInterface $input
   * @param OutputInterface $output
   * @return int|null|void
   */
  protected function execute(InputInterface $input, OutputInterface $output)
  {
    parent::execute($input, $output);
    SetupManager::install();
  }
}
