<?php

namespace Wordrobe\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Wordrobe\Helper\Config;
use Wordrobe\Helper\Dialog;
use Wordrobe\Helper\SetupManager;

/**
 * Class AddCommand
 * @package Wordrobe\Command
 */
abstract class AddCommand extends BaseCommand
{
  private $builder;

  /**
   * Builder setter
   * @param string $builder
   */
  protected function setBuilder($builder)
  {
    if (class_exists("Wordrobe\Builder\\$builder")) {
      $this->builder = $builder;
    } else {
      Dialog::write('Error: ' . $builder . ' is not defined. Run "vendor/bin/wordrobe list" to show available commands list.', 'red');
      exit;
    }
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
    $this->checkSetup();
    call_user_func('Wordrobe\Builder\\' . $this->builder::startWizard);
  }

  /**
   * Checks project setup
   */
  private function checkSetup()
  {
    if (!Config::exists()) {
      if (Dialog::getConfirmation('Your project is not configured yet. Do you want to run setup right now?', true, 'yellow')) {
        SetupManager::install();
        Dialog::write('Resuming ' . $this->builder . ' wizard...', 'cyan');
      } else {
        Dialog::write('Unable to continue.', 'red');
        exit;
      }
    }
  }
}
