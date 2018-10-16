<?php

namespace Wordrobe\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Wordrobe\Helper\Config;
use Wordrobe\Helper\Dialog;
use Wordrobe\Builder\ConfigBuilder;
use Wordrobe\Builder\ThemeBuilder;


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
   * @throws \Exception
   */
  protected function execute(InputInterface $input, OutputInterface $output)
  {
    parent::execute($input, $output);

    if (!Config::exists()) {
      ConfigBuilder::startWizard();
    } else {
      Dialog::write('Project configuration already exists! Check wordrobe.json file in the project root for details.', 'green');
    }

    if (Dialog::getConfirmation('Do you want to add a new theme right now?', true, 'yellow')) {
      ThemeBuilder::startWizard();
    }
  }
}
