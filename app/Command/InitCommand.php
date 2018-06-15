<?php

namespace Wordrobe\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Wordrobe\Config;
use Wordrobe\Helper\Dialog;
use Wordrobe\Builder\ConfigBuilder;
use Wordrobe\Builder\ThemeBuilder;

/**
 * Class InitCommand
 * @package Wordrobe\Command
 */
class InitCommand extends BaseCommand
{
  protected function configure()
  {
    $this->setName('init');
    $this->setDescription('Initializes Wordrobe');
  }

  /**
   * @param InputInterface $input
   * @param OutputInterface $output
   * @return int|null|void
   */
  protected function execute(InputInterface $input, OutputInterface $output)
  {
    parent::execute($input, $output);

    if (!Config::exists()) {
      ConfigBuilder::startWizard();
    }

    if (Dialog::getConfirmation('Do you want to add a new theme right now?', true, 'green')) {
      ThemeBuilder::startWizard();
    }
  }
}
